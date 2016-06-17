from itertools import islice
from crawler_tasks import WebCrawlerTask, WebCrawler1PerSec, WebCrawler3PerSec, WebCrawler5PerSec
from db_connect import (CrawlerPersonPageRankConnector, CrawlerSitesConnector, CrawlerPersonsConnector,
                                  CrawlerMonitoringConnection)
from celery import chain, group
from celery_app import app
import time
from datetime import datetime
from sitemap_worker import SitemapWorker
from utils import rank_page, create_search_patterns
from robotstxt import RobotsFactory


class CrawlerWorker:
    def __init__(self):
        self.sitemap_worker = SitemapWorker()
        self.crawler_sites_conn = CrawlerSitesConnector()
        self.crawler_persons_conn = CrawlerPersonsConnector()
        self.crawler_person_page_rank_conn = CrawlerPersonPageRankConnector()
        self.monitoring = CrawlerMonitoringConnection()

    def rescan_sitemap(self, website_id):
        website_pages = self.crawler_sites_conn.count_urls(website_id)
        if website_pages == 0:
            website_url = self.crawler_sites_conn.get_site_name(website_id)
        else:
            website_url = None
        self.sitemap_worker.parse_and_save_links_from_sitemap(website_id, site_url=website_url)

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
            # got int instead of tuple or list, so convert int->list
            person_ids = [person_ids]
        # Before start scanning keep in mind ids of new keywords with rescan_needed=1
        # After scanning we'll check out this flag on its.
        remember_keywords = self.crawler_persons_conn.get_unscanned_keywords_for_persons(person_ids)
        persons_dict = self.crawler_persons_conn.get_person_with_keywords(person_ids)
        # Make dictionary of regular expressions for data searching
        my_search_patterns = create_search_patterns(persons_dict)
        # Grabs a generator of all pages in the database
        pages_list_generator = self.crawler_sites_conn.get_all_pages_id_gen()
        page_ranks = {}
        for page_id, site_id, found_date_time in pages_list_generator:
            # Check is there a saved  version of page
            page_text = self.crawler_sites_conn.get_page_text(page_id)
            if not page_text:
                print('There is no page with id: {} in the database. Flag this id to further processing'.format(page_id))
                self.crawler_sites_conn.mark_page_to_scan(page_id)
                continue

            # If we have a text of the page then compute a rank
            rank_info = rank_page(my_search_patterns, page_text)
            rank_info['date-modified'] = found_date_time
            rank_info['page-text'] = page_text
            rank_info['rescanned'] = 1
            page_ranks[page_id] = rank_info

            # Save results after 10 proceeded pages
            if len(page_ranks) >= 10:
                self.crawler_person_page_rank_conn.save(page_ranks)
                page_ranks.clear()
        # De-flag persons
        self.crawler_persons_conn.set_persons_scanned(person_ids)
        # And de-flag keywords
        if len(remember_keywords) > 0:
            self.crawler_persons_conn.set_keywords_scanned(remember_keywords)
        # Start pages rescan so pages we recently flagged will be downloaded and processed.
        print('Finished rescan of saved pages. Starting crawler for visit some pages.')
        self.start_pages_crawling()

    def start_pages_crawling(self):
        """
        Download and proceed all pages with rescan_needed=1 flag in database
        :return:
        """
        # Check if celery active at the moment
        print('Starting pages crawling.')
        inspection_helper = app.control.inspect()

        while True:
            active_tasks = inspection_helper.active()
            reserved_tasks = inspection_helper.reserved()
            scheduled_tasks = inspection_helper.scheduled()
            if active_tasks is None or reserved_tasks is None or scheduled_tasks is None:
                print('No celery worker detected. Please, provide one')
                # TODO Add something here. Notification for admin maybe.
                return
            for key, item in active_tasks.items():
                active_count = len(item)
            for key, item in reserved_tasks.items():
                reserved_count = len(item)
            for key, item in scheduled_tasks.items():
                shed = len(item)
            if active_count + reserved_count + shed == 0:
                break
            else:
                print('Celery tasks is up and running already. Wait for its ending.'
                      ' Active:{}/Reserved:{}/Sheduled:{}'.format(active_count, reserved_count, shed))
                time.sleep(120)

        # Celery Queue is empty. Let's fill it
        # my_task = WebCrawlerTask()
        my_task3 = WebCrawler3PerSec()
        my_task1 = WebCrawler1PerSec()
        my_task5 = WebCrawler5PerSec()

        #Задаём лимиты на скорость выполнения
        # app.control.broadcast('rate_limit',
        #                       arguments={'task_name': 'brand_new.crawler_tasks.WebCrawler1PerSec',
        #                                  'rate_limit': '1/s'})
        # app.control.broadcast('rate_limit',
        #                       arguments={'task_name': 'brand_new.crawler_tasks.WebCrawler3PerSec',
        #                                  'rate_limit': '3/s'})
        # app.control.broadcast('rate_limit',
        #                       arguments={'task_name': 'brand_new.crawler_tasks.WebCrawler5PerSec',
        #                                  'rate_limit': '5/s'})


        person_ids = self.crawler_persons_conn.get_persons_ids()
        keywords_dict = self.crawler_persons_conn.get_person_with_keywords(person_ids)
        site_ids = self.crawler_sites_conn.get_sites_id()

        # Для каждого сайта получаем генератор, который будет выдавать ещё не просканированные страницы.
        # И туда-же закинем количество запросов
        websites_gens = []
        for _id in site_ids:
            # генератор, который будет выдавать страницы с флаго rescan_needed - {page_id: (page_url, page_found_date)}
            website_pages_g = self.crawler_sites_conn.get_pages_by_site_id_gen(_id)
            website_rate = self.crawler_sites_conn.get_site_rate_limit(_id)
            website_pages_to_scan_count = self.crawler_sites_conn.count_urls_to_scan(_id)
            websites_gens.append((website_pages_g, website_rate, website_pages_to_scan_count, _id))

        # Sorting via (number of pages to scan // site rate limit) = time to scan. Sites with smallest time goes first.
        websites_gens = sorted(websites_gens, key=lambda x: x[2] // x[1])

        for index, one_gen in enumerate(websites_gens):
                site_gen = one_gen[0]
                site_rate = one_gen[1]
                site_id = one_gen[3]

                if site_rate >= 5:
                    my_task = my_task5
                elif 1 < site_rate < 5:
                    my_task = my_task3
                else:
                    my_task = my_task1

                for page in site_gen:
                    for p_id, p_data in page.items():
                        my_task.delay(p_id, p_data[0], p_data[1], site_id)
        # All tasks in a celery now

    def update_database(self):
        self.rescan_all_sitemaps()
        self.start_pages_crawling()

    def check_new_items(self):
        new_sites, new_persons, new_keywords, new_pages = self.monitoring.get_stat_info()

        print('New sites: {}. New Persons: {}. New Keywords: {}. New Pages: {}'.format(new_sites,
                                                                                       new_persons,
                                                                                       new_keywords,
                                                                                       new_pages))
        if new_sites > 0:
            new_ids = self.crawler_sites_conn.get_newly_added_websites_ids()
            print('Detected new websites with id={}'.format(new_ids))
            print('Start sitemaps scanning.')
            self.rescan_few_sitemaps(new_ids)
            self.crawler_sites_conn.set_sites_scanned(new_ids)
            print('Start page crawling.')
            self.start_pages_crawling()
            return

        if new_persons > 0 or new_keywords > 0:
            print('Detected new persons or keywords.')
            new_ids = self.crawler_persons_conn.get_persons_id_for_newly_added_persons_or_keywords()
            print('Id of persons with changes = {}'.format(new_ids))
            self.rescan_new_persons(new_ids)
            return

        if new_pages > 0:
            print('Detected unvisited pages. Start crawling.')
            self.start_pages_crawling()
        return


if __name__ == '__main__':
    test = CrawlerWorker()
    while True:
        print('Start new loop.')
        time.sleep(120)
        test.check_new_items()
