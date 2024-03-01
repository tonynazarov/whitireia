import json
import re
from datetime import timedelta, date, datetime

from ...enums import Sources
from ...parser import Parser as BaseParser


class Parser(BaseParser):

    def __get_json(self, content) -> dict:
        json_raw = re.findall(r'window._initialData=({.*})', content)

        try:
            json_data = json_raw[0]
        except IndexError:
            raise ValueError

        return json.loads(json_data)

    def _set_project(self) -> None:
        self._project = Sources.SOURCE_INDEED.value

    def _get_link(self, content) -> str:
        soup = self._get_soup_page(content)

        return soup.find('meta', {'property': 'og:url'}).get('content')

    def _get_title(self, content) -> str:
        json_data = self.__get_json(content)

        return json_data.get('jobTitle')

    def _get_id(self, content) -> str:
        json_data = self.__get_json(content)

        if json_data.get('jobKey') is None:
            raise ValueError

        return json_data.get('jobKey')

    def _get_salary(self, content):
        json_data = self.__get_json(content)

        salary = json_data.get('salaryInfoModel')

        if salary is None:
            return None

        return json_data.get('salaryInfoModel').get('salaryText')

    def _get_contract_type(self, content):
        return None

    def _get_location(self, content) -> str:
        json_data = self.__get_json(content)

        return json_data.get('jobLocation')

    def _get_company(self, content) -> str:
        json_data = self.__get_json(content)

        return json_data.get('jobInfoWrapperModel').get('jobInfoModel').get('jobInfoHeaderModel').get('companyName')

    def _get_posted_at(self, content):
        json_data = self.__get_json(content)

        model = json_data.get('hiringInsightsModel')

        if model is None:
            return None

        age = model.get('age')

        days = re.findall(r'\d+\+?', age)

        if len(days) == 0:
            return None

        if days[0][-1] == '+':
            return None

        return (datetime(2023, 11, 2) - timedelta(int(days[0]))).strftime('%Y-%m-%d')

    def _get_industry(self, content):
        return None

    def _get_employment_type(self, content):
        json_data = self.__get_json(content)

        return json_data.get('jobInfoWrapperModel').get('jobInfoModel').get('jobMetadataHeaderModel').get('jobType')

    def _get_body(self, content):
        json_data = self.__get_json(content)

        return json_data.get('jobInfoWrapperModel').get('jobInfoModel').get('sanitizedJobDescription')

    def _get_extra(self, content):
        return None
