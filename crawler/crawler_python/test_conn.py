from db_connect import CrawlerSitesConnector, CrawlerPersonsConnector


persons_con = CrawlerPersonsConnector()
sites_con = CrawlerSitesConnector()

# not scanned pages страницы
if sites_con.need_scan():
    pages = sites_con.get_not_scan_pages_gen()
    persons_dict = persons_con.get_all_persons_with_keywords()
    print('Not scaned pages: {0}, Persons for scan: {1}'.format(
        pages, persons_dict))

# not scanned persons with keywords
if persons_con.need_scan():
    persons_dict = persons_con.get_not_scan_pers()
    pages = sites_con.get_all_pages_gen()
    print('Not scaned persons: {0}, Pages for scan: {1}'.format(
        persons_dict, pages))
