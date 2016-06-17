from urllib import parse
from urllib.parse import unquote
import re
from bs4 import UnicodeDammit
import requests


class Agent:
    """
    Class with attributes for a given user-agent
    """

    def __init__(self):
        self.rules_for_agent = []
        self.delay = []

    @staticmethod
    def extract_path(some_url):
        # Extracts path (with parameters) from given url, if url is already a
        # path (starts with /) it's rerurned without modifications.
        # If url is empty or only contains domain without trailing slash, returns a
        # single slash.
        if len(some_url) == 0:
            # emptry
            path = '/'
        elif some_url[0] == '/':
            # Starts with '/' so it's a path already
            path = some_url
        else:
            # url scheme://host/parameters...
            parts = parse.urlsplit(some_url)
            print(parts.path)
            print(parts.query)
            new_parts = parse.SplitResult(scheme='', netloc='', path=parts.path, query=parts.query, fragment='')
            #  scheme may be normalized to lower case and empty components may be dropped. Specifically,
            #  empty parameters, queries, and fragment identifiers will be removed.
            path = new_parts.geturl()
            if len(path) == 0:
                path = '/'
        return path

    def allowed(self, url):
        # Can I fetch a given url?
        path = unquote(self.extract_path(url))
        if path == '/robots.txt':
            return True
        allowed = [a for a in self.rules_for_agent if a[1].match(path)]
        if allowed:
            return max(allowed)[2]
        else:
            return True


class Rules:
    def __init__(self, url, content):
        self.agents = {}
        self.sitemaps = []
        self.url = url
        self.parse(content)

    @staticmethod
    def _make_regexp(rule):
        """Make a regex that matches the patterns from robots.txt"""
        # If the string doesn't start with a forward slash, we'll insert it
        # anyways:
        #   http://code.google.com/web/controlcrawlindex/docs/robots_txt.html
        # The only permissible start characters for a rule like this
        # are '*' and '/'
        if rule and rule[0] != '/' and rule[0] != '*':
            rule = '/' + rule
        tmp = re.escape(unquote(rule))
        return re.compile(tmp.replace('\*', '.*').replace('\$', '$'))

    def parse(self, content):
        # current Agent
        current_agent = Agent()
        if isinstance(content, bytes):
            converted = UnicodeDammit(content)
            if not converted.unicode_markup:
                # robots.txt is broken
                print('Robotstxt is broken at {}'.format(self.url))
                self.agents['*'] = Agent()
                return
            content = converted.unicode_markup
        current_agent_name = '*'
        last = ''
        for rawline in content.splitlines():
            # Throw away any leading or trailing whitespace
            line = rawline.strip()
            comment_sign_pos = line.find('#')
            if comment_sign_pos >= 0:
                line = line[:comment_sign_pos]
            if line == '':
                continue

            if ':' not in line:
                print('This line is bad - {}'.format(rawline))
                continue

            # looks valid
            key, value = [x.strip() for x in line.split(':', maxsplit=1)]
            key = key.lower()
            if key == 'user-agent' or key == 'useragent':
                if current_agent:
                    self.agents[current_agent_name] = current_agent
                current_agent_name = value.lower()
                if last != 'user-agent' and last != 'useragent':
                    current_agent = self.agents.get(current_agent_name, None) or Agent()

            elif current_agent and key == 'disallow':
                if len(value):
                    current_agent.rules_for_agent.append(
                        (len(value), self._make_regexp(value), False))
            elif current_agent and key == 'allow':
                if len(value):
                    current_agent.rules_for_agent.append(
                        (len(value), self._make_regexp(value), True))
            elif current_agent and key == 'crawl-delay':
                try:
                    current_agent.delay = float(value)
                except ValueError:
                    print('Crawl delay directive is not a digit - {}'.format(value))
            elif current_agent and key == 'sitemap':
                self.sitemaps.append(value)
            else:
                print('Unknown key in robots.txt line {}'.format(rawline))
            last = key
        self.agents[current_agent_name] = current_agent or Agent()

    def allowed(self, url, agent):
        return self.agents.get(agent.lower(), self.agents.get('*')).allowed(url)

    def disallowed(self, url, agent):
        return not self.allowed(url, agent)

    def delay(self, agent):
        return self.agents.get(agent.lower(), self.agents.get('*')).delay

    def sitemaps(self):
        return self.sitemaps


class RobotsFactory:
    """
    Robots.txt parser
    """
    _rules_for_sites = {}

    def select_rules(self, url, site_id):
        if not self._rules_for_sites.get(site_id):
            parsed = parse.urlsplit(url)
            robot_url = '{s.scheme}://{s.netloc}/robots.txt'.format(s=parsed)
            # TODO check exeptions and check status code
            robots_txt = requests.get(robot_url)
            if robots_txt.status_code == 200:
                rule_set = Rules(url, robots_txt.content)
            else:
                rule_set = Rules(url, '')
            self._rules_for_sites[site_id] = rule_set
        return self._rules_for_sites[site_id]

    def get_sitemap_links(self, url, site_id):
        current_rules = self.select_rules(url, site_id)
        return current_rules.sitemaps

    def check_url_allowed(self, url, site_id, useragent):
        current_rules = self.select_rules(site_id)
        return current_rules.allowed(url, useragent)

    def check_url_disallowed(self, url, site_id, useragent):
        return not self.check_url_allowed(url, site_id, useragent)

    def get_rules_object(self, url, site_id):
        return self.select_rules(url, site_id)


