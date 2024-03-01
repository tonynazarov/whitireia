from .parser import Parser
from .mapper import map_jobs as run_parser
from ...enums import Sources
from ...scraper import run as run_scraper
from ...config import SOURCES_CONFIG
from ...persister import persist


def scrape() -> None:
    config = SOURCES_CONFIG.get(Sources.SOURCE_SEEK.value)
    run_scraper(config, persist)


def parse(stage: int, raw_data_dir: str, write_to_storage):
    parser = Parser(raw_data_dir)
    run_parser(parser.read_source(), stage, write_to_storage)