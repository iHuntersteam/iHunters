import pymysql
from pymysql import MySQLError
from db_settings import HOST, USER, PASSWORD, DBNAME


class DBconnector:

    db = pymysql.connect(host=HOST, user=USER,
                         password=PASSWORD, db=DBNAME,
                         use_unicode=True, charset='utf8',
                         autocommit=True)

    def __init__(self):
        self.cursor = self.db.cursor(pymysql.cursors.Cursor)

    def err(e):
        return 'Got error {!r}, error is {}'.format(e, e.args[0])


class CrawlerPagesConnector(DBconnector):

    def get(self, page_id):
        try:
            return self.cursor.execute('''
                SELECT * FROM pages WHERE id = %s
            ''', page_id)
        except MySQLError as e:
            print(self.err(e))

    def save(self, page, site):
        try:
            self.cursor.execute('''
                INSERT INTO pages''')
        except MySQLError as e:
            print(self.err(e))


class CrawlerPersonPageRankConnector(DBconnector):

    def save(self, person, page, page_rank):
        try:
            pass
        except MySQLError as e:
            print(self.err(e))


class CrawlerSitesConnector(DBconnector):

    def get(site_id):
        pass


class CrawlerPersonsConnector(DBconnector):

    def get(person_id):
        pass
