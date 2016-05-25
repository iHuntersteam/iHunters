import pymysql
import logging
from pymysql import MySQLError
from db_settings import HOST, USER, PASSWORD, DBNAME


class DBconnector:

    db = pymysql.connect(host=HOST, user=USER,
                         password=PASSWORD, db=DBNAME,
                         use_unicode=True, charset='utf8',
                         autocommit=True)

    def __init__(self):
        self.cursor = self.db.cursor(pymysql.cursors.Cursor)

    def err(self, e):
        logging.basicConfig(
            format=u'%(levelname) -8s [%(asctime)s] %(module)s:%(lineno)s %(funcName)s %(message)s',
            level=logging.DEBUG,
            filename=u'debug.log')
        logging.exception(e)
        # raise
        return 'Got error {!r}, error is {}'.format(e, e.args[0])


class CrawlerPagesConnector(DBconnector):

    def get(self, id):
        try:
            return self.cursor.execute('''
                SELECT * FROM pages WHERE id = %s
            ''', id)
        except MySQLError as e:
            print(self.err(e))

    def save(self, url, site_id):
        try:
            self.cursor.execute('''
                INSERT INTO pages(url, site_id)
                VALUES(%s, %s)''', (url, site_id))
        except MySQLError as e:
            print(self.err(e))

    def update(self, id, url=None, site_id=None):
        pass


class CrawlerPersonPageRankConnector(DBconnector):

    def save(self, person, page, page_rank):
        try:
            pass
        except MySQLError as e:
            print(self.err(e))


class CrawlerSitesConnector(DBconnector):

    def get(self, id):
        try:
            pass
        except MySQLError as e:
            print(self.err(e))


class CrawlerPersonsConnector(DBconnector):

    def get(self, id):
        try:
            pass
        except MySQLError as e:
            print(self.err(e))
