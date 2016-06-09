from datetime import datetime
from celery import Celery, Task
from celery.utils import cached_property
import requests as requestslib
import time
import re
from db_connect import CrawlerPersonsConnector
from bs4 import UnicodeDammit
import dateutil.parser
from lxml import etree, html
from lxml.html.clean import Cleaner
from lxml.etree import XMLSyntaxError
from requests.exceptions import (SSLError, ConnectionError, URLRequired,
                                 MissingSchema, InvalidSchema, InvalidURL,
                                 TooManyRedirects)
import random


redis_backend = 'redis://192.168.1.119:6379/0'
rabbit_mq = 'amqp://testuser:test@192.168.1.119:5672/testvhost'

app = Celery('tasks', broker=rabbit_mq)
app.conf.update(
    # CELERY_RESULT_BACKEND=redis_backend,
    # CELERY_RESULT_BACKEND='rpc://',
    CELERY_TASK_RESULT_EXPIRES=86400,
    # CELERY_ALWAYS_EAGER=True - Для дебага, запускает все задачи последовательно.
)


class ReqRequest:
    def __init__(self, url):
        self.url = url

    def __str__(self):
        return '<ReqRequest url:{s.url}>'.format(s=self)


class ReqResponse:
    def __init__(self, req, response):
        self.request = req
        self.content = response.content
        self.status_code = response.status_code
        self.headers = response.headers

    def __str__(self):
        return '<ReqResponse from url:{s.request.url}>'.format(s=self)


def backoff(attempts):
    """
    Return a backoff delay, in seconds, given a number of attempts.
    The delay increases very rapidly with the number of attemps:
    5, 10, 15, 20, 25, 30, ...
    """
    return 5 ** attempts


# @app.task(rate_limit='1/m')
@app.task(max_retries=3, bind=True, ignore_result=True)
def fetch(self, page_id, request_url, page_date):
    http_headers = {
        'User-Agent': 'iHunters Bot 1.0',
    }
    try:
        res = requestslib.get(request_url, headers=http_headers)
    except (SSLError, ConnectionError, URLRequired,
            MissingSchema, InvalidSchema, InvalidURL, TooManyRedirects) as e:
        self.retry(countdown=backoff(self.request.retries), exc=e)
    time.sleep(random.randint(1, 5))  # Типа сетевая задержка
    return page_id, ReqResponse(ReqRequest(request_url), res), page_date


class ParseTask(Task):
    """
    Чтобы для каждой задачи не создавать заново словарь ключевых слов, делаю его один раз
    он будет общий для всех задач (в пределах одного воркера)
    """
    abstract = True
    _search_patterns = None

    @cached_property
    def search_patterns(self):
        if self._search_patterns is None:
            self._search_patterns = {}
            temp_connector = CrawlerPersonsConnector()
            persons_ids = ','.join(map(str, temp_connector.get_persons_ids()))
            persons_dict_wkeys = temp_connector.get_person_with_keywords(persons_ids)
            for key, value in persons_dict_wkeys.items():
                search_val = '|'.join(value)
                self._search_patterns[key] = re.compile(r'\b{}\b'.format(search_val), re.IGNORECASE | re.MULTILINE)
        return self._search_patterns


class SaveTask(Task):
    """
    Точно так-же расшариваю соединение с БД в рамках одного воркера, открывать его на каждую задачу - накладно.
    """
    abstract = True
    _db_connection = None

    @cached_property
    def get_connector(self):
        if self._db_connection is None:
            from db_connect import CrawlerPersonPageRankConnector
            temp = CrawlerPersonPageRankConnector()
            self._db_connection = temp
        return self._db_connection


@app.task(base=SaveTask, ignore_result=True)
def save_rank_to_database(data):
    save_rank_to_database.get_connector.save(data)


@app.task(base=ParseTask, ignore_result=True)
def get_info_from(data):
    page_id, page_response, page_date = data
    page_ranks = {}
    page_url = page_response.request.url
    """
    Профилировщик помог выяснить, что распознавание кодировки оказалось очень затратной операцией в celery.
    Поэтому сначала пробую раскодировать из utf-8, сейчас большинство сайтов его используют.
    Если не получается - тогда уже стартуем распознавалку.
    Если сразу запускать распознавалку, одна задача выполнялась 5 с копейками секунд, так - меньше секунды.
    """
    try:
        converted = page_response.content.decode('utf-8')
    except UnicodeDecodeError:
        converted = UnicodeDammit(page_response.content)
        converted = converted.unicode_markup

    if not converted:
        page_ranks[page_id] = {}
        return page_ranks

    page_date_only = page_date.date()
    now_date = datetime.now().date()
    if page_date_only == now_date:
        last_modified_header = page_response.headers.get('Last-Modified')
        if last_modified_header:
            try:
                page_date = dateutil.parser.parse(last_modified_header)
            except ValueError:
                # if this error occurs page_date_modified remains equal to page_date_modified
                pass
        else:
            # TODO Add parsing url to found datetime
            pass

    root = html.fromstring(converted)
    # удаляем <script> тэги
    cleaner = Cleaner(scripts=True)
    root = cleaner.clean_html(root)
    # нужен только текст внутри <body></body>
    body = root.xpath('body')[0]
    body_text = body.text_content()
    result = {}

    for pattern_name in get_info_from.search_patterns:
        rank = get_info_from.search_patterns[pattern_name].findall(body_text)
        result[pattern_name] = len(rank)
        result['date-modified'] = page_date
    page_ranks[page_id] = result
    body_text = re.sub('\s+', ' ', body_text)
    page_ranks[page_id]['page-text'] = body_text.strip()
    return page_ranks
