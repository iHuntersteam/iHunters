import pymysql
import logging
from pymysql import MySQLError
from db_settings import HOST, USER, PASSWORD, DBNAME
from collections import defaultdict


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

    def get_pages_by_site_id(self, ids):
        try:
            CURSOR.execute('''
                SELECT id, url FROM pages WHERE site_id IN ({0})
                '''.format(ids))
            return {k: v for k, v in CURSOR.fetchall()}
        except MySQLError as e:
            print(err(e))

    def save(self, url, id):
        try:
            CURSOR.execute('''
                INSERT INTO pages(url, site_id)
                VALUES('{}', {}) ON DUPLICATE KEY UPDATE site_id = site_id
                '''.format(url, id))
        except MySQLError as e:
            print(err(e))


class CrawlerPersonPageRankConnector:

    def save(self, dict_ranks):
        for page_id, v in dict_ranks.items():
            for person_id, rank in v.items():
                try:
                    CURSOR.execute('''
                        INSERT INTO person_page_rank(person_id, page_id, rank)
                        VALUES({0}, {1}, {2}) ON DUPLICATE KEY UPDATE rank = {2},
                        '''.format(person_id, page_id, rank))

                except MySQLError as e:
                    print(err(e))


class CrawlerPersonsConnector:

    def get_person_with_keywords(self, ids):
        try:
            CURSOR.execute('''
                SELECT id, name FROM persons WHERE id IN ({0})
                '''.format(ids))
            persons = CURSOR.fetchall()
            CURSOR.execute('''
                SELECT person_id, name FROM keywords WHERE person_id IN ({0})
            '''.format(ids))
            keywords = list(CURSOR.fetchall())
            keywords.extend(persons)
            persons_dict = defaultdict(list)
            for k, v in keywords:
                persons_dict[k].append(v)
            return dict(persons_dict)
        except MySQLError as e:
            print(err(e))
