import pymysql
import logging
from pymysql import MySQLError
from db_settings import HOST, USER, PASSWORD, DBNAME
from itertools import chain


DB = pymysql.connect(host=HOST, user=USER,
                     password=PASSWORD, db=DBNAME,
                     use_unicode=True, charset='utf8',
                     autocommit=True)

CURSOR = DB.cursor(pymysql.cursors.Cursor)


def err(e):
    logging.basicConfig(
        format=u'%(levelname) -8s [%(asctime)s] %(module)s:%(lineno)s %(funcName)s %(message)s',
        level=logging.DEBUG,
        filename=u'debug.log')
    logging.exception(e)
    return 'Got error {!r}, error is {}'.format(e, e.args[0])


class CrawlerPagesConnector:

    def get(self, id):
        try:
            CURSOR.execute('''
                SELECT * FROM pages WHERE id = %s
                ''', id)
            return CURSOR.fetchone()
        except MySQLError as e:
            print(err(e))

    def save(self, url, site_id):
        try:
            CURSOR.execute('''
                INSERT INTO pages(url, site_id)
                VALUES(%s, %s)
                ''', (url, site_id))
        except MySQLError as e:
            print(err(e))

    def update(self, id):
        try:
            CURSOR.execute('''
                UPDATE pages SET last_scan_date=NOW()
                WHERE id=%s
                ''', (id))
        except MySQLError as e:
            print(err(e))


class CrawlerPersonPageRankConnector:

    def save(self, person_id, page_id, rank):
        try:
            CURSOR.execute('''
                INSERT INTO person_page_rank(person_id, page_id, rank)
                VALUES(%s, %s, %s)
                ''', (person_id, page_id, rank))
        except MySQLError as e:
            print(err(e))


class CrawlerSitesConnector:

    def get(self, id):
        try:
            CURSOR.execute('''
                SELECT * FROM sites WHERE id = %s
                ''', id)
            return CURSOR.fetchone()
        except MySQLError as e:
            print(err(e))


class CrawlerPersonsConnector:

    def get(self, id):
        try:
            CURSOR.execute('''
                SELECT * FROM persons WHERE id = %s
                ''', id)
            person = CURSOR.fetchone()
            CURSOR.execute('''
                SELECT name FROM keywords WHERE person_id = %s
            ''', person[0])
            keywords = list(chain(*CURSOR.fetchall()))
            keywords.extend([person[1]])
            return keywords
        except MySQLError as e:
            print(err(e))
