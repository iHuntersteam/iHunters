import pymysql
import logging
from pymysql import MySQLError
from db_settings import HOST, USER, PASSWORD, DBNAME
from collections import defaultdict


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


class CrawlerSitesConnector:

    def get(self, *ids):
        format_strings = ','.join(['%s'] * len(ids))
        try:
            CURSOR.execute('''
                SELECT id, url FROM pages WHERE site_id IN (%s)
                ''' % format_strings, ids)
            return {k: v for k, v in CURSOR.fetchall()}
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


class CrawlerPersonsConnector:

    def get(self, *ids):
        try:
            format_strings = ','.join(['%s'] * len(ids))
            CURSOR.execute('''
                SELECT id, name FROM persons WHERE id IN (%s)
                ''' % format_strings, ids)
            persons = CURSOR.fetchall()
            CURSOR.execute('''
                SELECT person_id, name FROM keywords WHERE person_id IN (%s)
            ''' % format_strings, ids)
            keywords = list(CURSOR.fetchall())
            keywords.extend(persons)
            persons_dict = defaultdict(list)
            for k, v in keywords:
                persons_dict[k].append(v)
            return dict(persons_dict)
        except MySQLError as e:
            print(err(e))
