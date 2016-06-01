# SQL команды для прогона в файле test_data.sql
# После запуска этого файла добавляются 2258 ссылок с гикбрэйнса.

from urllib import parse
from datetime import datetime

from parse import SitemapParser, RobotsParser
from db_connect import CrawlerSitesConnector

crawler_sites_conn = CrawlerSitesConnector()
sitemap_reader = SitemapParser()
robotstxt_reader = RobotsParser()

start_time = datetime.now()
# Получаем список id сайтов, которые есть в базе.
sites = crawler_sites_conn.get_sites_id()

# Для каждого сайта получаем количество записей в таблице pages.
# Если запись только одна, значит это новый сайт, и нужно обработаеть его sitemap
for site_id in sites:
    start_time = datetime.now()
    print('Проверяем сайт с ID = {}'.format(site_id))
    page_count = crawler_sites_conn.count_urls(site_id)
    print('Найдено ссылок в базе с этого сайта: {}'.format(page_count))
    print('------')
    if page_count == 1:
        # Ссылку на сайтмап возьмём из robots.txt
        # Если её там нет, попробуем обработать url/sitemap.xml
        site_url, site_date = list(crawler_sites_conn.get_pages_by_site_id(site_id).values())[0]
        # site_url, site_date = 'http://lenta.ru', '11'
        # потому что метод get возвращает словарь {id: url} а городить новый метод для
        # получения одного url'а это как-то глупо.
        print('Всего одна ссылка в базе. Судя по всему это новый сайт. Его URL = {}'.format(site_url))
        sitemap_links = robotstxt_reader.get_sitemap_links(site_url)
        if not sitemap_links:
            parsed = parse.urlparse(site_url)
            sitemap_links = ['{s.scheme}://{s.netloc}/sitemap.xml'.format(s=parsed)]

        for sitemap_link in sitemap_links:
            print('Обрабатываем Sitemap по адресу: {}'.format(sitemap_link))
            urls_generator = sitemap_reader.get_parsed_urls(sitemap_link)
            url_counter = 0
            insert_many = []
            split_counter = 0
            for url, found_date in urls_generator:
                found_date = found_date or datetime.now()
                found_date = found_date.strftime('%Y-%m-%d %H:%M:%S')
                insert_many.append((url, site_id, found_date))
                split_counter += 1
                if split_counter > 2000:
                    crawler_sites_conn.save_stack(insert_many)
                    split_counter = 0
                    insert_many.clear()
                    print('\rОбработано: {}.'.format(url_counter))
                url_counter += 1
            crawler_sites_conn.save_stack(insert_many)
            print('Готово. Обработано ссылок: {}'.format(url_counter))
print('------------------ Общее время -------------')
t = datetime.now() - start_time
print(t)
print('------------------ Запросов в секунду -------------')
print(url_counter / t.seconds)
