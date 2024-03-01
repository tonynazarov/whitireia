import json
import os
from abc import abstractmethod, ABC
from typing import Any
import re

from bs4 import BeautifulSoup

from .config import Sources


class Parser(ABC):
    _project: Sources | None = None
    _jobs: list = []
    _soup_page = None

    def __new__(cls, *args, **kwargs):
        instance = super().__new__(cls)
        instance._project = None
        instance._jobs = []

        return instance

    def __init__(self, data_dir: str):
        self._reader_data_dir = data_dir
        self._set_project()

    def __is_redirect_page(self, content) -> bool:
        return False

    @abstractmethod
    def _set_project(self) -> None:
        pass

    def read_source(self) -> list[Any]:
        self.iterate_country_related_data()

        return self._jobs

    def iterate_country_related_data(self):
        for country in os.listdir(self.get_project_dir()):
            project_country_dir = self.get_project_list_dir(country)

            if not os.path.exists(project_country_dir):
                continue

            jobs = self.iterate_data_files(project_country_dir)

            self._finalise_jobs_list(country, jobs)

    def _finalise_jobs_list(self, country: str, jobs: list) -> None:
        self._jobs += [{country: jobs}]

    def iterate_data_files(self, project_country_dir: str) -> list:
        jobs = []
        for file in os.listdir(project_country_dir):
            file_path = f'{project_country_dir}/{file}'
            print(file_path)
            with open(file_path, 'r') as open_file:
                self._iterate_append_jobs(jobs, open_file)

        return jobs

    def _iterate_append_jobs(self, jobs: list, open_file) -> None:
        job = self.read_file(open_file)
        jobs.append(job)

    @staticmethod
    def _pre_handle_content(content):
        return content

    def read_file(self, open_file) -> Any:
        try:
            html = open_file.read()
            self.clean_soup_page()

            if self.__is_redirect_page(html):
                return {}

            html = self._pre_handle_content(html)

            return {
                'id': self._get_id(html),
                'salary': self._get_salary(html),
                'contract_type': self._get_contract_type(html),
                'location': self._get_location(html),
                'company': self._get_company(html),
                'title': self.remove_html_tags(self._get_title(html)),
                'body': self.remove_html_tags(self._get_body(html)),
                'posted_at': self._get_posted_at(html),
                'industry': self._get_industry(html),
                'employment_type': self._get_employment_type(html),
                'link': self._get_link(html),
                'extra': self._get_extra(html)
            }
        except json.decoder.JSONDecodeError:
            return {}
        except ValueError as e:
            return {}

    def get_project_dir(self) -> str:
        return f'{self._reader_data_dir}/{self._project}'

    def get_project_list_dir(self, country: str) -> str:
        return f'{self.get_project_dir()}/{country}/view'

    def clean_soup_page(self) -> None:
        self._soup_page = None

    def _get_soup_page(self, content) -> Any:
        if not self._soup_page:
            self._soup_page = BeautifulSoup(content, 'html.parser')

        return self._soup_page

    @abstractmethod
    def _get_id(self, content):
        pass

    @abstractmethod
    def _get_salary(self, content):
        pass

    @abstractmethod
    def _get_contract_type(self, content):
        pass

    @abstractmethod
    def _get_location(self, content):
        pass

    @abstractmethod
    def _get_company(self, content):
        pass

    @abstractmethod
    def _get_posted_at(self, content):
        pass

    @abstractmethod
    def _get_industry(self, content):
        pass

    @abstractmethod
    def _get_employment_type(self, content):
        pass

    def _get_link(self, content) -> str:
        soup = self._get_soup_page(content)

        return soup.find('meta', {'name': 'og:url'}).get('content')

    def _get_title(self, content):
        soup = self._get_soup_page(content)

        return soup.find('meta', {'name': 'og:description'}).get('content')

    @abstractmethod
    def _get_body(self, content):
        pass

    def _get_extra(self, content):
        return None

    @staticmethod
    def remove_html_tags(text):
        clean = re.compile('<.*?>')

        if text is None:
            return text

        return re.sub(clean, '', text)
