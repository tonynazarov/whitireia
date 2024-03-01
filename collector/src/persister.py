import datetime
import errno
import json
import os
import sys

from .scraper import PageCollection


def get_script_directory():
    return os.path.dirname(os.path.dirname(os.path.abspath(sys.argv[0])))


def persist_stats(project, country, jobs):
    script_directory = get_script_directory()
    stats_dir = os.path.join(
        script_directory,
        'data',
        'stats',
        datetime.datetime.now().strftime("%Y%m%d"),
        project,
    )

    try:
        os.makedirs(stats_dir)
    except OSError as e:
        if e.errno != errno.EEXIST:
            raise

    file = f'{stats_dir}/{country}.json'

    with open(file, 'w+') as stat_file:
        stat_file.write(json.dumps(jobs))


def persist(
        source_name: str,
        country: str,
        collection: PageCollection,
        jobs_total: int,
        jobs_total_overall: int,
) -> None:
    script_directory = get_script_directory()
    data_dir = os.path.join(
        script_directory,
        'data',
        datetime.datetime.now().strftime("%Y%m%d"),
        source_name,
        country
    )

    for key, page in collection.get_pages().items():
        full_page_dir = os.path.join(data_dir, page.get_type())

        try:
            os.makedirs(full_page_dir)
        except OSError as e:
            if e.errno != errno.EEXIST:
                raise

        file = os.path.join(full_page_dir, key + collection.get_ext())

        with open(file, 'wb') as download:
            download.write(page.get_html_bytes())

    file = os.path.join(data_dir, 'result.json')

    try:
        os.makedirs(data_dir)
    except OSError as e:
        if e.errno != errno.EEXIST:
            raise

    with open(file, 'wb') as download:
        download.write(json.dumps({
            'jobs_actual': len(collection.get_pages()),
            'jobs_with_ad': jobs_total,
            'time': datetime.datetime.now().isoformat(),
            'jobs_total_ai_overall': jobs_total_overall
        }).encode())
