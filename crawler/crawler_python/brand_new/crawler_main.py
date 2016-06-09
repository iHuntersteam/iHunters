from brand_new.crawler_tasks import fetch, get_info_from, save_rank_to_database, WarnerTask
from brand_new.db_connect import CrawlerPersonPageRankConnector, CrawlerSitesConnector, CrawlerPersonsConnector
from celery import chain, group
from brand_new.celery_app import app
import time
from datetime import datetime


def get_page_info(_id, url, date):
    # result = fetch.s(_id, url, date) | get_info_from.s() | save_rank_to_database.s()
    chain(
        fetch.s(_id, url, date),
        get_info_from.s(),
        save_rank_to_database.s()
    ).apply_async()

crawler_sites_conn = CrawlerSitesConnector()
crawler_persons_conn = CrawlerPersonsConnector()
crawler_person_page_rank_conn = CrawlerPersonPageRankConnector()


def data_format(data):
    if type(data) in (int, str):
        return str(data)
    return ','.join(map(str, data))

# grab all persons
person_ids = data_format(crawler_persons_conn.get_persons_ids())
# grab keywords
keywords_dict = crawler_persons_conn.get_person_with_keywords(person_ids)
# grab sites id
site_ids = crawler_sites_conn.get_sites_id()
print(person_ids)
print(keywords_dict)
print(site_ids)


def get_chain(_id, url, date):
    return fetch.s(_id, url, date) | get_info_from.s() | save_rank_to_database.s()


for _id in site_ids:
    website_pages_g = crawler_sites_conn.get_pages_by_site_id_gen(_id)
    # website_pages_g yields a dict {page_id: (page_url, page_found_date)}
    website_rate = crawler_sites_conn.get_site_rate_limit(_id)
    app.control.broadcast('rate_limit',
                          arguments={'task_name': 'brand_new.crawler_tasks.WarnerTask',
                                     'rate_limit': '{}/s'.format(website_rate)})
    # counter = 0
    # my_group = []

    my_ta = WarnerTask()
    for page in website_pages_g:
        # if counter > 10:
        #     print('new_group')
        #     counter = 0
        #     group(my_group)()
        #     my_group.clear()

        for p_id, p_data in page.items():
            # my_group.append(get_chain(p_id, p_data[0], p_data[1]))
            # counter += 1
            my_ta.delay(p_id, p_data[0], p_data[1])

# my_chain = fetch.s(2434, 'http://192.168.1.119/1', datetime.now()) | get_info_from.s() | save_rank_to_database.s()
# res = group([my_chain, my_chain, my_chain])
# res()
