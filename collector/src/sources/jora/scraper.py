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
    __total_jobs_id: dict = {'class_': 'search-results-count'}
    __total_jobs_el: str = 'strong'
    __total_jobs_attr: str = 'data-cy-count'
    __result_container = 'jobresults'
    __index: str = '/j?p={page}&q={query}'

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
        jobs = soup.find('div', class_=self.__result_container)
        jobs = jobs.find_all('div', class_='organic-job')

        for job in jobs:
            href = job.find('a', class_='job-link').get('href')

            # sponsored links leads to another sites, it is not make sense to grap them
            if len(re.findall('\/job\/rd\/', href)) > 0:
                continue

            closure(href)

    def get_jobs_total(self, html: str) -> str:
        soup = BeautifulSoup(html, 'html.parser')

        if soup.find(class_='no-results-container'):
            raise NoJobsException()

        el_string = soup.find(**self.__total_jobs_id) \
            .find(self.__total_jobs_el)

        jobs_total = el_string.get_text()
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
        parser = Scraper(
            browser=browser,
            host=host,
            page_collection=PageCollection()
        )

        source = Source(jobs_per_page=jobs_per_page)

        return Director(parser, source)
