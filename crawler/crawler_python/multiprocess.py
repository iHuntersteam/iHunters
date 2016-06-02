import multiprocessing as mp
from db_connect import CrawlerSitesConnector
from rank_worker import PageRankWorker

sites_connector = CrawlerSitesConnector()
sites_id = sites_connector.get_sites_id()


def crawl_site(site_id):
    ranker = PageRankWorker()
    ranker.crawl_website(site_id)


if __name__ == '__main__':
    p = mp.Pool(4)
    p.map(crawl_site, sites_id)
