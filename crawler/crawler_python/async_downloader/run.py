from async_downloader.downloader_async import fetch, get_info_from, save_rank_to_database
from datetime import datetime
import time
from async_downloader.downloader_async import app
from itertools import islice
from db_connect import CrawlerPersonPageRankConnector, CrawlerSitesConnector
from celery import chain

website_url = 'http://192.168.1.119/'
page_id = 1
page_date_modified = datetime.now()

crawler_person_page_rank_conn = CrawlerPersonPageRankConnector()
crawler_sites_conn = CrawlerSitesConnector()


def split_every(n, iterable):
    i = iter(iterable)
    piece = list(islice(i, n))
    while piece:
        yield piece
        piece = list(islice(i, n))

if __name__ == '__main__':
    w_tasks = []
    start_time = datetime.now()

    def get_page_info(_id, url, date):
        # result = fetch.s(_id, url, date) | get_info_from.s() | save_rank_to_database.s()
        chain(
            fetch.s(_id, url, date),
            get_info_from.s(),
            save_rank_to_database.s()
        ).apply_async()
        # return result()

    pages_from_geektimes = crawler_sites_conn.get_pages_by_site_id_gen(page_id)

    for page in pages_from_geektimes:
        for p_id, p_data in page.items():
            get_page_info(p_id, p_data[0], p_data[1])

    # Выставление лимитов на задачи вот так
    # app.control.broadcast('rate_limit',
    #                       arguments={'task_name': 'async_downloader.downloader_async.fetch',
    #                                  'rate_limit': '200/s'})

