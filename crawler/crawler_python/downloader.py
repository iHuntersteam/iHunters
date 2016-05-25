import requests as requestslib
from requests.exceptions import (SSLError, ConnectionError, URLRequired,
                                 MissingSchema, InvalidSchema, InvalidURL,
                                 TooManyRedirects)


class BaseHTTPRequest:
    """HTTP request interface"""
    def __init__(self, url):
        self.url = url


class BaseHTTPResponse:
    """HTTP response interface"""
    def __init__(self, req, response):
        self.request = req
        self.body = response.content


class BaseHTTPDowloader:
    """Downloader interface
    The downloader must implement one task:
    - make http request and fetch response.
    """
    def get(self, requests):
        responses = []
        for request in requests:
            response = self._fetch(request)
            responses.append(response)
        return responses

    def _fetch(self, request):
        # TODO Add configuring (user-agent etc)
        try:
            res = requestslib.get(request.url)
            return BaseHTTPResponse(request, res)
        except (SSLError, ConnectionError, URLRequired,
                MissingSchema, InvalidSchema, InvalidURL, TooManyRedirects):
            # TODO Add error-handling
            return None
