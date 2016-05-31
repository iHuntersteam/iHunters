from itertools import islice
from parse import HTMLParser
from db_connect import (CrawlerSitesConnector,
                        CrawlerPersonsConnector, CrawlerPersonPageRankConnector)


# TODO Refactoring
class WorkerPageRank:
    crawler_sites_conn = CrawlerSitesConnector()
    crawler_persons_conn = CrawlerPersonsConnector()
    crawler_person_page_rank_conn = CrawlerPersonPageRankConnector()

    def __init__(self, person_ids, site_ids):
        person_ids = self.data_validate(person_ids)
        site_ids = self.data_validate(site_ids)
        self.pages_dict = self.crawler_sites_conn.get_pages_by_site_id(
            site_ids)
        self.parser = HTMLParser(
            self.crawler_persons_conn.get_person_with_keywords(person_ids))

    def go(self):
        for id in list(self.pages_dict):
            print({id: self.pages_dict[id]})
            rank = self.parser.get_info_from({id: self.pages_dict[id]})
            self.crawler_person_page_rank_conn.save(rank)
        print('DONE!')

        # def split_to_chunks(data, step=100):
        #     it = iter(data)
        #     result = []
        #     for i in range(0, len(data), step):
        #         result.append({k: data[k] for k in islice(it, step)})
        #     return result

        # chunks = split_to_chunks(self.pages_dict, 20)
        # Если не разбить на куски, то воркер будет ждать обхода всех ссылок, прежде чем записать в БД
        # Если ссылок очень много - ждать будем крайне долго, и из-за какого-нибудь сбоя можно всё потерять.

        # for num, chunk in enumerate(chunks):
        #     print('Обрабатываем кусок списка url № {} из {}'.format(num + 1, len(chunks) + 1))
        #     ranks = self.parser.get_info_from(chunk)
        #     self.crawler_person_page_rank_conn.save(ranks)
        # print('DONE!')

    @staticmethod
    def data_validate(data):
        if type(data) in (int, str):
            return str(data)
        return ','.join(map(str, data))


# Тестовые данные на которых я проверял работу - в файле test_data.sql
if __name__ == '__main__':
    worker = WorkerPageRank(person_ids=(1, 2, 3), site_ids='1, 2')
    worker.go()
