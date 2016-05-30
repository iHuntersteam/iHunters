import pymysql
import logging
from pymysql import MySQLError
from db_settings import HOST, USER, PASSWORD, DBNAME
from collections import defaultdict
import time


CONN = pymysql.connect(host=HOST, user=USER,
                       password=PASSWORD, db=DBNAME,
                       use_unicode=True, charset='utf8',
                       autocommit=True)

CURSOR = CONN.cursor(pymysql.cursors.Cursor)


def err(e):
    logging.basicConfig(
        format=u'%(levelname) -8s [%(asctime)s] %(module)s:%(lineno)s %(funcName)s %(message)s',
        level=logging.DEBUG,
        filename=u'debug.log')
    logging.exception(e)
    return 'Got error {!r}, error is {}'.format(e, e.args[0])


class CrawlerSitesConnector:

    def get(self, ids):
        try:
            CURSOR.execute('''
                SELECT id, url FROM pages WHERE site_id IN ({})
                '''.format(ids))
            return {k: v for k, v in CURSOR.fetchall()}
        except MySQLError as e:
            print(err(e))

    def save(self, url, id, found_time=None):
        try:
            found = found_time.strftime('%Y-%m-%d %H:%M:%S') or time.strftime('%Y-%m-%d %H:%M:%S')

            CURSOR.execute('''
                INSERT INTO pages(url, site_id, found_date_time)
                VALUES(%s, %s, %s)
                ''', (url, id, found))
        except MySQLError as e:
            print(err(e))

    def count_urls(self, one_id):
        try:
            CURSOR.execute('''
                SELECT COUNT(*) FROM pages
                WHERE `site_id` = %s;
            ''', one_id)
            return CURSOR.fetchone()[0]
        except MySQLError as e:
            print(err(e))

    def get_sites_id(self):
        try:
            CURSOR.execute('''
            SELECT id FROM sites;
            ''')
            return list(site_id[0] for site_id in CURSOR.fetchall())
        except MySQLError as e:
            print(err(e))


class CrawlerPersonPageRankConnector:

    def save(self, dict_ranks):
        for page_id, v in dict_ranks.items():
            for person_id, rank in v.items():
                try:
                    CURSOR.execute('''
                        INSERT INTO person_page_rank(person_id, page_id, rank)
                        VALUES({}, {}, {})
                        '''.format(person_id, page_id, rank))

                except MySQLError as e:
                    print(err(e))


class CrawlerPersonsConnector:

    def get(self, ids):
        try:
            CURSOR.execute('''
                SELECT id, name FROM persons WHERE id IN ({})
                '''.format(ids))
            persons = CURSOR.fetchall()
            CURSOR.execute('''
                SELECT person_id, name FROM keywords WHERE person_id IN ({})
            '''.format(ids))
            keywords = list(CURSOR.fetchall())
            keywords.extend(persons)
            persons_dict = defaultdict(list)
            for k, v in keywords:
                persons_dict[k].append(v)
            return dict(persons_dict)
        except MySQLError as e:
            print(err(e))
