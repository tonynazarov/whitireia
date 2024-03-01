from .parser import Parser
from .mapper import map_jobs
from ...db import Jobs, Database
from ...enums import Sources
from ...scraper import run
from ...config import SOURCES_CONFIG
from ...persister import persist


def scrape() -> None:
    config = SOURCES_CONFIG.get(Sources.SOURCE_JORA.value)
    run(config, persist)


# date = '20231002'
# data_dir = f'../data/{date}'
def parse(data_dir: str, stage: int):
    parser = Parser(data_dir)
    jobs = map_jobs(parser.read_source(), stage)

    db = Database()
    db.make_connection()

    jobs_db = Jobs(db)
    jobs_db.insert_values(jobs)
