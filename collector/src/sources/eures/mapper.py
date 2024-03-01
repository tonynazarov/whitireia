import json
import uuid

from datetime import datetime
from ...enums import Sources


def map_jobs(jobs: list, stage: int, write_to_storage) -> None:
    for country_data_set in jobs:
        for country in country_data_set:
            for job in country_data_set.get(country):
                if dict(job) is False:
                    continue

                employment_type = None
                if job.get('positionScheduleCodes'):
                    employment_type = job.get('positionScheduleCodes')[0]

                company = None
                if job.get('employer'):
                    company = job.get('employer').get('name')

                posted_at = None
                if job.get('creationDate'):
                    posted_at = datetime.utcfromtimestamp(job.get('creationDate') / 1e3)

                write_to_storage({
                    'id': str(uuid.uuid4()),
                    'sourceId': job.get('id'),
                    'source': Sources.SOURCE_EURES.value,
                    'country': country,
                    'title': job.get('title'),
                    'body': job.get('body'),
                    'postedAt': posted_at,
                    'stage': stage,
                    'company': company,
                    'salary': None,
                    'contractType': None,
                    'location': None,
                    'employmentType': employment_type,
                    'industry': None,
                    'link': 'https://ec.europa.eu/eures/portal/jv-se/jv-details/' + job.get('id'),
                    'extra': json.dumps(job.get('extra'))
                })
