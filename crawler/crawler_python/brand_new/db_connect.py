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
                SELECT id, url,found_date_time FROM pages WHERE site_id IN ({0}) AND rescan_needed = 1;
                '''.format(ids))
            CONN.commit()

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
                rescan_needed = IF(last_scan_date < VALUES(found_date_time), 1, 0)
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
            rescan_needed = IF(last_scan_date < VALUES(found_date_time), 1, 0)
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
            result = CURSOR.fetchone()
            return result[0] if result else None
        except MySQLError as e:
            print(err(e))

    def count_urls_to_scan(self, one_id):
        try:
            CURSOR.execute('''
                SELECT COUNT(*) FROM pages
                WHERE `site_id`=%s AND rescan_needed=1;
            ''', one_id)
            result = CURSOR.fetchone()
            return result[0] if result else None
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
            result = CURSOR.fetchone()
            return result[0] if result else None
        except MySQLError as e:
            print(err(e))

    def get_site_name(self, site_id):
        try:
            CURSOR.execute('''
                SELECT name FROM sites
                WHERE id=%s;
                ''', site_id)
            result = CURSOR.fetchone()
            return result[0] if result else None
        except MySQLError as e:
            print(err(e))

    def get_all_pages_id_gen(self):
        try:
            CURSOR.execute('''SELECT id, site_id, found_date_time FROM pages;''')
            for id, site_id, found_date_time in CURSOR.fetchall():
                yield id, site_id, found_date_time
        except MySQLError as e:
            print(err(e))

    def get_page_text(self, one_page_id):
        try:
            CURSOR.execute('''SELECT page_body_text FROM pages_content WHERE page_id=%s;''', one_page_id)
            # fetchone returns None when there are no row to fetch
            result = CURSOR.fetchone()   # tuple if there is a text or None
            return result[0] if result else None
        except MySQLError as e:
            print(err(e))

    def mark_page_to_scan(self, page_id):
        try:
            CURSOR.execute('''
            UPDATE pages SET rescan_needed = 1 WHERE id = %s;
            ''', page_id)
            CONN.commit()
        except MySQLError as e:
            print(err(e))

    def get_newly_added_websites_ids(self):
        try:
            CURSOR.execute('''
            SELECT id FROM sites WHERE rescan_needed = 1;
            ''')
            # из базы приходит ((1,), (2,) и т.п.) переводим в обычный список
            # Этот запрос запускается только когда до этого БД показала, что есть сайты с флагом rescan_needed
            # Поэтому обязательно что-нибудь вернётся
            return [x[0] for x in CURSOR.fetchall()]
        except MySQLError as e:
            print(err(e))

    def set_sites_scanned(self, ids):
        if isinstance(ids, int):
            # один айдишник передали
            ids = [ids]
        # Чтобы можно было сюда просто передать список и не париться.
        var_string = ', '.join(['%s' for _ in range(len(ids))])
        # получается строка %s, %s, %s по количеству idшников, вставляем её в запрос
        query_string = 'UPDATE sites SET rescan_needed = 0  WHERE id IN ({});'.format(var_string)
        try:
            # вместо %s подставятся значения идшников
            CURSOR.execute(query_string, ids)
            CONN.commit()
        except MySQLError as e:
            print(err(e))


class CrawlerPersonPageRankConnector:

    def save(self, dict_ranks):
        for page_id, v in dict_ranks.items():
            page_modified_date = v.pop('date-modified', datetime.now())
            page_modified_date = page_modified_date.strftime('%Y-%m-%d %H:%M:%S')
            page_text = v.pop('page-text', 'No text')
            # Если мы пересканируем персон по сохранённых текстам, то не будем обновлять last_scan_date
            rescanned_flag = v.pop('rescanned', None)

            if not rescanned_flag:
                try:
                    # Сохраним текст страницы
                    CURSOR.execute('''
                    INSERT INTO pages_content(page_id, page_body_text)
                    VALUES (%s, %s)
                    ON DUPLICATE KEY
                    UPDATE page_body_text = VALUES (page_body_text);''', (page_id, page_text))
                    # Пометим, что мы её просканировали
                    CURSOR.execute('''UPDATE pages
                                      SET rescan_needed = 0
                                      WHERE id = %s;''', page_id)
                    # Отметим дату последнего сканирования
                    CURSOR.execute('''UPDATE pages SET last_scan_date = CURRENT_TIMESTAMP WHERE `id` = %s;''', page_id)
                except MySQLError as e:
                    print(err(e))

            for person_id, rank in v.items():
                if rank == 0:
                    continue
                # if rank haven't changed don't create a new record in the database
                last_rank_info = self.get_last_rank_(page_id, person_id)
                if last_rank_info:
                    last_rank, last_date = last_rank_info
                    if last_rank == rank:
                        continue
                # Такого рейтинга ещё не было
                try:
                    CURSOR.execute('''INSERT INTO person_page_rank(person_id, page_id, rank, date_modified)
                                      VALUES('{0}', '{1}', '{2}', '{3}')'''.format(person_id,
                                                                                   page_id,
                                                                                   rank,
                                                                                   page_modified_date))
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
            if isinstance(ids, int):
                # один айдишник передали
                ids = [ids]
            # Чтобы можно было сюда просто передать список и не париться.
            var_string = ', '.join(['%s' for _ in range(len(ids))])
            # получается строка %s, %s, %s по количеству idшников, вставляем её в запрос
            query_string = 'SELECT person_id, name FROM keywords WHERE person_id IN ({0});'.format(var_string)
            CURSOR.execute(query_string, ids)
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

    def get_persons_id_for_newly_added_persons_or_keywords(self):
        try:
            # CURSOR.execute('''
            # SELECT id FROM persons WHERE rescan_needed=1;
            # ''')
            # new_persons = [x[0] for x in CURSOR.fetchall()]
            # CURSOR.execute('''
            # SELECT person_id FROM keywords WHERE rescan_needed=1;
            # ''')
            CURSOR.execute('''SELECT P.id
                              FROM ihunters.keywords K
                              INNER JOIN ihunters.persons P ON (K.person_id = P.id)
                              WHERE P.rescan_needed = 1 OR K.rescan_needed = 1''')
            # new_keywords = [x[0] for x in CURSOR.fetchall()]
            new_persons = [x[0] for x in CURSOR.fetchall()]
            # return list(set(new_keywords + new_persons))
            return list(set(new_persons))
        except MySQLError as e:
            print(err(e))
            return []

    def get_unscanned_keywords_for_persons(self, ids):
        if isinstance(ids, int):
            # один айдишник передали
            ids = [ids]
        # Чтобы можно было сюда просто передать список и не париться.
        var_string = ', '.join(['%s' for _ in range(len(ids))])
        query_string = 'SELECT id FROM keywords WHERE rescan_needed = 1 AND person_id IN ({});'.format(var_string)
        try:
            CURSOR.execute(query_string, ids)
            return [x[0] for x in CURSOR.fetchall()]
        except MySQLError as e:
            print(err(e))
            return []

    def set_persons_scanned(self, ids):
        if isinstance(ids, int):
            # один айдишник передали
            ids = [ids]
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
        if isinstance(ids, int):
            # один айдишник передали
            ids = [ids]
        var_string = ', '.join(['%s' for _ in range(len(ids))])
        query_string = 'UPDATE keywords SET rescan_needed = 0  WHERE id IN ({});'.format(var_string)
        try:
            CURSOR.execute(query_string, ids)
            CONN.commit()
        except MySQLError as e:
            print(err(e))

class CrawlerMonitoringConnection:

    def get_stat_info(self):
        try:
            CURSOR.execute('''
            SELECT
            (SELECT COUNT(*) FROM `sites` WHERE `rescan_needed`=1) AS 'new_sites',
            (SELECT COUNT(*) FROM `persons` WHERE `rescan_needed`=1) AS 'new_persons',
            (SELECT COUNT(*) FROM `keywords` WHERE `rescan_needed`=1) AS 'new_keywords',
            (SELECT COUNT(*) FROM `pages` WHERE `rescan_needed`=1) AS 'new_pages'
            ''')
            # !Без этого коммита ниже, когда код в цикле запрашивал результаты этого запроса, постоянно возвращались
            # одни и те-же данные. Т.е. запускаю скрипт, 0 сайтов, 0 персон, 0 слов, 3 страницы возвращается.
            # Дальше скрипт висит в памяти и каждые несколько минут запрашивает эту процедуру.
            # За это время добавилась 1 новая персона и 5 слов, но почему-то при запросе mysql отвечает (0,0,0,3),
            # как в первый раз. Коммит решил эту проблему, запрос теперь честно выполняется каждый раз и
            # изменения отображаются.
            CONN.commit()
            return CURSOR.fetchone()
        except MySQLError as e:
            print(err(e))
