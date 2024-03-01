import math
import requests
import time
from ...scraper import PageCollection, Page
import json


class ForceRenewIterationException(Exception):
    pass


class Scraper:
    __total_pages = 2
    __data = []

    @staticmethod
    def __get_headers() -> dict:
        return {
            'authority': 'www.upwork.com',
            'accept': 'application/json, text/plain, */*',
            'accept-language': 'en,en-US;q=0.9',
            'dnt': '1',
            'referer': 'https://www.upwork.com/nx/jobs/search/?q=chatgpt&sort=recency',
            'sec-ch-ua': '"Google Chrome";v="117", "Not;A=Brand";v="8", "Chromium";v="117"',
            'sec-ch-ua-full-version-list': '"Google Chrome";v="117.0.5938.132", "Not;A=Brand";v="8.0.0.0", "Chromium";v="117.0.5938.132"',
            'sec-ch-ua-mobile': '?0',
            'sec-ch-ua-platform': '"macOS"',
            'sec-ch-viewport-width': '1604',
            'sec-fetch-dest': 'empty',
            'sec-fetch-mode': 'cors',
            'sec-fetch-site': 'same-origin',
            'user-agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36',
            'x-odesk-user-agent': 'oDesk LM',
            'x-requested-with': 'XMLHttpRequest',
            'x-upwork-accept-language': 'en-US',
        }

    @staticmethod
    def __get_cookies() -> dict:
        return {

        }

    @staticmethod
    def __get_params(page: int) -> dict:
        return {
            'q': 'chatgpt',
            'per_page': '100',
            'sort': 'recency',
            'page': page
        }

    def __request(self, page: int = 1):
        response = requests.get(
            url='https://www.upwork.com/search/jobs/url',
            params=self.__get_params(page),
            cookies=self.__get_cookies(),
            headers=self.__get_headers()
        )

        time.sleep(5)
        json_data = response.json()
        self.__data.append(json_data)

        return json_data.get('searchResults').get('paging')

    def run(self):
        collection = PageCollection()
        collection.set_ext_json()
        paging = self.__request()
        total = paging.get('total')

        end = math.ceil(total / paging.get('count')) + 1
        for page in range(1, end):
            self.__request(page)

        for page_data in self.__data:
            page = Page(html=json.dumps(page_data), url=page_data.get('url'))
            page.set_type_list()
            collection.add_page(page)

            for page in page_data.get('searchResults').get('jobs'):
                page_view = Page(html=json.dumps(page), url=page.get('uid'))
                page_view.set_type_view()
                collection.add_page(page_view)

        return collection, total


class Director:
    _scraper: Scraper = None

    def __init__(self, scraper: Scraper):
        self._scraper = scraper

    def run(self) -> tuple[PageCollection, int]:
        return self._scraper.run()


class Factory:
    @staticmethod
    def create_from_config():
        return Director(Scraper())
