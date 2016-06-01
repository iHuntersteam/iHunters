import requests as requestslib
from requests.exceptions import (SSLError, ConnectionError, URLRequired,
                                 MissingSchema, InvalidSchema, InvalidURL,
                                 TooManyRedirects)
import logging
log = logging.getLogger(__name__)


class ReqRequest:
    """HTTP request interface"""
    def __init__(self, url):
        self.url = url

    def __str__(self):
        return '<ReqRequest url:{s.url}>'.format(s=self)


class ReqResponse:
    """HTTP response interface"""
    def __init__(self, req, response):
        self.request = req
        self.content = response.content
        self.status_code = response.status_code
        self.headers = response.headers


class ReqDownloader:
    """Downloader interface
    The downloader must implement one task:
    - make http request and fetch response.
    """
    def get(self, requests):
        """
        Works with multiple requests
        :param requests: ReqRequest
        :return: ReqResponse
        """
        responses = []
        for request in requests:
            response = self.fetch(request)
            responses.append(response)
        return responses

    @staticmethod
    def fetch(request, **kwargs):
        """
        Load one request from the internet
        :param \*\*kwargs: Options for requests library (headers, user-agent, redirects)
        :param request: ReqRequest to load
        :return:
        """
        # TODO Add configuring (user-agent etc)
        http_headers = {
            'User-Agent': 'GeekBrains Bot 1.0',
        }
        try:
            res = requestslib.get(request.url, headers=http_headers, **kwargs)
            return ReqResponse(request, res)
        except (SSLError, ConnectionError, URLRequired,
                MissingSchema, InvalidSchema, InvalidURL, TooManyRedirects) as e:
            # TODO Add error-handling
            log.debug('Exceprion on {} '.format(request))
            return BaseCrawlException(request, exception=e)


class BaseCrawlException(Exception):
    """
    Downloader exception interface
    """
    def __init__(self, request=None, response=None, exception=None, exc_info=None):
        self.request = request
        self.response = response
        self.exception = exception
        self.exc_info = exc_info

    def __str__(self):
        return 'Exception on response: %s request: %s - %s' % (
            self.response, self.request, self.exception
        )
