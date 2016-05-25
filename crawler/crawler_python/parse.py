import gzip
import re
from io import BytesIO
from itertools import chain
from urllib import parse

import logging
from bs4 import UnicodeDammit
from lxml import etree, html
from lxml.etree import XMLSyntaxError

from downloader import ReqRequest, ReqResponse, ReqDownloader, BaseCrawlException


class RobotsParser:
    """
    Robots.txt parser
    """
    @staticmethod
    def get_sitemap_links(url):
        """
        Parse robots.txt and search for sitemap links in it.
        :param url: web-site url
        :return: A list of sitemaps OR an empty list if sitemaps are not found.
        """
        parsed = parse.urlparse(url)
        robot_url = '{s.scheme}://{s.netloc}/robots.txt'.format(s=parsed)
        robots_txt = ReqDownloader.fetch(ReqRequest(robot_url))
        if isinstance(robots_txt, BaseCrawlException):
            logging.debug('Robots.txt isn\'t available')
            return []

        if isinstance(robots_txt.content, bytes):
            # Before there was a decoding algorithm. But since I use UnicodeDammit to decode html pages
            # I prefer to use it here too.
            converted = UnicodeDammit(robots_txt.content)
            if not converted.unicode_markup:
                # robots.txt is broken
                return []
            result = []
            for rawline in converted.unicode_markup.splitlines():
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


class SitemapParser:

    def _fetch(self, url):
        """
        Receive file from a website. Auto-detects and reads gzip-compressed XML files (.gz)
        :return: BytesIO file-like object
        """
        r = ReqDownloader.fetch(ReqRequest(url))
        if isinstance(r, BaseCrawlException):
            logging.debug('Sitemap isn\'t available')
            raise StopIteration
        if url.endswith('gz'):
            return gzip.GzipFile(fileobj=BytesIO(r.content))
        else:
            return BytesIO(r.content)

    def get_parsed_urls(self, url):
        """
        Parses sitemap.xml and extract urls from it
        If sitemap contains links to other sitemaps, automatically download and use them.
        Works with gzipped sitemaps too.
        :param url: Sitemap's url to parse
        :return: Generator with all found urls
        """
        # grab sitemap
        try:
            sitemap = etree.parse(self._fetch(url))
        except XMLSyntaxError:
            logging.debug('XML parse error')
            raise StopIteration
        # grab a root element
        root = sitemap.getroot()
        # prepare namespaces
        # As we only use default namespace from sitemap, we don't need to worry
        # about custom namespaces for video, mobile (etc)
        my_nsmap = {'bot': ''}
        if root.nsmap:
            my_nsmap['bot'] = root.nsmap[None]
        # There are two types of sitemaps - sitemaps of page urls and sitemaps of other sitemaps urls
        # The second one are used because one sitemap can't contain more than 50000 urls and can't be more
        # than 10 MB

        # Determine which sitemap we are dealing with
        if 'sitemapindex' in root.tag:
            # This is sitemap of sitemaps
            sitemap_list = [x for x in root.xpath('//bot:loc/text()', namespaces=my_nsmap)]
            yield from chain(*[self.get_parsed_urls(x) for x in sitemap_list])
        elif 'urlset' in root.tag:
            # This is sitemap of urls
            for url_entry in root.xpath('bot:url', namespaces=my_nsmap):
                location = url_entry.find('bot:loc', namespaces=my_nsmap).text  # obligatory field
                lastmod = url_entry.find('bot:lastmod', namespaces=my_nsmap)  # optional field
                if lastmod is not None:
                    lastmod = lastmod.text
                    # TODO add filtering urls on date
                yield location
        else:
            # bad xml
            raise StopIteration


class HTMLParser:

    def __init__(self, search_dict):
        """
        Search words from search_dict on webpage. Returns rank
        :param search_dict: Dictionary like {'id': ('word_form1', 'word_form2', 'word_form3'), 'id2': ('...' etc)}
        """
        self._search_patterns = {}
        # For each search pattern build a regular expression
        # \bВася|Васи|васе\b - '|' means OR, \b means - word boundary
        # Matches a word boundary position such as whitespace, punctuation, or the start/end
        # of the string. This matches a position, not a character.
        for key, value in search_dict.items():
            search_val = '|'.join(value)
            self._search_patterns[key] = re.compile(r'\b{}\b'.format(search_val), re.IGNORECASE | re.MULTILINE)

    def get_info_from(self, response):
        """
        Returns rank dictionary like {'id': nubmer of needed words on the page}
        :param response: Webpage ReqResponse from downloader
        :return: Calculated rank
        """
        # Using BeautifulSoup to encoding detection
        # It works very good. Much better than requests or lxml encoding detection
        converted = UnicodeDammit(response.content)
        if not converted.unicode_markup:
            raise UnicodeDecodeError(
                "Failed to detect encoding, tried [{}]".format(', '.join(converted.tried_encodings))
            )
        root = html.fromstring(converted.unicode_markup)
        # remove all <script> tags
        cleaner = html.clean.Cleaner(scripts=True)
        root = cleaner.clean_html(root)
        # we are interested in text on a webpage, so use only body tag
        body = root.xpath('body')[0]
        body_text = body.text_content()
        result = {}
        for pattern_name in self._search_patterns:
            rank = self._search_patterns[pattern_name].findall(body_text)
            print(rank)
            result[pattern_name] = len(rank)
        return result
