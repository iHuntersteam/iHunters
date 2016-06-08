import pymysql
import logging
from pymysql import MySQLError
from db_settings import HOST, PORT, USER, PASSWORD, DBNAME
from collections import defaultdict
from datetime import datetime


CONN = pymysql.connect(host=HOST, user=USER,
                       password=PASSWORD, db=DBNAME,
                       use_unicode=True, charset='utf8',
                       autocommit=False)

CURSOR = CONN.cursor(pymysql.cursors.Cursor)


def err(e):
    logging.basicConfig(
        format=u'%(levelname) -8s [%(asctime)s] %(module)s:%(lineno)s %(funcName)s %(message)s',
        level=logging.DEBUG,
        filename=u'debug.log')
    logging.exception(e)
    return 'Got error {!r}, error is {}'.format(e, e.args[0])


class CrawlerHandlerConnector:

    def update_last_scan_pages():
        try:
            CURSOR.execute('''
                UPDATE handler
                SET last_scan_pages = create_upd_date_pages
                WHERE handler.id = 1
            ''')
            CONN.commit()
        except MySQLError as e:
            print(err(e))

    def update_last_scan_pers_keys():
        try:
            CURSOR.execute('''
                UPDATE handler
                SET last_scan_pers_keys = create_upd_date_pers_keys
                WHERE handler.id = 1
            ''')
            CONN.commit()
        except MySQLError as e:
            print(err(e))

    def check_for_scan(last_scan_field, create_upd_date_field):
        try:
            CURSOR.execute('''
                SELECT IF((SELECT {0} FROM handler WHERE handler.id=1) 
                !=
                (SELECT {1} FROM handler WHERE handler.id=1), 1, 0)
            '''.format(last_scan_field, create_upd_date_field))
            return CURSOR.fetchone()[0]
        except MySQLError as e:
            print(err(e))


class CrawlerSitesConnector:

    def get_pages_by_site_id(self, ids):
        try:
            CURSOR.execute('''
                SELECT id, url,found_date_time FROM pages WHERE site_id IN ({0}) AND last_scan_date IS NULL
                '''.format(ids))
            return {k: (v, d) for k, v, d in CURSOR.fetchall()}
        except MySQLError as e:
            print(err(e))

    def __query_for_last_scan_pages(self, val):
        return '''
                SELECT {0} 
                FROM pages 
                WHERE pages.create_upd_date > (
                    SELECT handler.last_scan_pages 
                    FROM handler
                    WHERE handler.id = 1)
                AND pages.create_upd_date <= (
                    SELECT handler.create_upd_date_pages
                    FROM handler
                    WHERE handler.id = 1
                    )
            '''.format(val)

    def need_scan(self):
        return CrawlerHandlerConnector.check_for_scan(
            'last_scan_pages', 'create_upd_date_pages')

    def get_create_upd_date_pages(self):
        CURSOR.execute('''
                SELECT create_upd_date_pages
                FROM handler
                WHERE id = 1
            ''')
        return CURSOR.fetchone()

    def get_not_scan_pages_gen(self):
        try:
            CURSOR.execute(self.__query_for_last_scan_pages(
                'id, url, found_date_time'))
            return {k: (v, d) for k, v, d in CURSOR.fetchall()}
        except MySQLError as e:
            print(err(e))

    # def get_pages_by_site_id_gen(self, ids):
    #     try:
    #         CURSOR.execute('''
    #             SELECT id, url,found_date_time FROM pages WHERE site_id IN ({0}) AND last_scan_date IS NULL
    #             '''.format(ids))
    #         for k, v, d in CURSOR.fetchall():
    #             yield {k: (v, d)}
    #         # return {k: (v, d) for k, v, d in CURSOR.fetchall()}
    #     except MySQLError as e:
    #         print(err(e))

    def save(self, url, id, found_time=None):
        try:
            found_time = found_time or datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            CURSOR.execute('''
                INSERT INTO pages(url, site_id, found_date_time)
                VALUES(%s, %s, %s)
                ''', (url, id, found_time))
        except MySQLError as e:
            print(err(e))
        CONN.commit()

    def save_stack(self, data):
        try:
            CURSOR.executemany('''
            INSERT INTO pages (url, site_id, found_date_time) VALUES (%s, %s, %s)
            ON DUPLICATE KEY UPDATE
            last_scan_date = IF(last_scan_date < VALUES(found_date_time), null, last_scan_date)
            ''', data)
        except MySQLError as e:
            print(err(e))
        CONN.commit()

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
            return tuple(site_id[0] for site_id in CURSOR.fetchall())
        except MySQLError as e:
            print(err(e))

    def get_page_info(self, site_id):
        try:
            CURSOR.execute('''
            SELECT site_id, url FROM pages WHERE site_id = %s
            ''', site_id)
            return CURSOR.fetchone()
        except MySQLError as e:
            print(err(e))


class CrawlerPersonPageRankConnector:

    def save(self, dict_ranks):
        for page_id, v in dict_ranks.items():
            page_modified_date = v.pop('date_modified', datetime.now())
            page_modified_date = page_modified_date.strftime(
                '%Y-%m-%d %H:%M:%S')
            for person_id, rank in v.items():
                if rank == 0:
                    try:
                        CURSOR.execute('''
                        UPDATE pages
                        SET last_scan_date = CURRENT_TIMESTAMP WHERE `id` = %s;
                        ''', page_id)
                    except MySQLError as e:
                        print(err(e))
                    continue
                # if rank haven't changed don't create a new record in the
                # database
                last_rank_info = self.get_last_rank_(page_id, person_id)
                if len(last_rank_info) == 2:
                    last_rank, last_date = last_rank_info
                    if last_rank == rank:
                        continue
                try:
                    CURSOR.execute('''
                        INSERT INTO person_page_rank(person_id, page_id, rank, date_modified)
                        VALUES('{0}', '{1}', '{2}', '{3}')
                        '''.format(person_id, page_id, rank, page_modified_date))
                except MySQLError as e:
                    print(err(e))
            # after inserting all items - commiting transaction
            CONN.commit()

    def get_last_rank_(self, page_id, person_id):
        try:
            CURSOR.execute('''
                SELECT rank, date_modified FROM person_page_rank
                WHERE page_id = '{0}' AND person_id = '{1}' AND date_modified = (SELECT MAX(date_modified)
                FROM person_page_rank
                WHERE page_id = '{0}' and person_id = '{1}')
                '''.format(page_id, person_id))
            return CURSOR.fetchall()

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

    def get_persons_ids(self):
        try:
            CURSOR.execute('''
            SELECT id FROM persons
            ''')
            return tuple(person_id[0] for person_id in CURSOR.fetchall())
        except MySQLError as e:
            print(err(e))

    ## TODO in progress
    def query_for_last_scan_persons(self, val):
        return '''
                SELECT {0} 
                FROM persons 
                WHERE persons.create_upd_date > (
                    SELECT handler.last_scan_pers_keys 
                    FROM handler
                    WHERE handler.id = 1)
                AND persons.create_upd_date <= (
                    SELECT handler.create_upd_date_pers_keys
                    FROM handler
                    WHERE handler.id = 1
                    )
            '''.format(val)

    def need_scan(self):
        return CrawlerHandlerConnector.check_for_scan(
            'last_scan_pers_keys', 'create_upd_date_pers_keys')

    ## TODO in progress
    def get_not_scan_pages_gen(self):
        try:
            CURSOR.execute(self.query_for_last_scan_persons(
                'MAX(create_upd_date)'))
            max_create_update = CURSOR.fetchone()
            CURSOR.execute(self.query_for_last_scan_persons(
                'id, name'))
            persons = CURSOR.fetchall()

            print(pages)
            # date update only on commit
            CrawlerHandlerConnector.update_last_scan_pages(
                max_create_update)

            return {k: (v, d) for k, v, d in pages}
        except MySQLError as e:
            print(err(e))
