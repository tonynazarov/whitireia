import re
from datetime import datetime

from ...enums import Sources
from ...parser import Parser as BaseParser


class Parser(BaseParser):
    def _set_project(self) -> None:
        self._project = Sources.SOURCE_HHRU.value

    def _get_link(self, content) -> str:
        soup = self._get_soup_page(content)

        return soup.find('link', {'rel': 'canonical'}).get('href')

    def _get_title(self, content):
        soup = self._get_soup_page(content)

        title = soup.find('h1', {'data-qa': 'vacancy-title'})

        if title is not None:
            return title.get_text()

        return ''

    def _get_id(self, content) -> str:
        link = self._get_link(content)

        return re.findall(r'/(\d+)', link)[0]

    def _get_salary(self, content):
        return ''

    def _get_contract_type(self, content):
        soup = self._get_soup_page(content)

        type = soup.find('p', {'data-qa': 'vacancy-view-employment-mode'})

        if type is not None:
            return type.get_text()

        return ''

    def _get_location(self, content):
        soup = self._get_soup_page(content)

        location = soup.find('p', {'data-qa': 'vacancy-view-location'})

        if location:
            return location.get_text()

        raw_address = soup.find('p', {'data-qa': 'vacancy-view-raw-address'})

        if raw_address:
            return raw_address.get_text()

        return ''

    def _get_company(self, content):
        soup = self._get_soup_page(content)

        company = soup.find('a', {'data-qa':'vacancy-company-name'})

        if company is not None:
            company.get_text().replace(" ", " ")
        else:
            return ''

    def _get_posted_at(self, content):
        soup = self._get_soup_page(content)

        date = soup.find(class_='vacancy-creation-time-redesigned')

        if date is None:
            return date

        date = date.find('span')

        if date is None:
            return date

        date = date.get_text()
        months = {
            'января': '01',
            'февраля': '02',
            'марта': '03',
            'апреля': '04',
            'мая': '05',
            'июня': '06',
            'июля': '07',
            'августа': '08',
            'сентября': '09',
            'октября': '10',
            'ноября': '11',
            'декабря': '12',
        }

        for key in months:
            date = date.replace(key, months[key])

        date = '-'.join(re.findall(r'(\d+)', date))
        date_object = datetime.strptime(date, '%d-%m-%Y')
        return date_object.strftime('%Y-%m-%d')

    def _get_industry(self, content):
        return ''

    def _get_employment_type(self, content):
        type = self._get_contract_type(content)

        if type is None:
            return ''

        if re.findall('удаленн', type):
            return 'REMOTE'

        return type

    def _get_body(self, content):
        soup = self._get_soup_page(content)

        body = soup.find('div', {'data-qa': 'vacancy-description'})

        if body is not None:
            return body.get_text()
        else:
            return ''

    def _get_extra(self, content):
        soup = self._get_soup_page(content)

        skills = []
        tags = soup.find(class_='bloko-tag-list')

        if tags:
            for tag in tags.find_all('span'):
                skills.append(tag.get_text())

        return skills
