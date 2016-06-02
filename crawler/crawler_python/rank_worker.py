import threading
from parse import HTMLParser
from db_connect import (CrawlerSitesConnector,
                        CrawlerPersonsConnector, CrawlerPersonPageRankConnector)


class PageRankWorker:
    crawler_sites_conn = CrawlerSitesConnector()
    crawler_persons_conn = CrawlerPersonsConnector()
    crawler_person_page_rank_conn = CrawlerPersonPageRankConnector()

    def __init__(self):
        self.person_ids = self.crawler_persons_conn.get_persons_ids()
        self.person_ids = self.data_validate(self.person_ids)
        self.site_ids = self.crawler_sites_conn.get_sites_id()
        self.keywords_dict = self.crawler_persons_conn.get_person_with_keywords(self.person_ids)
        self.parser = HTMLParser(self.keywords_dict)

    def crawl_website(self, site_id):
        website_pages_g = self.crawler_sites_conn.get_pages_by_site_id_gen(site_id)
        # website_pages_g yields a dict {page_id: (page_url, page_found_date)}
        for page in website_pages_g:
            ranks = self.parser.get_info_from(page)
            self.crawler_person_page_rank_conn.save(ranks)

    def crawl_all(self):
        for site in self.site_ids:
            self.crawl_website(site)

    @staticmethod
    def data_validate(data):
        if type(data) in (int, str):
            return str(data)
        return ','.join(map(str, data))


# test = PageRankWorker()
# # print(test.person_ids)
# print(test.site_ids)
# print(test.keywords_dict)
# test.crawl_all()

# test.crawl_website(1)