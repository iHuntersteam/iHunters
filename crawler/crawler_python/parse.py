import requests
import codecs
from requests.exceptions import (SSLError, ConnectionError, URLRequired, MissingSchema, InvalidSchema,
                                 InvalidURL, TooManyRedirects)
from urllib import parse


class RobotsParser:
    def __init__(self, url):
        """
        Robots.txt parser
        :param url: Any url from website to parse.
        """
        self.url = url

    def get_sitemap_links(self):
        """
        Parse robots.txt and search for sitemap links in it.
        :return: A list of sitemaps or an empty list if sitemaps are not found.
        """
        robots_txt = self._fetch()
        if robots_txt:
            # We need to decode binary string here
            # Robots.txt must be in ASCII. If we didn't get a decoded string, suggest it's utf-8 or utf-16
            # If the decoding fails we say robots.txt is a garbage.
            if isinstance(robots_txt, bytes):
                try:
                    if robots_txt.startswith(codecs.BOM_UTF8):
                        # Normally, the BOM is used to signal the endianness of an encoding, but since endianness
                        # is irrelevant to utf-8, the BOM is unnecessary.
                        # BOM for utf-8 files is not recommended according to Unicode standart.
                        # Anyway, some 'smart' owner could save robots.txt as utf-8 with BOM.
                        robots_txt = robots_txt.decode('utf-8').lstrip(str(codecs.BOM_UTF8, encoding='utf-8'))
                    elif robots_txt.startswith(codecs.BOM_UTF16):
                        robots_txt = robots_txt.decode('utf-16')
                    else:
                        robots_txt = robots_txt.decode('utf-8', errors='ignore')
                except UnicodeDecodeError:
                    # Very rare and difficult to reproduce exception
                    return
            for rawline in robots_txt.splitlines():
                line = rawline.strip()
                # Throw away comments
                comment_sign = line.find('#')
                if comment_sign >= 0:
                    line = line[:comment_sign]
                # Throw away blank lines
                if line == '':
                    continue
                # Each line in robots txt must contains ':'
                if ':' not in line:
                    continue
                # Yes it is possible to have more than one sitemap-index-file:
                # http://www.sitemaps.org/protocol.html#index
                result = []
                key, val = [x.strip() for x in line.split(':', maxsplit=1)]
                if key.lower() == 'sitemap':
                    result.append(val)
            return result
        else:
            return []

    def allowed(self, url, useragent='*'):
        """
        Are you allowed to visit url according with robots.txt?
        :param url: url you want to visit
        :param useragent: your user-agent
        :return: True or False
        """
        pass

    def _fetch(self):
        try:
            parsed = parse.urlparse(self.url)
            robot_url = '{s.scheme}://{s.netloc}/robots.txt'.format(s=parsed)
            options = {'allow_redirects': True}
            req = requests.get(robot_url, options)
            return req.content
        except (SSLError, ConnectionError, URLRequired, MissingSchema, InvalidSchema, InvalidURL, TooManyRedirects):
            return None


class SitemapParser:
    pass


class HTMLParser:
    pass


p = RobotsParser(url='http://192.168.1.6:8080/')
p.get_sitemap_links()
