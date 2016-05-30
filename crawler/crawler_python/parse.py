import gzip
import re
from datetime import datetime
from io import BytesIO
from itertools import chain
from urllib import parse
from urllib.parse import urljoin

import logging
import dateutil.parser
from bs4 import UnicodeDammit
from lxml import etree, html
from lxml.html.clean import Cleaner
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


class SiteMapException(BaseCrawlException):
    pass


class SitemapParser:
    def _fetch(self, url):
        """
        Receive file from a website. Auto-detects and reads gzip-compressed XML files (.gz)
        :return: BytesIO file-like object
        """
        r = ReqDownloader.fetch(ReqRequest(url), allow_redirects=False)
        if r.status_code != 200:
            logging.debug('Sitemap not found on {}, status code {} '.format(url, r.status_code))
            return BytesIO()
        if isinstance(r, BaseCrawlException):
            logging.debug('Sitemap isn\'t available on {}'.format(url))
            return BytesIO()
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
        :return: Generator with all found urls and dates. It returns tuples like (url, date).
        If date is not in sitemap it returns (url, None)
        """
        # grab sitemap
        try:
            sitemap = etree.parse(self._fetch(url))
        except XMLSyntaxError:
            logging.debug('XML parse error on {}'.format(url))
            sitemap = etree.parse(BytesIO(b'<?xml version="1.0" encoding="UTF-8" ?><wrong><bad></bad></wrong>'))
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
                try:
                    location = url_entry.find('bot:loc', namespaces=my_nsmap).text  # obligatory field
                    lastmod = url_entry.find('bot:lastmod', namespaces=my_nsmap)  # optional field
                    if lastmod is not None:
                        try:
                            lastmod_date = dateutil.parser.parse(lastmod.text)
                        except ValueError:
                            logging.debug('Error date string {} on {}'.format(lastmod.text, url))
                            lastmod_date = None
                        # TODO add filtering urls on date
                    if location:
                        # if xml tag <loc> is presented but empty location == None
                        # return only non-empty locations

                        yield location, lastmod_date
                except AttributeError:
                    # xml sitemap contains an error - missed <loc> tag.
                    # Ignore this error and parse the next entry
                    logging.debug('Missed obligatory field `loc` in XML on {}'.format(url))
        else:
            # bad xml
            # just ignore it
            logging.debug('Can\'t parse xml on {}'.format(url))


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

    def get_info_from(self, pages_dict):
        """
        Returns rank dictionary like {'id': number of needed words on the page}
        :return: Calculated rank
        """
        # {pages.id: pages.name, pages.other_id: pages.name и так далее?}
        # {1: ('http://lenta.ru/', '2016-05-30'), 12: ('http://example.com/', '2016-05-29')}
        #
        # на выходе ({page_id: { 'date_modified': 'date', person_id: rank, person_id2: rank2...},
        # {1: {'date_modified': '2016-05-30', 20: 10, }}
        page_ranks = {}
        for page_id, page_info in pages_dict.items():
            page_url, page_date_modified = page_info
            # Если дата страницы != сегодняшней, то скорее всего мы поставили её из сайтмапа. и значит её же и выдадим
            # в результате
            # А иначе пробуем определить дату.



            page_content = ReqDownloader.fetch(ReqRequest(page_url))
            if isinstance(page_content, BaseCrawlException):
                page_ranks[page_id] = {}
                continue
            # Using BeautifulSoup to encoding detection
            # It works very good. Much better than requests or lxml encoding detection
            else:
                converted = UnicodeDammit(page_content.content)
            if not converted.unicode_markup:
                # logging.debug(
                #     "Failed to detect encoding, tried [{}] page {}".format(', '.join(converted.tried_encodings),
                #                                                            page_content.request.url))
                page_ranks[page_id] = {}
                continue

            root = html.fromstring(converted.unicode_markup)
            # remove all <script> tags
            cleaner = Cleaner(scripts=True)
            root = cleaner.clean_html(root)
            # we are interested in text on a webpage, so use only body tag
            body = root.xpath('body')[0]
            body_text = body.text_content()
            result = {}
            for pattern_name in self._search_patterns:
                rank = self._search_patterns[pattern_name].findall(body_text)
                result[pattern_name] = len(rank)
            page_ranks[page_id] = result
        return page_ranks

