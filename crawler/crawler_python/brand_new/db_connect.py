from collections import defaultdict
from datetime import datetime
import logging

import pymysql
from pymysql import MySQLError

from brand_new.db_settings import (MYSQL_DBNAME, MYSQL_HOST, MYSQL_PASSWORD, MYSQL_PORT, MYSQL_USER)


CONN = pymysql.connect(host=MYSQL_HOST, port=MYSQL_PORT,
                       user=MYSQL_USER, password=MYSQL_PASSWORD,
                       db=MYSQL_DBNAME, use_unicode=True,
                       charset='utf8', autocommit=False)
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
                SELECT id, url,found_date_time FROM pages WHERE site_id IN ({0}) AND rescan_needed = 1
                '''.format(ids))
            return {k: (v, d) for k, v, d in CURSOR.fetchall()}
        except MySQLError as e:
            print(err(e))

    def get_pages_by_site_id_gen(self, ids):
        try:
            CURSOR.execute('''
                SELECT id, url,found_date_time FROM pages WHERE site_id IN ({0}) AND rescan_needed = 1
                '''.format(ids))
            for k, v, d in CURSOR.fetchall():
                yield {k: (v, d)}
        except MySQLError as e:
            print(err(e))

    def save(self, url, id, found_time=None):
        try:
            found_time = found_time or datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            CURSOR.execute('''
                INSERT INTO pages(url, site_id, found_date_time, rescan_needed)
                VALUES (%s, %s, %s, 1)
                ON DUPLICATE KEY UPDATE
                rescan_needed = IF(last_scan_date < VALUES(found_date_time), 0, 1)
                ''', (url, id, found_time))
        except MySQLError as e:
            print(err(e))
        CONN.commit()

    def save_stack(self, data):
        try:
            CURSOR.executemany('''
            INSERT INTO pages(url, site_id, found_date_time, rescan_needed)
            VALUES (%s, %s, %s, 1)
            ON DUPLICATE KEY UPDATE
            rescan_needed = IF(last_scan_date < VALUES(found_date_time), 0, 1)
            ''', data)
        except MySQLError as e:
            print(err(e))
        CONN.commit()

    def count_urls(self, one_id):
        try:
            CURSOR.execute('''
                SELECT COUNT(*) FROM pages
                WHERE `site_id`=%s;
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

    def get_site_rate_limit(self, site_id):
        try:
            CURSOR.execute('''
            SELECT rate_limit FROM sites
            WHERE id=%s;
            ''', site_id)
            return CURSOR.fetchone()[0]
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
                        # CURSOR.execute('''UPDATE pages
                        #                   SET last_scan_date = CURRENT_TIMESTAMP
                        #                   WHERE `id` = %s;''', page_id)
                        # CURSOR.execute('''INSERT INTO pages_content(page_id, page_body_text)
                        #                   VALUES (%s, %s)
                        #                   ON DUPLICATE KEY
                        #                   UPDATE page_body_text = VALUES (page_body_text)''', (page_id, page_text))
                        # CURSOR.execute('''UPDATE pages
                        #                   SET rescan_needed = 0
                        #                   WHERE id = %s;''', page_id)
                        CURSOR.execute('''UPDATE pages SET last_scan_date = CURRENT_TIMESTAMP WHERE `id` = %s;

                                          INSERT INTO pages_content(page_id, page_body_text)
                                          VALUES (%s, %s)
                                          ON DUPLICATE KEY
                                          UPDATE page_body_text = VALUES (page_body_text);

                                          UPDATE pages
                                          SET rescan_needed = 0
                                          WHERE id = %s;''', (page_id, page_id, page_text, page_id))

                    except MySQLError as e:
                        print(err(e))
                    continue
                # if rank haven't changed don't create a new record in the database
                last_rank_info = self.get_last_rank_(page_id, person_id)
                if last_rank_info:
                    last_rank, last_date = last_rank_info
                    if last_rank == rank:
                        continue
                try:
                    CURSOR.execute('''INSERT INTO person_page_rank(person_id, page_id, rank, date_modified)
                                      VALUES('{0}', '{1}', '{2}', '{3}')'''.format(person_id,
                                                                                   page_id,
                                                                                   rank,
                                                                                   page_modified_date))
                    CURSOR.execute('''INSERT INTO pages_content(page_id, page_body_text)
                                      VALUES (%s, %s)
                                      ON DUPLICATE KEY
                                      UPDATE page_body_text = VALUES (page_body_text)''', (page_id, page_text))
                    CURSOR.execute('''UPDATE pages
                                      SET rescan_needed = 0
                                      WHERE id = %s;''', page_id)
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

    def set_persons_scanned(self, ids):
        # Чтобы можно было сюда просто передать список и не париться.
        var_string = ', '.join(['%s' for _ in range(len(ids))])
        # получается строка %s, %s, %s по количеству idшников, вставляем её в запрос
        query_string = 'UPDATE persons SET rescan_needed = 0  WHERE id IN ({});'.format(var_string)
        try:
            # вместо %s подставятся значения идшников
            CURSOR.execute(query_string, ids)
            CONN.commit()
        except MySQLError as e:
            print(err(e))

    def set_keywords_scanned(self, ids):
        var_string = ', '.join(['%s' for _ in range(len(ids))])
        query_string = 'UPDATE keywords SET rescan_needed = 0  WHERE id IN ({});'.format(var_string)
        try:
            CURSOR.execute(query_string, ids)
            CONN.commit()
        except MySQLError as e:
            print(err(e))
