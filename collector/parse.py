from src.sources.adzuna.adzuna import parse as parse_adzuna
from src.sources.cathocombr.cathocombr import parse as parse_cathocombr
from src.sources.eures.eures import parse as parse_eures
from src.sources.hhru.hhru import parse as parse_hhru
from src.sources.indeed.indeed import parse as parse_indeed
from src.sources.jobsora.jobsora import parse as parse_jobsora
from src.sources.seek.seek import parse as parse_seek
from src.sources.seekjobsdb.seekjobsdb import parse as parse_jobsdb
from src.sources.seekjobstreet.seekjobstreet import parse as parse_jobstreet
from src.sources.upwork.upwork import parse as upwork_parse
import csv

folders = {
    1: '20231002',
    2: '20231101',
    3: '20231202',
}


def get_writer(storage_file):
    writer = csv.writer(storage_file)
    writer.writerow([
        'id',
        'salary',
        'contract type',
        'location',
        'company',
        'title',
        'description',
        'posted at',
        'industry',
        'employment_type',
        'link',
        'extra'
    ])

    def write(row: dict):
        writer.writerow(list(row.values()))

    return write


if __name__ == '__main__':
    root = f'../data'
    parsed_result = f'{root}/result.csv'
    with open(parsed_result, 'w', encoding='UTF8') as file:
        write_to_storage = get_writer(file)

        for stage in folders:
            raw_data_dir = f'{root}/{folders[stage]}'

            parse_adzuna(stage, raw_data_dir, write_to_storage)
            parse_cathocombr(stage, raw_data_dir, write_to_storage)
            parse_eures(stage, raw_data_dir, write_to_storage)
            parse_hhru(stage, raw_data_dir, write_to_storage)
            parse_indeed(stage, raw_data_dir, write_to_storage)
            parse_jobsora(stage, raw_data_dir, write_to_storage)
            parse_seek(stage, raw_data_dir, write_to_storage)
            parse_jobsdb(stage, raw_data_dir, write_to_storage)
            parse_jobstreet(stage, raw_data_dir, write_to_storage)
            upwork_parse(stage, raw_data_dir, write_to_storage)
