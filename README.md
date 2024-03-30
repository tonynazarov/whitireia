# Introduction



├── analyser (PHP 8.2, Symfony 6.4, PostgreSQL, Docker) \
│   ├── .env (dummy file for configuration) \
│   ├── .env.local (local file for configuration - should be created manually) \
│   ├── composer.json (application dependencies) \
│   ├── composer.lock (application frozen versions of dependencies) \
│   ├── symfony.lock (symfony frozen versions of dependencies) \
│   ├── src/ \
│   ├── config/ (config files) \
│   ├── bin/ (commands) \
│   ├── public/ (public source for webserver) \
│   ├── migrations/ (migrations for database) \
├── collector (Python Python 3.10)  \
│   ├── requirements.txt (application dependencies)\
│   ├── scrape.py (Command for scraping data from source)\
│   ├── parse.py/ (Command for parsing collected data)\
│   ├── src/ \
│   │   ├── config.py (Configuration) \
│   │   ├── *.py \
│   │   ├── sources/ \
│   │   │   ├── adzuna/ \
│   │   │   ├── cathocombr/ \
│   │   │   ├── eures/ \
│   │   │   ├── upwork/ \
│   │   │   ├── ... \
├── data/ (local storage for Docker Containers data and collected data; created when applications are running )  \
│   ├── 20231002 (Stages)\
│   ├── ... \
│   ├── postgres (Docker container data) \
│   ├── result.csv (Prepared data for transfer to the analysis system) \
├── docker/ (local storage for Docker Containers data and collected data )  \
│   ├── .env (should be created, file configuration) \
│   ├── docker.sh (command for run docker containers) \
│   ├── docker-compose.yml (settings for docker containers) \
│   ├── container/ (Dockerfiles for analyser application) \

# Analyser

## Configuration file for Analyser

All required parameters defined in analyser/.env.

For the local build should be used .env.local (need to be created (included to .gitignore)) with default configuration
```bash
DATABASE_NAME=research
DATABASE_USERNAME=research
DATABASE_PASSWORD=research
DATABASE_HOST=db
DATABASE_PORT=5432

DATABASE_URL="postgresql://${DATABASE_USERNAME}:${DATABASE_PASSWORD}@${DATABASE_HOST}:${DATABASE_PORT}/${DATABASE_NAME}?serverVersion=15&charset=utf8"
```

## Docker


### Configuration file for Docker

All required parameters defined in docker/.env.

The default configuration file:

```bash
DATABASE_NAME=research
DATABASE_USERNAME=research
DATABASE_PASSWORD=research
DATABASE_HOST=db
DATABASE_PORT=5432
```

### Run analyser app

```bash
cd analyser

make up
```

### Stop analyser app

```
make down
```

### Init analyser app

```bash
## enter a container
docker compose exec php sh
## install dependencies
composer install
## apply migrations to database
bin/console d:m:m
```

Application available on http://127.0.0.1:8083/

## Commands

### Init stages
```bash
bin/console a:init:stages
```

### Import data

The data source is configured by default to be stored in a directory {project_root}/data/result.csv

```bash
bin/console a:import
```

### Normalising data for the Job Posting services and Upwork

The data is distributed into two main groups (Upwork and Job Posting Sites (Adzune, Seek, Indeed, Eurures, Catho, HH.ru ...))

```bash
bin/console a:migrate:source:data
bin/console a:migrate:upwork:data
```


### Normalisation of skills
The skills obtained in Upwork are applied to data received from other sources.

```
bin/console a:migrate:upwork:skills
```

# Collector

### Install 

#### ChromeDriver

To run selenium should be installed chromedriver.

https://chromedriver.chromium.org/downloads

#### VirtualEnv

```bash
python -m venv
source venv/bin/activate

pip install -r requirements

```

### Run scripts

Run collecting iteration for Upwork and the Job Posting Services

```bash

cd collector

python scrape.py

```

It makes sense to run 1-2 resources at a time for a data collection

Config for each source location in collector/src/config.py

**Does not require additional settings**

## Scraper

As a result of this command all collected data should be saved in {project_root}/data/result.csv file

```bash

cd collector

python parse.py

```
