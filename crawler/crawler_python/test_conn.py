from db_connect import CrawlerSitesConnector, CrawlerPersonsConnector, CrawlerHandlerConnector

# получить несканированные страницы
sites_con = CrawlerSitesConnector()
if sites_con.need_scan():
    pages = sites_con.get_not_scan_pages_gen()
    # тут получение персон и работа краулера
    print('Not scaned pages:', list(pages))

    # обновление даты последнего сканирования по страницам в handler
    CrawlerHandlerConnector.update_last_scan_pages()

persons_con = CrawlerPersonsConnector()
if persons_con.need_scan():
    persons = persons_con.get_not_scan_pers()
    # тут получение страниц и работа краулера
    print('Not scaned persons:', persons)

    # обновление даты последнего сканирования по персонам с ключевыми словами в handler
    CrawlerHandlerConnector.update_last_scan_pers_keys()
