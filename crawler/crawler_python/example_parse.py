from parse import RobotsParser, SitemapParser
import logging
import sys

"""
Example of parsing
1. Parse robots.txt and search link on a sitemap inside.
2. Parse sitemap and print all url's from it.
"""
logging.basicConfig(level=logging.DEBUG, stream=sys.stdout)

parsing_url = 'https://geekbrains.ru/'
robots_reader = RobotsParser()
sitemap_reader = SitemapParser()

sitemap_links = robots_reader.get_sitemap_links(parsing_url)
# Robots.txt can contain a few links to sitemaps. Not only one.
for sitemap_url in sitemap_links:
    urls_from_sitemap = sitemap_reader.get_parsed_urls(sitemap_url)  # It's a generator
    uniq_urls = set(urls_from_sitemap)
    print('Found: {} urls in {} sitemap.'.format(len(uniq_urls), parsing_url))
    print('--------------------')
    # print(uniq_urls)
