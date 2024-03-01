import json
import re
import datetime
import string

from ...enums import Sources
from ...parser import Parser as BaseParser


class Parser(BaseParser):
    __is_seek_notation: bool = False

    def _set_project(self) -> None:
        self._project = Sources.SOURCE_SEEK_JOBSTREET.value

    def _get_link(self, content) -> str:
        json_data = self.__get_json(content)

        return json_data.get('pageUrl')

    def _get_title(self, content) -> str:
        json_data = self.__get_json(content)

        return json_data.get('title')

    def _get_id(self, content) -> str:
        json_data = self.__get_json(content)

        return json_data.get('id')

    def _get_salary(self, content):
        return None

    def _get_contract_type(self, content):
        return None

    def _get_location(self, content):
        json_data = self.__get_json(content)

        return json_data.get('location')

    def _get_company(self, content):
        json_data = self.__get_json(content)

        return json_data.get('company')

    def _get_posted_at(self, content):
        json_data = self.__get_json(content)

        return json_data.get('postedAt')

    def _get_industry(self, content):
        json_data = self.__get_json(content)

        return json_data.get('industry')

    def _get_employment_type(self, content):
        json_data = self.__get_json(content)

        return json_data.get('employmentType')

    def _get_body(self, content):
        json_data = self.__get_json(content)

        html = json_data.get('description')

        return self.remove_html_tags(html)

    def _get_extra(self, content):
        pass

    def __is_redirect_page(self, content) -> bool:
        return False

    def __get_json(self, content) -> dict:
        json_raw = re.findall(r'window.REDUX_STATE = ({.*})', content)

        try:
            json_data = json_raw[0]
        except IndexError:
            try:
                json_raw = re.findall(r'window.SEEK_REDUX_DATA = ({.*})', content)

                json_data = json_raw[0]
                json_data = json_data.replace('undefined', '""')
                self.__is_seek_notation = True
            except IndexError:
                raise ValueError

        json_data = json.loads(json_data)

        if self.__is_seek_notation_enabled():
            return self.__handle_seek_redux(json_data)
        else:
            return self.__handle_redux(json_data)

    def __is_seek_notation_enabled(self) -> bool:
        return self.__is_seek_notation is True

    @staticmethod
    def __handle_seek_redux(json_data):

        data = json_data.get('jobdetails').get('result').get('job')
        company = json_data.get('jobdetails').get('result').get('companyReviews')

        postedAt = data.get('listedAt').get('shortLabel')
        postedAt = int(re.findall(r'(\d+)', postedAt)[0])
        postedAt = datetime.datetime(2023, 12, 2) - datetime.timedelta(days=postedAt)

        return {
            'pageUrl': data.get('shareLink'),
            'title': data.get('title'),
            'id': data.get('id'),
            'location': data.get('location').get('label'),
            'company': None if company is None else company.get('name'),
            'postedAt': postedAt,
            'industry': None,
            'employmentType': None,
            'description': data.get('content'),
        }

    @staticmethod
    def __handle_redux(json_data) -> dict:
        for value in json_data.keys():
            if value[:7] == 'details':
                json_data = json_data.get(value)
                break

        industry = json_data.get('jobDetail').get('jobRequirement').get('industryValue')

        return {
            'pageUrl': json_data.get('pageUrl'),
            'title': json_data.get('header').get('jobTitle'),
            'id': json_data.get('id'),
            'location': json_data.get('location')[0].get('location'),
            'company': json_data.get('header').get('company').get('name'),
            'postedAt': json_data.get('header').get('postedAt'),
            'industry': None if industry is None else industry.get('label'),
            'employmentType': json_data.get('jobDetail').get('jobRequirement').get('employmentType'),
            'description': json_data.get('jobDetail').get('jobDescription').get('html'),
        }
