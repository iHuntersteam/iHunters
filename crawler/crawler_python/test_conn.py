from db_connect import CrawlerSitesConnector, CrawlerHandlerConnector

# получить несканированные страницы
sites_con = CrawlerSitesConnector()
if sites_con.need_scan():
    pages = sites_con.get_not_scan_pages_gen()
    # тут получение персон и работа краулера
    print(pages)

    # обновление даты последнего сканирования в handler
    CrawlerHandlerConnector.update_last_scan_pages()
