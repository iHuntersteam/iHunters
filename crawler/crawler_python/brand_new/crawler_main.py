from brand_new.crawler_tasks import WebCrawlerTask
from brand_new.db_connect import (CrawlerPersonPageRankConnector, CrawlerSitesConnector, CrawlerPersonsConnector,
                                  CrawlerMonitoringConnection)
from celery import chain, group
from brand_new.celery_app import app
import time
from datetime import datetime
from brand_new.sitemap_worker import SitemapWorker
from brand_new.utils import rank_page, create_search_patterns


# def get_page_info(_id, url, date):
#     # result = fetch.s(_id, url, date) | get_info_from.s() | save_rank_to_database.s()
#     chain(
#         fetch.s(_id, url, date),
#         get_info_from.s(),
#         save_rank_to_database.s()
#     ).apply_async()

# crawler_sites_conn = CrawlerSitesConnector()
# crawler_persons_conn = CrawlerPersonsConnector()
# crawler_person_page_rank_conn = CrawlerPersonPageRankConnector()


# def data_format(data):
#     if type(data) in (int, str):
#         return str(data)
#     return ','.join(map(str, data))

# grab all persons
# person_ids = data_format(crawler_persons_conn.get_persons_ids())
# grab keywords
# keywords_dict = crawler_persons_conn.get_person_with_keywords(person_ids)
# grab sites id
# site_ids = crawler_sites_conn.get_sites_id()
# print(person_ids)
# print(keywords_dict)
# print(site_ids)


# def get_chain(_id, url, date):
#     return fetch.s(_id, url, date) | get_info_from.s() | save_rank_to_database.s()


# for _id in site_ids:
#     website_pages_g = crawler_sites_conn.get_pages_by_site_id_gen(_id)
#     website_pages_g yields a dict {page_id: (page_url, page_found_date)}
    # website_rate = crawler_sites_conn.get_site_rate_limit(_id)
    # app.control.broadcast('rate_limit',
    #                       arguments={'task_name': 'brand_new.crawler_tasks.WebCrawlerTask',
    #                                  'rate_limit': '{}/s'.format(website_rate)})

    # my_task = WebCrawlerTask()
    # for page in website_pages_g:
    #     for p_id, p_data in page.items():
    #         my_task.delay(p_id, p_data[0], p_data[1])


# 1. Новый сайт
    # 1.1. Запускаем сканер сайтмапов
    # 1.2. Запускаем обход страниц со всеми персонами
# 2. Новая персона, ключевое слово
    # Пересканируем существующее - проверяем по таблице с текстом страниц
# 3. Пересканирование сайтмапов
#     Запускаем сканер сайтмапов, новые страницы отмечаем
#     Запускаем п.4
# 4. Пересканирование страниц
#     Запускаем пересканирование со всеми персонами

class CrawlerWorker:
    def __init__(self):
        self.sitemap_worker = SitemapWorker()
        self.crawler_sites_conn = CrawlerSitesConnector()
        self.crawler_persons_conn = CrawlerPersonsConnector()
        self.crawler_person_page_rank_conn = CrawlerPersonPageRankConnector()
        self.monitoring = CrawlerMonitoringConnection()

    def rescan_sitemap(self, website_id):
        self.sitemap_worker.parse_and_save_links_from_sitemap(website_id)

    def rescan_all_sitemaps(self):
        self.sitemap_worker.crawl_all_sitemaps()

    def rescan_few_sitemaps(self, many_ids):
        for _id in many_ids:
            self.rescan_sitemap(_id)

    def rescan_new_persons(self, person_ids):
        """
        :param person_ids: tuple or list or int
        :return:
        """
        if isinstance(person_ids, int):
            # один айдишник передали
            person_ids = [person_ids]
        # [Персона 1, персона2]
        persons_dict = self.crawler_persons_conn.get_person_with_keywords(person_ids)
        # нужно сделать словарь регулярок для поиска
        my_search_patterns = create_search_patterns(persons_dict)
        # Теперь получаем список всех страниц из базы.
        pages_list_generator = self.crawler_sites_conn.get_all_pages_id_gen()
        page_ranks = {}
        for page_id, site_id, found_date_time in pages_list_generator:
            # проверяем есть ли в таблице сохранённый текст
            page_text = self.crawler_sites_conn.get_page_text(page_id)
            if not page_text:
                print('Страницы с id: {} нет в базе, отмечаю её для скачивания'.format(page_id))
                self.crawler_sites_conn.mark_page_to_scan(page_id)
                continue

            # Мы уже сканировали страницу и у нас есть текст
            # Нужно вычислить
            rank_info = rank_page(my_search_patterns, page_text)
            rank_info['date-modified'] = found_date_time
            rank_info['page-text'] = page_text
            rank_info['rescanned'] = 1
            page_ranks[page_id] = rank_info

            # Через каждые 10 страниц - сохраняемся
            if len(page_ranks) >= 10:
                self.crawler_person_page_rank_conn.save(page_ranks)
                page_ranks.clear()
        # Просканировали все страницы, отмечаем персоны просканированными
        self.crawler_persons_conn.set_persons_scanned(person_ids)
        # Отмечаем и все ключевые слова просканированными
        self.crawler_persons_conn.set_keywords_scanned_for_person_id(person_ids)
        # Мы просканировали те страницы, которые есть в базе, а те, которых нет - поставили флаг,
        #  что нужно их пересканировать
        # Запускаем пересканирование
        print('Пересканирование сохранённых страниц завершено. Запускаю краулер для докачки других.')
        self.start_pages_crawling()

    def start_pages_crawling(self):
        """
        Сканирует все страницы, которые лежат в базе с флагом rescan_needed
        :return:
        """
        # Сначала нужно проверить, возможно какие-то задачи уже исполняются в данный момент, поэтому подождём их
        # завершения, чтобы не возникло бесконечных очередей
        print('Запущен процесс обхода страниц.')
        inspection_helper = app.control.inspect()

        while True:
            for key, item in inspection_helper.active().items():
                active_count = len(item)
            for key, item in inspection_helper.reserved().items():
                reserved_count = len(item)
            if active_count + reserved_count == 0:
                break
            else:
                # Сейчас идёт какая-то работа. Подождём 2 минуты
                print('Работа кипит')
                time.sleep(5)
        # Очередь свободна, создаём задание
        my_task = WebCrawlerTask()
        # Получаем всех персон
        person_ids = self.crawler_persons_conn.get_persons_ids()
        # Получаем все ключевые слова
        keywords_dict = self.crawler_persons_conn.get_person_with_keywords(person_ids)
        # Получаем все id сайтов
        site_ids = self.crawler_sites_conn.get_sites_id()
        for _id in site_ids:
            # генератор, который будет выдавать страницы с флаго rescan_needed - {page_id: (page_url, page_found_date)}
            website_pages_g = self.crawler_sites_conn.get_pages_by_site_id_gen(_id)
            website_rate = self.crawler_sites_conn.get_site_rate_limit(_id)
            # Устанавливаем количество заданий в секунду для текущего сайта
            app.control.broadcast('rate_limit',
                                  arguments={'task_name': 'brand_new.crawler_tasks.WebCrawlerTask',
                                             'rate_limit': '{}/s'.format(website_rate)})

            for page in website_pages_g:
                for p_id, p_data in page.items():
                    my_task.delay(p_id, p_data[0], p_data[1])
        # Все задания закинуты в selery

    def update_database(self):
        self.rescan_all_sitemaps()
        self.start_pages_crawling()

    def check_new_items(self):
        # Проверяем флаг в графе sites
        # Проверяем флаг в графе persons
        # Проверяем флаг в графе keywords
        # Проверяем флаг в pages
        new_sites, new_persons, new_keywords, new_pages = self.monitoring.get_stat_info()

        print('New sites: {}. New Persons: {}. New Keywords: {}. New Pages: {}'.format(new_sites,
                                                                                       new_persons,
                                                                                       new_keywords,
                                                                                       new_pages))
        if new_sites > 0:
            new_ids = self.crawler_sites_conn.get_newly_added_websites_ids()
            print('Новые сайты добавлены с id={}'.format(new_ids))
            print('Запускаю сканирование sitemap.')
            self.rescan_few_sitemaps(new_ids)
            print('Запускаю сканирование страниц.')
            self.start_pages_crawling()
            self.crawler_sites_conn.set_sites_scanned(new_ids)

        if new_persons > 0 or new_keywords > 0:
            print('Обнаружены новые персоны или ключевые слова')
            new_ids = self.crawler_persons_conn.get_persons_id_for_newly_added_persons_or_keywords()
            self.rescan_new_persons(new_ids)

        if new_pages > 0:
            print('Обнаружены непросканированные страницы.')
            self.start_pages_crawling()


        # Если новый сайт:
        #     Запускаем сканирование сайтмапов rescan_all_sitemaps
        #     Запускаем обход всех страниц update_database
        # Если новая персона или ключевое слово
        #     Запускаем обновление для этой персоны rescan_new_persons
        # Новые страницы - запускаем сканирование update_database

        return


if __name__ == '__main__':
    test = CrawlerWorker()
    while True:
        print('Новая проверка')
        time.sleep(60)
        test.check_new_items()

