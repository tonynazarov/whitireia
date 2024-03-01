import re

from bs4 import BeautifulSoup
from selenium import webdriver

from ...scraper import (
    Scraper as BaseScraper,
    Source as BaseSource,
    Page,
    Director,
    PageCollection,
    NoJobsException
)


class Scraper(BaseScraper):
    __total_jobs_id: dict = {'class_': 'ui-search-heading'}
    __total_jobs_el: str = 'span'
    __total_jobs_attr: str = 'data-cy-count'
    __result_container = 'ui-search-results'
    __index: str = '/search?q={query}&p={page}&pp={pp}'

    def _get_index_url(self, page: int, per_page: int, query: str) -> str:
        return self._get_path(
            self.__index
            .replace('{page}', str(page))
            .replace('{pp}', str(per_page))
            .replace('{query}', query)
        )

    def unpack_search_page_to_view_pages(self, page: Page, closure: callable):
        soup = BeautifulSoup(page.html, self._default_parser_type)
        jobs = soup.find('div', class_=self.__result_container)
        jobs = jobs.find_all('div', recursive=False)

        for job in jobs:
            if not job.has_attr('data-aid'):
                continue

            href = job.find('div', class_='w-full').find('a').get('href')

            if re.search('land/ad', href) is not None:
                continue

            closure(href)

    def get_jobs_total(self, html: str) -> str:
        soup = BeautifulSoup(html, 'html.parser')

        if soup.find(**self.__total_jobs_id) is None:
            raise NoJobsException()

        jobs_total = soup.find(**self.__total_jobs_id) \
            .find(self.__total_jobs_el) \
            .get(self.__total_jobs_attr)

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
