import hashlib
import json
from abc import abstractmethod, ABC
from datetime import datetime
from typing import Any
import math

from selenium import webdriver, common
from selenium.webdriver.chrome.options import Options
from selenium_stealth import stealth

from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC


class NoJobsException(Exception):
    pass


class UnavailableException(Exception):
    pass


class Page:
    __TYPE_LIST = 'list'
    __TYPE_VIEW = 'view'

    url: str | None = None
    html: str | None = None,
    type: str | None = None,
    description: str | None = None,
    created_at: str | None = None,
    id: str | None = None,
    types: list | None = None

    def __init__(self, url, html):
        self.url = url
        self.html = html

    def get_type(self) -> str:
        return self.type

    def set_type_list(self):
        self.type = self.__TYPE_LIST

    def is_type_list(self) -> bool:
        return self.type == self.__TYPE_LIST

    def is_type_view(self) -> bool:
        return self.type == self.__TYPE_VIEW

    def set_type_view(self):
        self.type = self.__TYPE_VIEW

    def get_html(self) -> str:
        return self.html

    def get_html_bytes(self) -> bytes:
        return self.html.encode()


class PageCollection:
    __pages: dict = {}
    __ext: str = '.html'

    def __new__(cls, *args, **kwargs):
        instance = super().__new__(cls)
        instance.__pages = {}
        return instance

    def set_ext_json(self):
        self.__ext = '.json'

    def get_ext(self) -> str:
        return self.__ext

    @staticmethod
    def hash_key(url: str) -> str:
        result = hashlib.md5(url.encode())

        return result.hexdigest()

    def add_page(self, page: Page):
        self.__pages[self.hash_key(page.url)] = page

    def get_pages(self) -> dict:
        return self.__pages

    def get_page(self, url: str) -> Page | None:
        return self.__pages.get(self.hash_key(url))

    def is_hit(self, url: str) -> bool:
        return self.get_page(self.hash_key(url)) is Page


class Scraper(ABC):
    _host: str | None = None
    _browser: webdriver.Chrome = None
    _page_collection: PageCollection | None = None
    _default_parser_type: str = 'html.parser'
    _wait_for_loading: bool = False
    _wait_for_element: str | None = None
    _wait_for_time: int | None = None
    _collection_include_list: bool = True

    def __init__(self, browser: webdriver, page_collection: PageCollection, host: str = None, ):
        self._page_collection = page_collection
        self._browser = browser
        self._host = (host, self._host)[host is None]

    def get_page_collection(self) -> PageCollection:
        return self._page_collection

    def intercept(self):
        pass

    @abstractmethod
    def get_jobs_total(self, html: str):
        pass

    @abstractmethod
    def _get_index_url(self, page: Any, per_page: int, query: str):
        return self._get_search_url(page, per_page, query)

    def _get_search_url(self, page: Any, per_page: int, query: str):
        return self._get_index_url(page, per_page, query)

    def _get_path(self, path: str):
        return f"{self._host}{path}"

    def _scrape_source(
            self,
            url: str,
    ) -> Page | None:
        page = self._page_collection.get_page(url)

        if isinstance(page, Page):
            print(f"Page: [{url}] already found")
            return page
        else:
            try:
                print(f"Parsing route: {url} {datetime.now()}: started")
                self.intercept()
                self._browser.get(url)

                if self._wait_for_loading:
                    WebDriverWait(self._browser, self._wait_for_time).until(
                        EC.presence_of_element_located((By.CLASS_NAME, self._wait_for_element))
                    )

                html = self._browser.page_source
                print(f"Parsing route: {url} {datetime.now()}: finished")
                page = Page(url=url, html=html)

                if self._collection_include_list:
                    self._page_collection.add_page(page)

                return page
            except common.exceptions.WebDriverException:
                print(f"Skipped {url} {datetime.now()}")
                raise UnavailableException

    def get_cookie(self, name: str) -> dict | None:
        return self._browser.get_cookie(name)

    def get_xsrf_cookie(self) -> str | None:
        token_keyword: str = 'XSRF-TOKEN'

        return self.get_cookie(token_keyword)

    def get_browser(self):
        return self._browser

    def parse_search_page(
            self,
            per_page: int,
            query: str,
            start: Any = 1
    ) -> Page:
        url = self._get_index_url(start, per_page, query)

        page = self._scrape_source(url)
        page.set_type_list()

        return page

    def parse_view_page(self, url: str) -> Page:
        page = self._scrape_source(url)
        page.set_type_view()

        return page

    def extract_view_pages(self) -> None:
        def parse_view_page_by_url(url: str):
            self.parse_view_page(url)

        view_pages = self.get_page_collection().get_pages().copy()

        for page in view_pages.values():
            if page.is_type_list():
                self.unpack_search_page_to_view_pages(page, parse_view_page_by_url)

    @abstractmethod
    def unpack_search_page_to_view_pages(self, page: Page, closure: callable):
        pass


class Source(ABC):
    query_chatgpt = 'chatgpt'
    query_ai = 'ai'
    _jobs_per_page: int = 50
    _jobs_total: int | None = None
    _jobs_pages_total: int | None = None
    _xsrf_token: str | None = None

    def __init__(
            self,
            jobs_per_page: int = None,
    ):
        self._jobs_per_page = self._jobs_per_page if jobs_per_page is None else jobs_per_page

    def get_jobs_per_page(self) -> int:
        return self._jobs_per_page

    def get_jobs_pages_total(self, jobs_total: int) -> int:
        jobs_total = int(jobs_total)
        jobs_per_page = int(self.get_jobs_per_page())
        pages_total = math.ceil(jobs_total / jobs_per_page)

        return pages_total

    def set_xsrf_token(self, xsrf) -> None:
        self._xsrf_token = xsrf

    def set_jobs_total(self, jobs_total: int) -> None:
        self._jobs_total = jobs_total

    def set_jobs_pages(self, jobs_pages: int) -> None:
        self._jobs_pages_total = jobs_pages

    def get_pages_total(self) -> int:
        return self._jobs_pages_total

    def get_jobs_total(self) -> int:
        return self._jobs_total


class Director:
    _scraper: Scraper = None
    _source: Source = None

    def __init__(
            self,
            scraper: Scraper = None,
            source: Source = None,

    ):
        self._scraper = scraper
        self._source = source

    def run(self) -> tuple[PageCollection, int]:
        self._scrape_and_memorise_search_result_params(self._source.query_chatgpt)
        self._scrape_pages(self._source.query_chatgpt)
        self._extract_and_scrape_view_pages()

        return self._get_page_collection(), \
            self._source.get_jobs_total()

    def run_overall(self):
        self._scrape_and_memorise_search_result_params(self._source.query_ai)

        return self._get_page_collection(), \
            self._source.get_jobs_total()

    def _scrape_index_page(self, query: str) -> str:
        return self._scraper.parse_search_page(
            self._source.get_jobs_per_page(),
            query,
        ).get_html()

    def _scrape_and_memorise_search_result_params(self, query: str):
        try:
            html = self._scrape_index_page(query)

            jobs_total = self._scraper.get_jobs_total(html)
            self._source.set_jobs_total(jobs_total)

            jobs_pages = self._source.get_jobs_pages_total(jobs_total)
            self._source.set_jobs_pages(jobs_pages)
        except UnavailableException:
            pass

    def _extract_and_scrape_view_pages(self):
        self._scraper.extract_view_pages()

    def _scrape_pages(self, query: str, step: int = 1, start: int = 1, end: int = None):
        end = end if end is not None else self._source.get_pages_total()
        end += 1
        for index in range(start, end, step):
            try:
                self._scraper.parse_search_page(
                    self._source.get_jobs_per_page(),
                    query,
                    index
                )
            except UnavailableException:
                continue

    def _get_page_collection(self):
        return self._scraper.get_page_collection()


def to_json(body: any) -> bytes:
    return json.dumps(body).encode('utf-8')


def with_browser(func: callable):
    with get_default_browser() as browser:
        return func(browser)


def get_default_browser(
        config: dict = None,
):
    chrome_options = Options()

    args = config.get('arguments', [])
    experimental_options = config.get('experimental_options', [])

    chrome_options.add_experimental_option("prefs", config.get('prefs', {}))

    for arg in args:
        chrome_options.add_argument(arg)

    for option in experimental_options:
        chrome_options.add_experimental_option(*option)

    driver = webdriver.Chrome(options=chrome_options)
    stealth(driver, platform="Win32", fix_hairline=True)

    return driver


def run(
        config: dict | None,
        persist: callable
) -> None:
    factory = config.get('class')
    browser_options = config.get('browser')

    for host in config.get('hosts'):
        with get_default_browser(browser_options) as browser:
            director = factory.create_from_config(
                browser,
                host.get('host'),
                config.get('jobsPerPage')
            )

            try:
                page_collection, jobs_total = director.run()
                _, jobs_total_overall = director.run_overall()
            except NoJobsException:
                jobs_total_overall = 0
                jobs_total = 0
                page_collection = PageCollection()

            persist(
                config.get('source'),
                host.get('country'),
                page_collection,
                jobs_total,
                jobs_total_overall,
            )

            del director


def run_upwork(
        config: dict | None,
        persist: callable
):
    factory = config.get('class')

    director = factory.create_from_config()
    page_collection, jobs_total = director.run()

    persist(
        config.get('source'),
        config.get('host').get('country'),
        page_collection,
        jobs_total,
        0
    )

    del director
