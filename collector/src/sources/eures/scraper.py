import re
from urllib import request
import json
import math
from datetime import datetime

from bs4 import BeautifulSoup
from selenium import webdriver

from ...scraper import (
    Scraper as BaseScraper,
    Source as BaseSource,
    Page, Director as BaseDirector,
    PageCollection,
    UnavailableException,
    to_json
)


class Scraper(BaseScraper):
    __total_jobs_id: dict = {'class_': 'ui-search-heading'}
    __total_jobs_el: str = 'span'
    __total_jobs_attr: str = 'data-cy-count'
    __result_container = 'main-content'
    __index: str = '/eures/portal/jv-se/search?page={page}&' \
                   'resultsPerPage={pp}&orderBy=BEST_MATCH&keywordsEverywhere={query}&lang=en'
    __api: str = '/eures/eures-apps/searchengine/page/jv-search/search'
    _wait_for_loading: bool = False
    _wait_for_element: str | None = 'jv-result-summary-title'
    _wait_for_time: int | None = 60
    _collection_include_list: bool = False

    def _get_api_url(self) -> str:
        return self._get_path(self.__api)

    def _get_index_url(self, page: int, per_page: int, query: str) -> str:
        return self._get_path(
            self.__index
            .replace('{page}', str(page))
            .replace('{pp}', str(per_page))
            .replace('{query}', query)
        )

    def unpack_search_page_to_view_pages(self, page: Page, closure: callable):
        soup = BeautifulSoup(page.html, self._default_parser_type)
        jobs = soup.find('main').find_all('a', class_='jv-result-summary-title')
        self._wait_for_element = 'jv-details-job-details-title'
        for job in jobs:
            href = job.get('href')

            closure(self._host + href)

    def get_jobs_total(self, html: str) -> str:
        soup = BeautifulSoup(html, 'html.parser')

        jobs_total = soup.find('main').find('h2').get_text()
        jobs_total = re.findall('f (\d+) v', jobs_total)[0]

        return jobs_total

    def request_api_search_page(self, page: int, limit: int = 50, scrape_all=True) -> int:
        total_pages: int | None = None
        while True:
            url = self._get_api_url()
            body = self.__get_body(page, limit)
            _uurl = f"{url}"
            print(f"Parsing route: POST {_uurl} {datetime.now()}: started")

            req = request.Request(
                method='POST',
                headers={
                    "X-XSRF-TOKEN": f"{self.get_xsrf_cookie()}",
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Cookie": f"XSRF-TOKEN={self.get_xsrf_cookie()}"
                },
                url=url,
                data=body,
            )

            with request.urlopen(req) as response:
                data = json.loads(response.read())

            jvs = data.get('jvs')

            _page = Page(
                html=json.dumps(jvs),
                url=_uurl+body.__str__()
            )
            _page.set_type_list()

            self._page_collection.add_page(_page)
            jobs_total = data.get('numberRecords', None)

            if total_pages is None:
                total_pages = math.ceil(jobs_total / limit)

            page += 1
            if len(jvs) == 0:
                break

            if not scrape_all:
                break

        return jobs_total

    @staticmethod
    def __get_body(page: int, size: int) -> bytes:
        return to_json({
            "keywords": [
                {"keyword": "chatgpt", "specificSearchCode": "EVERYWHERE"},
            ],
            "publicationPeriod": "LAST_MONTH",
            "occupationUris": [],
            "skillUris": [],
            "requiredExperienceCodes": [],
            "positionScheduleCodes": [],
            "sectorCodes": [],
            "educationLevelCodes": [],
            "positionOfferingCodes": [],
            "locationCodes": [],
            "euresFlagCodes": [],
            "otherBenefitsCodes": [],
            "requiredLanguages": [],
            "resultsPerPage": size,
            "sortSearch": "BEST_MATCH",
            "page": page,
        })


class Source(BaseSource):
    pass


class Director(BaseDirector):

    def __init__(
            self,
            scraper: Scraper = None,
            source: Source = None,

    ):
        self._scraper = scraper
        self._source = source

    def run(self) -> tuple[PageCollection, int]:
        self._scraper.get_page_collection().set_ext_json()
        self._scrape(self._source.query_chatgpt, True)

        return self._get_page_collection(), \
            self._source.get_jobs_total()

    def run_overall(self):
        self._scrape(self._source.query_ai, False)

        return self._get_page_collection(), \
            self._source.get_jobs_total()

    def _scrape(self, query: str, scrape_all: bool = True):
        try:
            self._scrape_index_page(query)
            self._source.set_xsrf_token(self._scraper.get_xsrf_cookie())

            jobs_total = self._scraper.request_api_search_page(1, scrape_all=scrape_all)

            self._source.set_jobs_total(jobs_total)

        except UnavailableException:
            pass


class Factory:
    @staticmethod
    def create_from_config(
            browser: webdriver,
            host: str,
            jobs_per_page: int,
    ):
        parser = Scraper(
            browser=browser,
            host=host,
            page_collection=PageCollection()
        )

        source = Source(jobs_per_page=jobs_per_page)

        return Director(parser, source)
