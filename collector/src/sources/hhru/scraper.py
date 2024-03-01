import json
import re
from typing import Any
from bs4 import BeautifulSoup

from selenium import webdriver

from ...scraper import Scraper as BaseScraper, Source as BaseSource, Page, Director, PageCollection


class Scraper(BaseScraper):
    __total_jobs_id: dict = {'class_': 'bloko-header-section-3'}
    __result_container = 'vacancy-serp-content'
    __index: str = '/search/vacancy?text={query}&page={page}'

    def _get_index_url(self, page: int, per_page: int, query: str) -> str:
        page -= 1
        return self._get_path(
            self.__index
            .replace('{page}', str(page))
            .replace('{pp}', str(per_page))
            .replace('{query}', query)
        )

    def unpack_search_page_to_view_pages(self, page: Page, closure: callable):
        soup = BeautifulSoup(page.html, self._default_parser_type)
        jobs = soup.find(id='HH-Lux-InitialState').get_text()
        jobs = json.loads(jobs)
        jobs = jobs.get('vacancySearchResult').get('vacancies')

        for job in jobs:
            href = job.get('links').get('desktop')

            if not isinstance(href, str):
                continue

            closure(href)

    def get_jobs_total(self, html: str) -> str:
        soup = BeautifulSoup(html, 'html.parser')

        jobs_total = soup.find(**self.__total_jobs_id).get_text()
        jobs_total = re.match(r'\d+', jobs_total).group()

        return jobs_total


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
