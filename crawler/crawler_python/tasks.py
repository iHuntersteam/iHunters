import multiprocessing as mp
from db_connect import CrawlerSitesConnector
from rank_worker import PageRankWorker
from celery import Celery
from celery.signals import worker_process_init
from celery.schedules import crontab
from multiprocessing import current_process



# fix AttributeError: 'Worker' object has no attribute '_config'
@worker_process_init.connect
def fix_multiprocessing(**kwargs):
    try:
        current_process()._config
    except AttributeError:
        current_process()._config = {'semprefix': '/mp'}

# in terminal run: celery -A tasks worker -B --loglevel=info
app = Celery('tasks')
app.conf.update(
    BROKER_URL='redis://localhost:6379/0',
    CELERY_TASK_SERIALIZER='json',
    CELERY_ACCEPT_CONTENT=['json'],  # Ignore other content
    # CELERY_RESULT_SERIALIZER='json',
    # CELERY_TIMEZONE='Europe/Oslo',
    # CELERY_ENABLE_UTC=True,

    CELERYBEAT_SCHEDULE={
        'po': {
            'task': 'tasks.po',
            'schedule': crontab(minute=0, hour=23),
        },
    }
)


def crawl_site(site_id):
    ranker = PageRankWorker()
    ranker.crawl_website(site_id)


@app.task(name='tasks.po')
def po():
    sites_connector = CrawlerSitesConnector()
    sites_id = sites_connector.get_sites_id()
    p = mp.Pool(4)
    p.map(crawl_site, sites_id)

