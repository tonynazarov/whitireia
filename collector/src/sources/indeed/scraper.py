import random
import re
import json
import time
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
    __total_jobs_id: dict = {'class_': 'jobsearch-JobCountAndSortPane-jobCount'}
    __total_jobs_el: str = 'span'
    __result_container = 'ui-search-results'
    __index: str = '/jobs?q={query}&limit={pp}&start={page}&fromage=30'

    def parse_view_page(self, url: str) -> Page:
        page = self._scrape_source(self._get_path(url))
        page.set_type_view()

        return page

    def _get_index_url(self, page: int, per_page: int, query: str) -> str:
        page -= 1
        page = 0 if page == 0 else page * per_page

        return self._get_path(
            self.__index
            .replace('{page}', str(page))
            .replace('{pp}', str(per_page))
            .replace('{query}', query)
        )

    def unpack_search_page_to_view_pages(self, page: Page, closure: callable):
        data = re.findall(r'window.mosaic.providerData\["mosaic-provider-jobcards"\]=(\{.+?\});', page.html)
        data = json.loads(data[0])
        jobs = data["metaData"]["mosaicProviderJobCardsModel"]["results"]

        for job in jobs:
            time.sleep(random.randrange(10))
            closure(job.get('viewJobLink'))

    def get_jobs_total(self, html: str) -> str:
        soup = BeautifulSoup(html, 'html.parser')

        if soup.find(class_='jobsearch-NoResult-messageContainer') or soup.find(**self.__total_jobs_id) is None:
            raise NoJobsException()

        jobs_total = soup.find(**self.__total_jobs_id) \
            .find(self.__total_jobs_el).get_text()

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
