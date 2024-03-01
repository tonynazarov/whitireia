import re

from bs4 import BeautifulSoup
from selenium import webdriver
import json

from ...scraper import (
    Scraper as BaseScraper,
    Source as BaseSource,
    Page,
    Director,
    PageCollection,
    NoJobsException
)


class Scraper(BaseScraper):
    __total_jobs_id: dict = {'class_': 'c-result-panel__value'}
    __index: str = '?page={page}&query={query}'

    def _get_index_url(self, page: int, per_page: int, query: str) -> str:
        return self._get_path(
            self.__index
            .replace('{page}', str(page))
            .replace('{pp}', str(per_page))
            .replace('{query}', query)
        )

    def unpack_search_page_to_view_pages(self, page: Page, closure: callable):
        soup = BeautifulSoup(page.html, self._default_parser_type)
        jobs = soup.find('main').find_all('article')

        for job in jobs:
            href = job.get('data-href')

            data_listing = json.loads(job.get('data-listing'))

            if data_listing.get('vacancy').get('p') == 'stepstone.de':
                continue

            closure(href)

    def get_jobs_total(self, html: str) -> str:
        soup = BeautifulSoup(html, 'html.parser')
        jobs_total = soup.find(**self.__total_jobs_id)

        if jobs_total is None:
            raise NoJobsException()

        jobs_total = re.findall(r'(\d+)', jobs_total.get_text())

        return ''.join(jobs_total)


class Source(BaseSource):
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
