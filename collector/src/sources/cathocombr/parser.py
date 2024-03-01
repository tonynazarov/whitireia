import json
import re
from typing import Any
import json

from bs4 import BeautifulSoup

from ...enums import Sources
from ...parser import Parser as BaseParser


class Parser(BaseParser):
    def read_file(self, open_file) -> Any:
        try:
            html = open_file.read()
            self.clean_soup_page()
            return {
                'id': self._get_id(html),
                'salary': self._get_salary(html),
                'contract_type': self._get_contract_type(html),
                'location': self._get_location(html),
                'company': self._get_company(html),
                'title': self._get_title(html),
                'body': self._get_body(html),
                'posted_at': self._get_posted_at(html),
                'industry': self._get_industry(html),
                'employment_type': self._get_employment_type(html),
                'link': self._get_link(html),
            }
        except json.decoder.JSONDecodeError:
            return {}

    def _get_title(self, content) -> str:
        soup = self._get_soup_page(content)

        return self.__get_json(soup).get('titulo')

    def __get_json(self, soup):
        json_raw = soup.find(id='__NEXT_DATA__').get_text()

        return json.loads(json_raw).get('props').get('pageProps').get('jobAdData')

    def _set_project(self) -> None:
        self._project = Sources.SOURCE_CATHOCOMBR.value

    def _get_id(self, content) -> str:
        soup = self._get_soup_page(content)

        return self.__get_json(soup).get('id')

    def _get_salary(self, content):
        soup = self._get_soup_page(content)

        return self.__get_json(soup).get('faixaSalarial')

    def _get_contract_type(self, content):
        soup = self._get_soup_page(content)

        return self.__get_json(soup).get('regimeContrato')

    def _get_location(self, content):
        soup = self._get_soup_page(content)

        return self.__get_json(soup).get('vagas')[0].get('cidade')

    def _get_company(self, content):
        soup = self._get_soup_page(content)

        return ",".join(self.__get_json(soup).get('benef'))

    def _get_posted_at(self, content):
        soup = self._get_soup_page(content)

        return self.__get_json(soup).get('data')

    def _get_industry(self, content):
        return None

    def _get_employment_type(self, content):
        soup = self._get_soup_page(content)

        holario = self.__get_json(soup).get('horario')

        if re.findall('REMOTO', holario):
            return 'REMOTO'

        return self.__get_json(soup).get('horario')

    def _get_body(self, content):
        soup = self._get_soup_page(content)

        return self.__get_json(soup).get('descricao')
