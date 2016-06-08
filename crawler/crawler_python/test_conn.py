from db_connect import CrawlerSitesConnector
from db_connect import CONN

# получить несканированные страницы
sites_con = CrawlerSitesConnector()
if sites_con.need_scan():
    pages = sites_con.get_not_scan_pages_gen()
    print(pages)
# пока нет коммита last_scan_pages в handler не обновится
CONN.commit()
