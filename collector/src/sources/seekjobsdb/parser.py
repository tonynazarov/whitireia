import json
import re

from ...enums import Sources
from ...parser import Parser as BaseParser


class Parser(BaseParser):

    def _set_project(self) -> None:
        self._project = Sources.SOURCE_SEEK_JOBSDB.value

    def _get_link(self, content) -> str:
        json_data = self.__get_json(content)

        return json_data.get('pageUrl')

    def _get_title(self, content) -> str:
        json_data = self.__get_json(content)

        return json_data.get('header').get('jobTitle')

    def _get_id(self, content) -> str:
        json_data = self.__get_json(content)

        return json_data.get('id')

    def _get_salary(self, content):
        return None

    def _get_contract_type(self, content):
        return None

    def _get_location(self, content):
        json_data = self.__get_json(content)

        return json_data.get('location')[0].get('location')

    def _get_company(self, content):
        json_data = self.__get_json(content)

        return json_data.get('header').get('company').get('name')

    def _get_posted_at(self, content):
        json_data = self.__get_json(content)

        return json_data.get('header').get('postedAt')

    def _get_industry(self, content):
        json_data = self.__get_json(content)

        value = json_data.get('jobDetail').get('jobRequirement').get('industryValue')

        if value is None:
            return None

        return value.get('label')

    def _get_employment_type(self, content):
        json_data = self.__get_json(content)

        return json_data.get('jobDetail').get('jobRequirement').get('employmentType')

    def _get_body(self, content):
        json_data = self.__get_json(content)

        html = json_data.get('jobDetail').get('jobDescription').get('html')

        return self.remove_html_tags(html)

    def _get_extra(self, content):
        pass

    def __is_redirect_page(self, content) -> bool:
        return False

    def __get_json(self, content) -> dict:
        json_raw = re.findall(r'window.REDUX_STATE = ({.*})', content)[0]
        json_data = json.loads(json_raw)

        for value in json_data.keys():
            if value[:7] == 'details':
                return json_data.get(value)
