import codecs
import gzip
import re
from io import BytesIO
from itertools import chain
from urllib import parse

import requests
from bs4 import UnicodeDammit
from lxml import etree, html
from requests.exceptions import (SSLError, ConnectionError, URLRequired, MissingSchema, InvalidSchema,
                                 InvalidURL, TooManyRedirects)


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
            result = []
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

    def _fetch(self, url):
        """
        Receive file from a website. If it's auto-detects and reads gzip-compressed XML files (.gz)
        :return: BytesIO file-like object
        """
        # TODO add integration with downloader
        r = requests.get(url)
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
        sitemap = etree.parse(self._fetch(url))
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
        self.search_entry = search_dict
        self._search_patterns = {}
        # For each search pattern build a regular expression
        # \bВася|Васи|васе\b - '|' means OR, \b means - word boundary
        # Matches a word boundary position such as whitespace, punctuation, or the start/end
        # of the string. This matches a position, not a character.
        for key, value in search_dict.items():
            search_val = '|'.join(value)
            self._search_patterns[key] = re.compile(r'\b{}\b'.format(search_val), re.IGNORECASE)

    def get_info(self, webpage):
        """
        Returns rank dictionary like {'id': nubmer of needed words on the page}
        :param webpage: Webpage content
        :return: Calculated rank
        """
        # Using BeautifulSoup to encoding detection
        # It works very good. Much better than requests or lxml encoding detection
        converted = UnicodeDammit(webpage)
        if not converted.unicode_markup:
            raise UnicodeDecodeError(
                "Failed to detect encoding, tried [{}]".format(', '.join(converted.tried_encodings))
            )
        root = html.fromstring(converted.unicode_markup)
        # remove all <script> tags
        cleaner = html.clean.Cleaner(scripts=True)
        root = cleaner.clean_html(root)
        # we are interested in text on a webpage, so use just body tag
        body = root.xpath('body')[0]
        body_text = body.text_content()
        # print(body_text)
        result = {}
        for pattern_name in self._search_patterns:
            rank = self._search_patterns[pattern_name].findall(body_text)
            print(rank)
            result[pattern_name] = len(rank)
        return result
