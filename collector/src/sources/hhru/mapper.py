import json
import uuid

from ...enums import Sources


def map_jobs(jobs: list, stage: int, write_to_storage) -> None:
    for country_data_set in jobs:
        for country in country_data_set:
            for job in country_data_set.get(country):
                if dict(job) is False:
                    continue

                write_to_storage({
                    'id': str(uuid.uuid4()),
                    'sourceId': job.get('id'),
                    'source': Sources.SOURCE_HHRU.value,
                    'country': country,
                    'title': job.get('title'),
                    'body': job.get('body'),
                    'postedAt': job.get('posted_at'),
                    'stage': stage,
                    'company': job.get('company'),
                    'salary': job.get('salary'),
                    'contractType': job.get('contract_type'),
                    'location': job.get('location'),
                    'employmentType': job.get('employment_type'),
                    'industry': None,
                    'link': job.get('link'),
                    'extra': json.dumps(job.get('extra'))
                })
