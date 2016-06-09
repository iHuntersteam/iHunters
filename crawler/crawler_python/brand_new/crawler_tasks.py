import re
from datetime import datetime

import dateutil.parser
from bs4 import UnicodeDammit
from lxml import etree, html
from lxml.html.clean import Cleaner
from celery import Task
from celery.utils import cached_property
import requests as requestslib
from requests.exceptions import (SSLError, ConnectionError, URLRequired,
                                 MissingSchema, InvalidSchema, InvalidURL,
                                 TooManyRedirects)

from brand_new.celery_app import app
from brand_new.db_connect import CrawlerPersonsConnector, CrawlerPersonPageRankConnector


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


# Abstract classes for tasks. It helps share things like db connection between tasks
class ParseTask(Task):
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
    abstract = True
    _db_connection = None

    @cached_property
    def get_connector(self):
        if self._db_connection is None:
            self._db_connection = CrawlerPersonPageRankConnector()
        return self._db_connection


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
    return page_id, ReqResponse(ReqRequest(request_url), res), page_date


@app.task(base=ParseTask, ignore_result=True)
def get_info_from(data):
    page_id, page_response, page_date = data
    page_ranks = {}
    page_url = page_response.request.url
    try:
        converted = page_response.content.decode('utf-8')
    except UnicodeDecodeError:
        converted = UnicodeDammit(page_response.content)
        if not converted.unicode_markup:
            page_ranks[page_id] = {}
            return page_ranks
        converted = converted.unicode_markup

    page_date_only = page_date.date()
    now_date = datetime.now().date()
    if page_date_only == now_date:
        last_modified_header = page_response.headers.get('Last-Modified')
        if last_modified_header:
            try:
                page_date = dateutil.parser.parse(last_modified_header)
            except ValueError:
                # if this error occurs page_date remains equal to page_date from arguments
                pass
        else:
            # TODO Add parsing url to found datetime
            pass

    root = html.fromstring(converted)
    #  delete all <script></script> tags
    cleaner = Cleaner(scripts=True)
    root = cleaner.clean_html(root)
    # we only need data from <body></body> tag
    body = root.xpath('body')[0]
    body_text = body.text_content()
    result = {}

    for pattern_name in get_info_from.search_patterns:
        rank = get_info_from.search_patterns[pattern_name].findall(body_text)
        result[pattern_name] = len(rank)
        result['date-modified'] = page_date
    page_ranks[page_id] = result
    # prepare body text to save. Remove multiple whitespaces and new-line sybmols
    body_text = re.sub('\s+', ' ', body_text)
    page_ranks[page_id]['page-text'] = body_text.strip()
    return page_ranks


@app.task(base=SaveTask, ignore_result=True)
def save_rank_to_database(data):
    save_rank_to_database.get_connector.save(data)


class WarnerTask(Task):

    _search_patterns = None
    _db_connection = None
    _session = None

    @cached_property
    def session(self):
        if self._session is None:
            self._session = requestslib.Session()
        return self._session

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

    @cached_property
    def get_connector(self):
        if self._db_connection is None:
            self._db_connection = CrawlerPersonPageRankConnector()
        return self._db_connection

    def run(self, page_id, request_url, page_date, *args, **kwargs):
        r1 = self.fetch(page_id, request_url, page_date)
        r2 = self.get_info_from(r1)
        self.save_rank_to_database(r2)

    def fetch(self, page_id, request_url, page_date):
        http_headers = {
            'User-Agent': 'iHunters Bot 1.0',
        }
        try:
            res = self.session.get(request_url, headers=http_headers)
        except (SSLError, ConnectionError, URLRequired,
                MissingSchema, InvalidSchema, InvalidURL, TooManyRedirects) as e:
            self.retry(countdown=backoff(self.request.retries), exc=e)
        return page_id, ReqResponse(ReqRequest(request_url), res), page_date

    def get_info_from(self, data):
        page_id, page_response, page_date = data
        page_ranks = {}
        page_url = page_response.request.url
        try:
            converted = page_response.content.decode('utf-8')
        except UnicodeDecodeError:
            converted = UnicodeDammit(page_response.content)
            if not converted.unicode_markup:
                page_ranks[page_id] = {}
                return page_ranks
            converted = converted.unicode_markup

        page_date_only = page_date.date()
        now_date = datetime.now().date()
        if page_date_only == now_date:
            last_modified_header = page_response.headers.get('Last-Modified')
            if last_modified_header:
                try:
                    page_date = dateutil.parser.parse(last_modified_header)
                except ValueError:
                    # if this error occurs page_date remains equal to page_date from arguments
                    pass
            else:
                # TODO Add parsing url to found datetime
                pass

        root = html.fromstring(converted)
        #  delete all <script></script> tags
        cleaner = Cleaner(scripts=True)
        root = cleaner.clean_html(root)
        # we only need data from <body></body> tag
        body = root.xpath('body')[0]
        body_text = body.text_content()
        result = {}

        for pattern_name in self.search_patterns:
            rank = self.search_patterns[pattern_name].findall(body_text)
            result[pattern_name] = len(rank)
            result['date-modified'] = page_date
        page_ranks[page_id] = result
        # prepare body text to save. Remove multiple whitespaces and new-line sybmols
        body_text = re.sub('\s+', ' ', body_text)
        page_ranks[page_id]['page-text'] = body_text.strip()
        return page_ranks

    def save_rank_to_database(self, data):
        self.get_connector.save(data)