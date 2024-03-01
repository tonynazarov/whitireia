import json
import re

from ...enums import Sources
from ...parser import Parser as BaseParser


class Parser(BaseParser):

    def _set_project(self) -> None:
        self._project = Sources.SOURCE_SEEK.value

    def _get_link(self, content) -> str:
        soup = self._get_soup_page(content)

        return soup.find('meta', {'property': 'og:url'}).get('content')

    def _get_title(self, content) -> str:
        json_data = self.__get_json_job(content)

        return json_data.get('title')

    def _get_id(self, content) -> str:
        json_data = self.__get_json_job(content)

        return json_data.get('id')

    def _get_salary(self, content):
        json_data = self.__get_json_job(content)

        salary = json_data.get('salary')

        if salary is None:
            return None

        return salary.get('label')

    def _get_contract_type(self, content):
        return None

    def _get_location(self, content):
        json_data = self.__get_json_job(content)

        location = json_data.get('locationInfo')

        if location is None:
            return None

        return location.get('location')

    def _get_company(self, content):
        json_data = self.__get_json_adv(content)
        value = None
        for key in json_data.keys():
            if key[:4] == 'name':
                value = json_data.get(key)

        return value

    def _get_posted_at(self, content):
        return None

    def _get_industry(self, content):
        json_data = self.__get_json_job(content)
        classifications = json_data.get('classifications')
        value = None

        for classification in classifications:
            for key in classification.keys():
                if key[:5] == 'label':
                    value = classification.get(key)
                    break

        return value

    def _get_employment_type(self, content):
        json_data = self.__get_json_job(content)

        workTypes = json_data.get('workTypes')

        if workTypes is None:
            return None

        value = None
        for key in workTypes.keys():
            if key[:5] == 'label':
                value = workTypes.get(key)
                break

        return value

    def _get_body(self, content):
        json_data = self.__get_json_job(content)

        return json_data.get('content({\"platform\":\"WEB\"})')

    def _get_extra(self, content):
        json_data = self.__get_json_job(content)

        listed_at = json_data.get('listedAt')
        value = None
        for key in listed_at.keys():
            if key[:10] == 'shortLabel':
                value = listed_at[key]
                break

        return {
            'listed_date': value
        }

    def __is_redirect_page(self, content) -> bool:
        return False

    def __get_json_job(self, content):
        return self.__get_json(content).get('job')

    def __get_json_adv(self, content):
        return self.__get_json(content).get('adv')

    def __get_json(self, content) -> dict:
        json_raw = re.findall(r'window.SEEK_APOLLO_DATA = ({.*})', content)[0]
        json_data = json.loads(json_raw)

        adv = job = {}
        for value in json_data.keys():
            if value[:3] == 'Adv':
                adv = json_data.get(value)

            if value[:3] == 'Job':
                job = json_data.get(value)

        new_json_raw = {
            'adv': adv,
            'job': job
        }

        return new_json_raw
