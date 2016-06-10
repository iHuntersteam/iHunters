from urllib import parse
from datetime import datetime

from brand_new.parse_old import SitemapParser, RobotsParser
from brand_new.db_connect import CrawlerSitesConnector


class SitemapWorker:
    sites_connector = CrawlerSitesConnector()
    robotstxt_reader = RobotsParser()
    sitemap_reader = SitemapParser()

    def __init__(self):
        self.site_ids = []
        self.site_urls = []

    def grab_site_ids(self):
        self.site_ids = self.sites_connector.get_sites_id()

    def grab_site_urls(self):
        if not self.site_urls:
            self.grab_site_ids()
        for one_id in self.site_ids:
            # res is a tuple (site_id, url)
            res = self.sites_connector.get_page_info(one_id)
            self.site_urls.append(res)

    def grab_one_site_url(self, site_id):
        res = self.sites_connector.get_page_info(site_id)
        return res[1] if res else None

    def parse_and_save_links_from_sitemap(self, site_id, site_url=None):
        if not site_url:
            site_url = self.grab_one_site_url(site_id)
        sitemap_links = self.robotstxt_reader.get_sitemap_links(site_url)
        # TODO add robots.txt rules and check urls with them before insert into database
        if not sitemap_links:
            parsed = parse.urlparse(site_url)
            sitemap_links = ['{s.scheme}://{s.netloc}/sitemap.xml'.format(s=parsed)]
        for sitemap_link in sitemap_links:
            urls_generator = self.sitemap_reader.get_parsed_urls(sitemap_link)
            url_counter = 0
            insert_many = []
            split_counter = 0
            for url, found_date in urls_generator:
                found_date = found_date or datetime.now()
                found_date = found_date.strftime('%Y-%m-%d %H:%M:%S')
                insert_many.append((url, site_id, found_date))
                split_counter += 1
                url_counter += 1
                if split_counter > 2000:
                    self.sites_connector.save_stack(insert_many)
                    split_counter = 0
                    insert_many.clear()
                    print('Processed: {} urls'.format(url_counter))
            self.sites_connector.save_stack(insert_many)
            print('Processed total: {} urls'.format(url_counter))

    def crawl_all_sitemaps(self):
        self.grab_site_urls()
        for _id, _url in self.site_urls:
            start_time = datetime.now()
            print('Processing: id: {}, url: {}'.format(_id, _url))
            self.parse_and_save_links_from_sitemap(_id, _url)
            t = datetime.now() - start_time
            print('Done in {} seconds.'.format(t.seconds))


if __name__ == '__main__':
    worker = SitemapWorker()
    worker.crawl_sitemaps()
