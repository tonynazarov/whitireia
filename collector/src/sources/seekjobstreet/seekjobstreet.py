from .mapper import map_jobs as run_parser
from .parser import Parser
from ...config import SOURCES_CONFIG
from ...enums import Sources
from ...persister import persist
from ...scraper import run as run_scraper


def scrape() -> None:
    config = SOURCES_CONFIG.get(Sources.SOURCE_SEEK_JOBSTREET.value)
    run_scraper(config, persist)


def parse(stage: int, raw_data_dir: str, write_to_storage):
    parser = Parser(raw_data_dir)
    run_parser(parser.read_source(), stage, write_to_storage)