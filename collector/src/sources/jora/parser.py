import re
from datetime import timedelta

from ...enums import Sources
from ...parser import Parser as BaseParser


class Parser(BaseParser):

    def _set_project(self) -> None:
        self._project = Sources.SOURCE_JORA.value

    def _get_link(self, content) -> str:
        soup = self._get_soup_page(content)

        return soup.find('meta', {'property': 'og:url'}).get('content')

    def _get_title(self, content) -> str:
        soup = self._get_soup_page(content)

        return soup.find('h3').get_text().strip()

    def _get_id(self, content) -> str:
        link = self._get_link(content)

        return re.findall(r'-(\w{32})', link)[0]

    def _get_salary(self, content):
        return None

    def _get_contract_type(self, content):
        return None

    def _get_location(self, content):
        soup = self._get_soup_page(content)

        location = soup.find(id='company-location-container').find(class_='location').get_text()

        return location

    def _get_company(self, content):
        soup = self._get_soup_page(content)

        company = soup.find(id='company-location-container').find(class_='company')

        if company is None:
            return 'undefined'

        return company.get_text()

    def _get_posted_at(self, content):
        regex = r'datePosted":"(.{20})"'

        posted_at = re.findall(regex, content)

        if len(posted_at)>0:
            return posted_at[0]

        return None

    def _get_industry(self, content):
        return None

    def _get_employment_type(self, content):
        soup = self._get_soup_page(content)

        type = soup.find(class_='-default-badge')

        if type is None:
            return None

        return type.get_text()

    def _get_body(self, content):
        soup = self._get_soup_page(content)

        return soup.find(id='job-description-container').get_text().strip()

    def _get_extra(self, content):
        soup = self._get_soup_page(content)

        date = soup.find(id='job-meta').find(class_='listed-date').get_text()
        return {
            'listed_date': date
        }

    def __is_redirect_page(self, content) -> bool:
        soup = self._get_soup_page(content)

        return soup.find(id='job-description-container') is None

    def __get_json(self, content) -> dict:
        json_raw = re.findall(r'<script type="application/ld+json">({.*})</script>', content)[0]

        return json.loads(json_raw)
