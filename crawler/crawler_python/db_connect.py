import pymysql
import logging
from pymysql import MySQLError
from db_settings import HOST, PORT, USER, PASSWORD, DBNAME
from collections import defaultdict
from datetime import datetime


CONN = pymysql.connect(host=HOST, port=PORT, user=USER,
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


class CrawlerSitesConnector:

    def get_pages_by_site_id(self, ids):
        try:
            CURSOR.execute('''
                SELECT id, url,found_date_time FROM pages WHERE site_id IN ({0}) AND last_scan_date IS NULL
                '''.format(ids))
            return {k: (v, d) for k, v, d in CURSOR.fetchall()}
        except MySQLError as e:
            print(err(e))

    def get_all_pages_gen(self):
        try:
            CURSOR.execute('''
                SELECT id, url, found_date_time
                FROM pages
            ''')
            for k, v, d in CURSOR.fetchall():
                yield {k: (v, d)}
        except MySQLError as e:
            print(err(e))

    def __query_for_rescan_needed_pages(self):
        return '''
                SELECT id, url, found_date_time
                FROM pages
                WHERE pages.rescan_needed = 1
            '''

    def need_scan(self):
        CURSOR.execute('''
            SELECT COUNT(id) FROM pages WHERE rescan_needed = 1
            ''')
        return CURSOR.fetchone()

    def get_create_upd_date_pages(self):
        CURSOR.execute('''
                SELECT create_upd_date_pages
                FROM handler
                WHERE id = 1
            ''')
        return CURSOR.fetchone()

    def get_not_scan_pages_gen(self):
        try:
            CURSOR.execute(self.__query_for_rescan_needed_pages())
            for k, v, d in CURSOR.fetchall():
                yield {k: (v, d)}
        except MySQLError as e:
            print(err(e))

    def get_pages_by_site_id_gen(self, ids):
        try:
            CURSOR.execute('''
                SELECT id, url,found_date_time FROM pages WHERE site_id IN ({0}) AND last_scan_date IS NULL
                '''.format(ids))
            for k, v, d in CURSOR.fetchall():
                yield {k: (v, d)}
            # return {k: (v, d) for k, v, d in CURSOR.fetchall()}
        except MySQLError as e:
            print(err(e))

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
            page_modified_date = v.pop('date-modified', datetime.now())
            page_modified_date = page_modified_date.strftime('%Y-%m-%d %H:%M:%S')
            page_text = v.pop('page-text', 'No text')
            for person_id, rank in v.items():
                if rank == 0:
                    try:
                        CURSOR.execute('''
                        UPDATE pages
                        SET last_scan_date = CURRENT_TIMESTAMP WHERE `id` = %s;
                        ''', page_id)
                        CURSOR.execute('''INSERT INTO pages_content(page_id, page_body_text)
                            VALUES (%s, %s)
                            ON DUPLICATE KEY UPDATE
                            page_body_text = VALUES (page_body_text)
                            ''', (page_id, page_text))
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
                    CURSOR.execute('''INSERT INTO pages_content(page_id, page_body_text)
                        VALUES (%s, %s)
                        ON DUPLICATE KEY UPDATE
                        page_body_text = VALUES (page_body_text)
                        ''', (page_id, page_text))
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
            return CURSOR.fetchone()

        except MySQLError as e:
            print(err(e))


class CrawlerPersonsConnector:

    def get_person_with_keywords(self, ids):
        try:
            CURSOR.execute('''
                SELECT person_id, name FROM keywords WHERE person_id IN ({0})
            '''.format(ids))
            keywords = list(CURSOR.fetchall())
            persons_dict = defaultdict(list)
            for k, v in keywords:
                persons_dict[k].append(v)
            return dict(persons_dict)

        except MySQLError as e:
            print(err(e))

    def get_all_persons_with_keywords(self):
        try:
            CURSOR.execute('''
            SELECT person_id, name FROM keywords
            ''')
            keywords = list(CURSOR.fetchall())
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

    def __query_for_rescan_needed_keywords(self):
        return '''
                SELECT person_id, name
                FROM keywords
                WHERE keywords.rescan_needed = 1
            '''

    def need_scan(self):
        CURSOR.execute('''
            SELECT COUNT(id) FROM keywords WHERE rescan_needed = 1
            ''')
        return CURSOR.fetchone()

    def get_not_scan_pers(self):
        try:
            CURSOR.execute(self.__query_for_rescan_needed_keywords())
            keywords = list(CURSOR.fetchall())
            persons_dict = defaultdict(list)
            for k, v in keywords:
                persons_dict[k].append(v)
            return dict(persons_dict)

        except MySQLError as e:
            print(err(e))
