# Релаизовать классы, отвечающие за парснг HTML-ей, Sitemap-ов и robots.txt.
# Это те классы, которые потребуются для обхода только по sitemap-ным ссылкам


class RobotsParser:
    def __init__(self, url):
        pass

    def get_sitemap_link(self):
        pass

    def can_i_visit(self, url):
        pass


class SitemapParser:
    pass


class HTMLParser:
    pass
