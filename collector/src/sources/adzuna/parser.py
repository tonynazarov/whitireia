import re

from ...enums import Sources
from ...parser import Parser as BaseParser


class AdzunaParser(BaseParser):
    def _set_project(self) -> None:
        self._project = Sources.SOURCE_ADZUNA.value

    def _get_link(self, content) -> int:
        soup = self._get_soup_page(content)

        return soup.find('meta', {'property': 'og:url'}).get('content')

    def _get_title(self, content) -> str:
        soup = self._get_soup_page(content)

        title = soup.find('meta', {'name': 'og:title'})

        if title is not None:
            title = title.get('content')
        else:
            title = soup.find('h1').get_text()

        return title

    def _get_id(self, content) -> int:
        soup = self._get_soup_page(content)

        url = soup.find('meta', {'property': 'og:url'}).get('content')
        id = re.findall('\/(\d+)', url)

        return int(id[0])

    def _get_salary(self, content) -> str | None:
        soup = self._get_soup_page(content)

        table = soup.find('table').get_text()

        salary = re.findall('(?:Salary:|Gehalt:|Salário:)\s*(\w*)', table)

        if not salary:
            return None

        return salary[0]

    def _get_contract_type(self, content) -> str | None:
        soup = self._get_soup_page(content)

        table = soup.find('table').get_text()

        contract_type = re.findall('(?:Contract type:|Tipo de contrato:|Type de contrat:)\s*(\w*)', table)

        if not contract_type:
            return None

        return contract_type[0]

    def _get_location(self, content) -> str | None:
        soup = self._get_soup_page(content)

        table = soup.find('table').get_text()

        location = re.findall('(?:Location:|Lokalizacja:|Ort:|Plaats:|Localização:|Ubicación:|Lieu:)\s*(\w*)', table)

        if not location:
            return None

        return location[0]

    def _get_body(self, content):
        soup = self._get_soup_page(content)

        return soup.find('section', class_='adp-body').get_text()

    def _get_company(self, content) -> str | None:
        soup = self._get_soup_page(content)

        table = soup.find('table').get_text()

        company = re.findall('(?:Company:|Firma:|Unternehmen:|Bedrijf:|Empresa:|Entreprise:)\s*(\w*)', table)

        if not company:
            return None

        return company[0]

    def _get_posted_at(self, content: str) -> str | None:
        posted_at = re.findall('(?:"datePosted".*)"(.*)"', content)

        if not posted_at:
            return None

        return posted_at[0]

    def _get_industry(self, content: str) -> str | None:
        industry = re.findall('(?:"industry".*)"(.*)"', content)

        if not industry:
            return None

        industries = industry[0].replace(',', '').split()

        try:
            unknown = industries.index('Unknown')
            del industries[unknown]
        except:
            pass

        return industries

    def _get_employment_type(self, content: str) -> str | None:
        employment_type = re.findall('(?:"employmentType").*\s.*\s.*', content)

        if not employment_type:
            return None

        employment_type = re.findall('\w+', employment_type[0])[1:]

        if not employment_type:
            return None

        return employment_type[0]

    def __get_body(self, content):
        soup = self._get_soup_page(content)

        return soup.find('body')

    def _get_extra(self, content):
        return None













