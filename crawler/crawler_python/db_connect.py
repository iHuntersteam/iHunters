class CrawlerPagesConnector:

    def get_page(page_id):
        try:
            pass
        except PageNotExists:
            print('Page with id {0} not exists.'.format(page_id))

    def save_page(page, site):
        try:
            pass
        except PageExists:
            print('Page {0} exists'.format(page))
        except SiteNotExists:
            print('Site {0} not exists'.format(site))


class CrawlerPersonPageRankConnector:

    def save_person_pr(person, page, page_rank):
        try:
            pass
        except PageNotExists:
            print('Page {0} not exists'.format(page))
        except PersonNotExists:
            print('Person {0} not exists'.format(person))


class CrawlerSitesConnector:

    def get_site(site_id):
        pass


class CrawlerPersonsConnector:

    def get_person(person_id):
        pass
