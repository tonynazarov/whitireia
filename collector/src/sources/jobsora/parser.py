import re

from ...enums import Sources
from ...parser import Parser as BaseParser
from datetime import timedelta, date


class Parser(BaseParser):

    def _set_project(self) -> None:
        self._project = Sources.SOURCE_JOBSORA.value

    def _get_link(self, content) -> str:
        soup = self._get_soup_page(content)

        return soup.find('link', {'rel': 'canonical'}).get('href')

    def _get_title(self, content) -> str:
        soup = self._get_soup_page(content)

        return soup.find('h1').get_text().strip()

    def _get_id(self, content) -> str:
        link = self._get_link(content)

        return re.findall(r'-(\d+)', link)[0]

    def _get_salary(self, content):
        return None

    def _get_contract_type(self, content):
        return None

    def _get_location(self, content):
        soup = self._get_soup_page(content)

        block = soup.find(class_='w-page__content').find('div').find('div').find('div').find('div')
        location = block.find_all(class_='w-gap-md')[1].find('div').find('div').find_all('div')[1].get_text().strip()

        return location.split(' - ')[0]

    def __is_redirect_page(self, content) -> bool:
        soup = self._get_soup_page(content)

        return soup.find(class_='w-page__content') is None

    def _get_company(self, content):
        soup = self._get_soup_page(content)

        block = soup.find(class_='w-page__content').find('div').find('div').find('div').find('div')
        company = block.find_all(class_='w-gap-md')[1].find(class_='grid-common__col').find_all('div', recursive=False)[
            0].get_text().strip()

        return company

    def _get_posted_at(self, content):
        soup = self._get_soup_page(content)

        block = soup.find(class_='w-page__content').find('div').find('div').find('div').find('div')
        location = block.find_all(class_='w-gap-md')[1].find('div').find('div').find_all('div')[1].get_text().strip()

        age = location.split(' - ')

        if len(age) < 2:
            return date.today().strftime('%Y-%m-%d')

        days = re.findall(r'\d+', age[1])

        if len(days) == 0:
            return date.today().strftime('%Y-%m-%d')

        return (date.today() - timedelta(int(days[0]))).strftime('%Y-%m-%d')

    def _get_industry(self, content):
        return None

    def _get_employment_type(self, content):
        return None

    def _get_body(self, content):
        soup = self._get_soup_page(content)

        return soup.find(class_='w-page__content').find('div').find('div').find_all('div', recursive=False)[
            1].get_text().strip()

    def _get_extra(self, content):
        return None
