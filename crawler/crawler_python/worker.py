from parse import HTMLParser
from db_connect import (CrawlerSitesConnector,
                        CrawlerPersonsConnector, CrawlerPersonPageRankConnector)


class WorkerPageRank:
    crawler_sites_conn = CrawlerSitesConnector()
    crawler_persons_conn = CrawlerPersonsConnector()
    crawler_person_page_rank_conn = CrawlerPersonPageRankConnector()

    def __init__(self, person_ids, site_ids):
        person_ids = self.data_validate(person_ids)
        site_ids = self.data_validate(site_ids)
        self.pages_dict = self.crawler_sites_conn.get(site_ids)
        self.parser = HTMLParser(self.crawler_persons_conn.get(person_ids))

    def go(self):
        ranks = self.parser.get_info_from(self.pages_dict)
        self.crawler_person_page_rank_conn.save(ranks)
        print('DONE!')

    @staticmethod
    def data_validate(data):
        if type(data) in (int, str):
            return str(data)
        return ','.join(map(str, data))


# test
# data:
# persons_ids = {1: ('путин', 'путину', 'путина', 'путине'), 3: ('муров', 'мурову', 'мурове')}
# sites 2 - lenta.ru and 4 - geekbrains.ru
if __name__ == '__main__':
    worker = WorkerPageRank(person_ids=(1, 3), site_ids='4, 2')
    worker.go()
