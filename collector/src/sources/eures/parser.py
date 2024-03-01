import json

from ...parser import Parser
from ...enums import Sources


class EuresParser(Parser):
    def _set_project(self) -> None:
        self._project = Sources.SOURCE_EURES.value

    def get_project_list_dir(self, country: str) -> str:
        return f'{self.get_project_dir()}/{country}/list'

    def read_file(self, open_file) -> list:
        try:
            json_data = json.loads(open_file.read())

            return json_data
        except json.decoder.JSONDecodeError as ex:
            return []

    def _iterate_append_jobs(self, jobs: list, open_file) -> None:
        jobs += self.read_file(open_file)

    def _finalise_jobs_list(self, country: str, jobs: list) -> None:
        jobs_grouped_by_language = {}
        for job in jobs:
            language = job.get('availableLanguages')[0]

            if not jobs_grouped_by_language.get(language):
                jobs_grouped_by_language[language] = []

            jobs_grouped_by_language[language].append(job)
            del language, job

        for country, jobs in jobs_grouped_by_language.items():
            self._jobs.append({country: jobs})

    def _append_jobs(self, country: str, jobs: list) -> None:
        self._jobs.append({country: jobs})

    def _get_id(self, content):
        pass

    def _get_salary(self, content):
        pass

    def _get_contract_type(self, content):
        pass

    def _get_location(self, content):
        pass

    def _get_company(self, content):
        pass

    def _get_posted_at(self, content):
        pass

    def _get_industry(self, content):
        pass

    def _get_employment_type(self, content):
        pass

    def _get_body(self, content):
        pass
