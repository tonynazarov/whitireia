from datetime import datetime

from src.sources.adzuna.adzuna import scrape as adzuna_scrape
from src.sources.cathocombr.cathocombr import scrape as cathocombr_scrape
from src.sources.eures.eures import scrape as eures_scrape
from src.sources.hhru.hhru import scrape as hhru_scrape
from src.sources.indeed.indeed import scrape as indeed_scrape
from src.sources.jobsora.jobsora import scrape as jobsora_scrape
from src.sources.seek.seek import scrape as seek_scrape
from src.sources.seekjobsdb.seekjobsdb import scrape as seekjobsdb_scrape
from src.sources.seekjobstreet.seekjobstreet import scrape as seekjobstreet_scrape
from src.sources.upwork.upwork import scrape as upwork_scrape


def main():
    print(f"Start all at {datetime.now()}")
    seekjobstreet_scrape()
    seekjobsdb_scrape()
    seek_scrape()
    jobsora_scrape()
    indeed_scrape()
    hhru_scrape()
    eures_scrape()
    cathocombr_scrape()
    adzuna_scrape()
    upwork_scrape()


if __name__ == '__main__':
    main()
