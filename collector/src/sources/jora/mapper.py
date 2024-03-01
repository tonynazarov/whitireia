import json

from ...enums import Sources


def map_jobs(jobs: list, stage: int) -> list:
    values = []
    for country_data_set in jobs:

        for country in country_data_set:
            for job in country_data_set.get(country):

                if job.get('id') is None:
                    continue

                values.append((
                    job.get('id'),
                    Sources.SOURCE_JORA.value,
                    country,
                    job.get('title'),
                    job.get('body'),
                    json.dumps(job),
                    job.get('posted_at'),
                    stage,
                    job.get('company'),
                    job.get('salary'),
                    job.get('contract_type'),
                    job.get('location'),
                    job.get('employment_type'),
                    None,
                    job.get('link'),
                    json.dumps(job.get('extra')),
                ))

    return values
