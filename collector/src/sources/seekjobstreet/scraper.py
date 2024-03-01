import re

from bs4 import BeautifulSoup
from selenium import webdriver

from ...scraper import (
    Scraper as BaseScraper,
    Source as BaseSource,
    Page,
    Director,
    PageCollection
)


class Scraper(BaseScraper):
    __total_jobs_id: dict = {'data-automation': 'SearchSummary'}
    __total_jobs_el: str = 'span'
    __result_container = 'jobListing'
    __index: str = '/{query}-jobs?page={page}'

    def parse_view_page(self, url: str) -> Page:
        page = self._scrape_source(self._get_path(url))
        page.set_type_view()

        return page

    def _get_index_url(self, page: int, per_page: int, query: str) -> str:
        return self._get_path(
            self.__index
            .replace('{page}', str(page))
            .replace('{pp}', str(per_page))
            .replace('{query}', query)
        )

    def unpack_search_page_to_view_pages(self, page: Page, closure: callable):
        soup = BeautifulSoup(page.html, self._default_parser_type)
        jobs = soup.find_all('article')

        for job in jobs:
            href = job.find('a').get('href')

            closure(href)

    def get_jobs_total(self, html: str) -> str:
        soup = BeautifulSoup(html, 'html.parser')

        try:
            jobs_total = soup.find('div', {'data-automation': 'searchResultBar'}).find('span').get_text()
            jobs_total = re.findall('f (\d+) j', jobs_total.replace(',', ''))

            return "".join(jobs_total)
        except AttributeError:
            jobs_total = soup.find('span', {'data-automation': 'totalJobsCount'}).get_text()
            jobs_total = jobs_total.replace(',', '')

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
