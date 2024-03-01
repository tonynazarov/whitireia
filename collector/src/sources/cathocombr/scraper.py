import re

from bs4 import BeautifulSoup
from selenium import webdriver

from ...scraper import (
    Scraper as BaseScraper,
    Source as BaseSource,
    Page,
    PageCollection,
    Director
)


class Parser(BaseScraper):
    __total_jobs_id: dict = {'id': 'jobTitle'}
    __result_container = 'search-result'
    __index: str = '/vagas/{query}/?page={page}'

    def _get_index_url(self, page: int, per_page: int, query: str) -> str:
        return self._get_path(
            self.__index
            .replace('{page}', str(page))
            .replace('{query}', query)
        )

    def unpack_search_page_to_view_pages(self, page: Page, closure: callable):
        soup = BeautifulSoup(page.html, self._default_parser_type)
        jobs = soup.find('section', id=self.__result_container)

        if jobs is None:
            return

        jobs = jobs.find_all('li')

        for job in jobs:
            href = job.find('a').get('href')

            closure(href)

    def get_jobs_total(self, html: str) -> str:
        soup = BeautifulSoup(html, 'html.parser')

        jobs_total = soup.find(**self.__total_jobs_id).get_text()
        jobs_total = re.findall(r'(\d+)', jobs_total)

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
        parser = Parser(
            browser=browser,
            host=host,
            page_collection=PageCollection()
        )

        source = Source(jobs_per_page=jobs_per_page)

        return Director(parser, source)
