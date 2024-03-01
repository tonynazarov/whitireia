import json

from ...enums import Sources
from ...parser import Parser as BaseParser


class Parser(BaseParser):

    @staticmethod
    def _pre_handle_content(content):
        return json.loads(content)

    def _set_project(self) -> None:
        self._project = Sources.SOURCE_UPWORK.value

    def _get_link(self, content) -> str:
        link_id = content.get('ciphertext')

        return f'https://www.upwork.com/freelance-jobs/apply/some_{link_id}/'

    def _get_title(self, content) -> str:

        return content.get('title')

    def _get_id(self, content) -> str:

        return content.get('uid')

    def _get_salary(self, content):
        return None

    def _get_contract_type(self, content):
        return None

    def _get_location(self, content):

        location = content.get('prefFreelancerLocation')

        if location is None:
            return None

        return location

    def _get_company(self, content):
        return content.get('client').get('companyName')

    def _get_posted_at(self, content):
        return content.get('publishedOn')

    def _get_industry(self, content):
        return content.get('occupations').get('category').get('prefLabel')

    def _get_employment_type(self, content):
        return None

    def _get_body(self, content):
        return content.get('description')

    def _get_extra(self, content):
        skills = []
        for skill in content.get('attrs'): skills.append(skill.get('prettyName'))

        return {
            'skills': skills
        }

    def __is_redirect_page(self, content) -> bool:
        return False
