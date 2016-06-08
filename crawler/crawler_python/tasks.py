import logging
from datetime import datetime
from db_connect import CrawlerSitesConnector
from rank_worker import PageRankWorker
from celery import Celery
from celery.signals import worker_process_init
from celery.schedules import crontab
from celery import group
from multiprocessing import current_process


logging.basicConfig(
        format=u'%(levelname) -8s [%(asctime)s] %(module)s:%(lineno)s %(funcName)s %(message)s',
        level=logging.DEBUG,
        filename=u'info.log')

# fix AttributeError: 'Worker' object has no attribute '_config'
@worker_process_init.connect
def fix_multiprocessing(**kwargs):
    try:
        current_process()._config
    except AttributeError:
        current_process()._config = {'semprefix': '/mp'}

app = Celery('tasks')
app.conf.update(
    BROKER_URL='amqp://guest:guest@localhost:5672//',
    CELERY_RESULT_BACKEND='redis://localhost:6379/0',
    CELERY_TASK_SERIALIZER='pickle',
    # CELERY_ROUTES={
    # "tasks.crawl_url_": {"queue": "slow"},
    # "tasks.b": {"queue": "fast"}
    # }
    # CELERY_ACCEPT_CONTENT=['json'],  # Ignore other content
    # CELERY_RESULT_SERIALIZER='json',
    # CELERY_TIMEZONE='Europe/Oslo',
    # CELERY_ENABLE_UTC=True,

    #     CELERYBEAT_SCHEDULE={
    #         'crawl_url_': {
    #             'task': 'tasks.crawl_url_',
    #             'schedule': crontab(minute=0, hour=23),
    #         },
    #     }
)
app.control.broadcast(
    'rate_limit', {
        'task_name': 'tasks.crawl_url_', 'rate_limit': '200/m'
    }, reply=True)

sites_connector = CrawlerSitesConnector()
sites_id = sites_connector.get_sites_id()
sites_id = ','.join(map(str, sites_id))
pages = sites_connector.get_pages_by_site_id(sites_id)
pages = [{k: v} for k, v in pages.items()][270:280]

# @app.task(name='tasks.crawl_site')
# def crawl_site(site_id):
#     ranker = PageRankWorker()
#     ranker.crawl_website(site_id)


@app.task(name='tasks.crawl_url_')
def crawl_url_(page):
    ranker = PageRankWorker()
    ranker.crawl_url(page)


# @app.task(name='tasks.po')
# @app.task
# def po(site_id):
#     # p = mp.Pool(4)
#     crawl_site(sites_id)

if __name__ == '__main__':
    # jobs = group(crawl_site.s(_id) for _id in sites_id)
    # jobs = crawl_site.chunks(sites_id, 2)
    st = datetime.now()
    jobs = group(crawl_url_.s(page) for page in pages)
    jobs.apply_async()
    logging.info(datetime.now() - st)
