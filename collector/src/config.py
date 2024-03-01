from .sources.adzuna.scraper import Factory as AdzunaFactory
from .sources.cathocombr.scraper import Factory as CathocombrFactory
from .sources.eures.scraper import Factory as EuresFactory
from .sources.hhru.scraper import Factory as HHRuFactory
from .sources.indeed.scraper import Factory as IndeedFactory
from .sources.jobsora.scraper import Factory as JobSoraFactory
from .sources.seek.scraper import Factory as SeekFactory
from .sources.seekjobstreet.scraper import Factory as JobStreetFactory
from .sources.seekjobsdb.scraper import Factory as JobsdbFactory
from .sources.jora.scraper import Factory as JoraFactory
from .sources.upwork.scraper import Factory as UpworkFactory
from .enums import Sources

SOURCES_CONFIG = {
    Sources.SOURCE_UPWORK.value: {
        'source': Sources.SOURCE_UPWORK.value,
        'browser': {
            'arguments': [
                '--blink-settings=imagesEnabled=false',
                '--disable-blink-features=AutomationControlled'

            ],
            'experimental_options': [
                ['excludeSwitches', ["enable-automation"]],
                ['useAutomationExtension', False]
            ]
        },
        'class': UpworkFactory,
        'jobsPerPage': 50,
        'host':
            {
                'country': 'upwork',
                'host': 'https://www.upwork.com'
            },
    },
    Sources.SOURCE_SEEK_JOBSDB.value: {
        'source': Sources.SOURCE_SEEK_JOBSDB.value,
        'browser': {
            'arguments': [
                '--headless=new',
                '--blink-settings=imagesEnabled=false',
                '--disable-blink-features=AutomationControlled'

            ],
            'experimental_options': [
                ['excludeSwitches', ["enable-automation"]],
                ['useAutomationExtension', False]
            ]
        },
        'class': JobsdbFactory,
        'jobsPerPage': 50,
        'hosts': [
            {
                'country': 'th',
                'host': 'https://th.jobsdb.com/th'
            },
            {
                'country': 'hk',
                'host': 'https://hk.jobsdb.com/hk'
            },
        ]
    },
    Sources.SOURCE_SEEK_JOBSTREET.value: {
        'source': Sources.SOURCE_SEEK_JOBSTREET.value,
        'browser': {
            'arguments': [
                '--headless=new',
                '--blink-settings=imagesEnabled=false',
                '--disable-blink-features=AutomationControlled'

            ],
            'experimental_options': [
                ['excludeSwitches', ["enable-automation"]],
                ['useAutomationExtension', False]
            ]
        },
        'class': JobStreetFactory,
        'jobsPerPage': 50,
        'hosts': [
            {
                'country': 'sg',
                'host': 'https://www.jobstreet.com.sg'
            },
            {
                'country': 'ph',
                'host': 'https://www.jobstreet.com.ph'
            },
            {
                'country': 'my',
                'host': 'https://www.jobstreet.com.my'
            },
            {
                'country': 'id',
                'host': 'https://www.jobstreet.co.id'
            },
        ]
    },
    Sources.SOURCE_SEEK.value: {
        'source': Sources.SOURCE_SEEK.value,
        'browser': {
            'arguments': [
                '--headless=new',
                '--blink-settings=imagesEnabled=false',
                '--disable-blink-features=AutomationControlled'

            ],
            'experimental_options': [
                ['excludeSwitches', ["enable-automation"]],
                ['useAutomationExtension', False]
            ]
        },
        'class': SeekFactory,
        'jobsPerPage': 50,
        'hosts': [
            {
                'country': 'nz',
                'host': 'https://www.seek.co.nz'
            },
            {
                'country': 'au',
                'host': 'https://www.seek.com.au'
            },

        ]
    },
    Sources.SOURCE_JORA.value: {
        'source': Sources.SOURCE_JORA.value,
        'browser': {
            'arguments': [
                '--headless=new',
                '--blink-settings=imagesEnabled=false',
                '--disable-blink-features=AutomationControlled'

            ],
            'experimental_options': [
                ['excludeSwitches', ["enable-automation"]],
                ['useAutomationExtension', False]
            ]
        },
        'class': JoraFactory,
        'jobsPerPage': 50,
        'hosts': [
            {
                'country': 'ar',
                'host': 'https://ar.jora.com'
            }, {
                'country': 'au',
                'host': 'https://au.jora.com'
            },
            {
                'country': 'bd',
                'host': 'https://bd.jora.com'
            }, {
                'country': 'br',
                'host': 'https://br.jora.com'
            }, {
                'country': 'ca',
                'host': 'https://ca.jora.com'
            }, {
                'country': 'cl',
                'host': 'https://cl.jora.com'
            }, {
                'country': 'co',
                'host': 'https://co.jora.com'
            }, {
                'country': 'ec',
                'host': 'https://ec.jora.com'
            }, {
                'country': 'fr',
                'host': 'https://fr.jora.com'
            }, {
                'country': 'hk',
                'host': 'https://hk.jora.com'
            },
            {
                'country': 'in',
                'host': 'https://in.jora.com'
            }, {
                'country': 'id',
                'host': 'https://id.jora.com'
            }, {
                'country': 'ie',
                'host': 'https://ie.jora.com'
            }, {
                'country': 'my',
                'host': 'https://my.jora.com'
            }, {
                'country': 'mx',
                'host': 'https://mx.jora.com'
            }, {
                'country': 'nz',
                'host': 'https://nz.jora.com'
            }, {
                'country': 'pe',
                'host': 'https://pe.jora.com'
            }, {
                'country': 'ph',
                'host': 'https://ph.jora.com'
            }, {
                'country': 'pt',
                'host': 'https://pt.jora.com'
            }, {
                'country': 'sg',
                'host': 'https://sg.jora.com'
            }, {
                'country': 'es',
                'host': 'https://es.jora.com'
            }, {
                'country': 'th',
                'host': 'https://th.jora.com'
            },
            {
                'country': 'uk',
                'host': 'https://uk.jora.com'
            },
            {
                'country': 'us',
                'host': 'https://us.jora.com'
            }, {
                'country': 'vn',
                'host': 'https://www.jobstreet.vn'
            }
        ]
    },
    Sources.SOURCE_JOBSORA.value: {
        'source': Sources.SOURCE_JOBSORA.value,
        'browser': {
            'arguments': [
                '--headless=new',
                '--blink-settings=imagesEnabled=false',
                '--disable-blink-features=AutomationControlled'

            ],
            'experimental_options': [
                ['excludeSwitches', ["enable-automation"]],
                ['useAutomationExtension', False]
            ]
        },
        'class': JobSoraFactory,
        'jobsPerPage': 50,
        'hosts': [
            {
                'country': 'pl',
                'host': 'https://pl.jobsora.com/praca'
            }, {
                'country': 'uk',
                'host': 'https://uk.jobsora.com/jobs'
            },
            {
                'country': 'de',
                'host': 'https://de.jobsora.com/jobs'
            },
            {
                'country': 'at',
                'host': 'https://at.jobsora.com/jobs'
            }, {
                'country': 'es',
                'host': 'https://es.jobsora.com/empleos'
            }, {
                'country': 'fr',
                'host': 'https://fr.jobsora.com/emplois'
            }, {
                'country': 'nl',
                'host': 'https://nl.jobsora.com/vacatures'
            }, {
                'country': 'be',
                'host': 'https://be.jobsora.com/emplois'
            },
            {
                'country': 'tr',
                'host': 'https://tr.jobsora.com/iş-ilanları'
            }, {
                'country': 'it',
                'host': 'https://it.jobsora.com/jobs'
            }, {
                'country': 'pt',
                'host': 'https://pt.jobsora.com/empregos'
            }, {
                'country': 'hu',
                'host': 'https://hu.jobsora.com/állások'
            }, {
                'country': 'se',
                'host': 'https://se.jobsora.com/jobb'
            }, {
                'country': 'fi',
                'host': 'https://fi.jobsora.com/työpaikat'
            }, {
                'country': 'ch',
                'host': 'https://ch.jobsora.com/jobs'
            }, {
                'country': 'cz',
                'host': 'https://cz.jobsora.com/práce'
            }, {
                'country': 'ro',
                'host': 'https://ro.jobsora.com/locuri-de-muncă'
            },
            {
                'country': 'bg',
                'host': 'https://bg.jobsora.com/работа'
            }, {
                'country': 'pt',
                'host': 'https://pt.jobsora.com/empregos'
            }, {
                'country': 'za',
                'host': 'https://za.jobsora.com/jobs'
            }, {
                'country': 'ng',
                'host': 'https://ng.jobsora.com/jobs'
            }, {
                'country': 'kz',
                'host': 'https://kz.jobsora.com/работа'
            }, {
                'country': 'in',
                'host': 'https://in.jobsora.com/jobs'
            }, {
                'country': 'ae',
                'host': 'https://ae.jobsora.com/jobs'
            }, {
                'country': 'my',
                'host': 'https://my.jobsora.com/jobs'
            }, {
                'country': 'ph',
                'host': 'https://ph.jobsora.com/jobs'
            }, {
                'country': 'sg',
                'host': 'https://sg.jobsora.com/jobs'
            },
            {
                'country': 'jp',
                'host': 'https://jp.jobsora.com/の求人'
            }, {
                'country': 'us',
                'host': 'https://us.jobsora.com/jobs'
            },
            {
                'country': 'ar',
                'host': 'https://ar.jobsora.com/empleos'
            }, {
                'country': 'mx',
                'host': 'https://mx.jobsora.com/empleos'
            }, {
                'country': 'cl',
                'host': 'https://cl.jobsora.com/empleos'
            }, {
                'country': 'pe',
                'host': 'https://pe.jobsora.com/empleos'
            }, {
                'country': 'br',
                'host': 'https://br.jobsora.com/empregos'
            }, {
                'country': 'ca',
                'host': 'https://ca.jobsora.com/jobs'
            }, {
                'country': 'au',
                'host': 'https://au.jobsora.com/jobs'
            }, {
                'country': 'nz',
                'host': 'https://nz.jobsora.com/jobs'
            },
        ]
    },
    Sources.SOURCE_INDEED.value: {
        'source': Sources.SOURCE_INDEED.value,
        'browser': {
            'arguments': [
                '--blink-settings=imagesEnabled=false',
                '--disable-blink-features=AutomationControlled'

            ],
            'experimental_options': [
                ['excludeSwitches', ["enable-automation"]],
                ['useAutomationExtension', False]
            ]
        },
        'class': IndeedFactory,
        'jobsPerPage': 50,
        'hosts': [
            {
                "country": "ar",
                "host": "https://ar.indeed.com"
            },
            {
                "country": "au",
                "host": "https://au.indeed.com"
            },
            {
                "country": "at",
                "host": "https://at.indeed.com"
            },
            {
                "country": "be",
                "host": "https://be.indeed.com"
            },
            {
                "country": "br",
                "host": "https://br.indeed.com"
            },
            {
                "country": "ca",
                "host": "https://ca.indeed.com"
            },
            {
                "country": "cl",
                "host": "https://cl.indeed.com"
            },
            {
                "country": "cn",
                "host": "https://cn.indeed.com"
            },
            {
                "country": "co",
                "host": "https://co.indeed.com"
            },
            {
                "country": "cr",
                "host": "https://cr.indeed.com"
            },
            {
                "country": "cz",
                "host": "https://cz.indeed.com"
            },
            {
                "country": "dk",
                "host": "https://dk.indeed.com"
            },
            {
                "country": "ec",
                "host": "https://ec.indeed.com"
            },
            {
                "country": "eg",
                "host": "https://eg.indeed.com"
            },
            {
                "country": "fi",
                "host": "https://fi.indeed.com"
            },
            {
                "country": "fr",
                "host": "https://fr.indeed.com"
            },
            {
                "country": "de",
                "host": "https://de.indeed.com"
            },
            {
                "country": "gr",
                "host": "https://gr.indeed.com"
            },
            {
                "country": "hk",
                "host": "https://hk.indeed.com"
            },
            {
                "country": "hu",
                "host": "https://hu.indeed.com"
            },
            {
                "country": "in",
                "host": "https://in.indeed.com"
            },
            {
                "country": "id",
                "host": "https://id.indeed.com"
            },
            {
                "country": "ie",
                "host": "https://ie.indeed.com"
            },
            {
                "country": "il",
                "host": "https://il.indeed.com"
            },
            {
                "country": "it",
                "host": "https://it.indeed.com"
            },
            {
                "country": "jp",
                "host": "https://jp.indeed.com"
            },
            {
                "country": "kw",
                "host": "https://kw.indeed.com"
            },
            {
                "country": "lu",
                "host": "https://lu.indeed.com"
            },
            {
                "country": "my",
                "host": "https://malaysia.indeed.com"
            },
            {
                "country": "mx",
                "host": "https://mx.indeed.com"
            },
            {
                "country": "ma",
                "host": "https://ma.indeed.com"
            },
            {
                "country": "nl",
                "host": "https://nl.indeed.com"
            },
            {
                "country": "nz",
                "host": "https://nz.indeed.com"
            },
            {
                "country": "ng",
                "host": "https://ng.indeed.com"
            },
            {
                "country": "no",
                "host": "https://no.indeed.com"
            },
            {
                "country": "om",
                "host": "https://om.indeed.com"
            },
            {
                "country": "pk",
                "host": "https://pk.indeed.com"
            },
            {
                "country": "pa",
                "host": "https://pa.indeed.com"
            },
            {
                "country": "pe",
                "host": "https://pe.indeed.com"
            },
            {
                "country": "ph",
                "host": "https://ph.indeed.com"
            },
            {
                "country": "pl",
                "host": "https://pl.indeed.com"
            },
            {
                "country": "pt",
                "host": "https://pt.indeed.com"
            },
            {
                "country": "qa",
                "host": "https://qa.indeed.com"
            },
            {
                "country": "ro",
                "host": "https://ro.indeed.com"
            },
            {
                "country": "sa",
                "host": "https://sa.indeed.com"
            },
            {
                "country": "sg",
                "host": "https://sg.indeed.com"
            },
            {
                "country": "za",
                "host": "https://za.indeed.com"
            },
            {
                "country": "kr",
                "host": "https://kr.indeed.com"
            },
            {
                "country": "es",
                "host": "https://es.indeed.com"
            },
            {
                "country": "se",
                "host": "https://se.indeed.com"
            },
            {
                "country": "ch",
                "host": "https://ch.indeed.com"
            },
            {
                "country": "tw",
                "host": "https://tw.indeed.com"
            },
            {
                "country": "th",
                "host": "https://th.indeed.com"
            },
            {
                "country": "tr",
                "host": "https://tr.indeed.com"
            },
            {
                "country": "ua",
                "host": "https://ua.indeed.com"
            },
            {
                "country": "ae",
                "host": "https://ae.indeed.com"
            },
            {
                "country": "uk",
                "host": "https://uk.indeed.com"
            },
            {
                "country": "uy",
                "host": "https://uy.indeed.com"
            },
            {
                "country": "ve",
                "host": "https://ve.indeed.com"
            },
            {
                "country": "vn",
                "host": "https://vn.indeed.com"
            },
            {
                "country": "us",
                "host": "https://www.indeed.com"
            },
        ]
    },
    Sources.SOURCE_HHRU.value: {
        'source': Sources.SOURCE_HHRU.value,
        'browser': {
            'prefs': {
                "webkit.webprefs.javascript_enabled": False,
            },
            'arguments': [
                '--blink-settings=imagesEnabled=false',
                '--disable-blink-features=AutomationControlled'

            ],
            'experimental_options': [
                ['excludeSwitches', ["enable-automation"]],
                ['useAutomationExtension', False]
            ]
        },
        'class': HHRuFactory,
        'jobsPerPage': 50,
        'hosts': [
            {
                'country': 'ru',
                'host': 'https://hh.ru'
            },
        ]
    },
    Sources.SOURCE_EURES.value: {
        'source': Sources.SOURCE_EURES.value,
        'browser': {
            'prefs': {
                "webkit.webprefs.javascript_enabled": False
            },
            'arguments': [
                '--headless=new',
                '--blink-settings=imagesEnabled=false',
                '--disable-blink-features=AutomationControlled'

            ],
            'experimental_options': [
                ['excludeSwitches', ["enable-automation"]],
                ['useAutomationExtension', False]
            ]
        },
        'class': EuresFactory,
        'jobsPerPage': 50,
        'hosts': [
            {
                'country': 'eu',
                'host': 'https://europa.eu'
            },
        ]
    },
    Sources.SOURCE_CATHOCOMBR.value: {
        'source': Sources.SOURCE_CATHOCOMBR.value,
        'browser': {
            'prefs': {
                "webkit.webprefs.javascript_enabled": False,
            },
            'arguments': [
                '--headless=new',
                '--blink-settings=imagesEnabled=false',
                '--disable-blink-features=AutomationControlled'

            ],
            'experimental_options': [
                ['excludeSwitches', ["enable-automation"]],
                ['useAutomationExtension', False]
            ]
        },
        'class': CathocombrFactory,
        'jobsPerPage': 15,
        'hosts': [
            {
                'country': 'br',
                'host': 'https://www.catho.com.br'
            },
        ]
    },
    Sources.SOURCE_ADZUNA.value: {
        'source': Sources.SOURCE_ADZUNA.value,
        'browser': {
            'prefs': {
                "webkit.webprefs.javascript_enabled": False,
            },
            'arguments': [
                '--headless=new',
                '--blink-settings=imagesEnabled=false',
                '--disable-blink-features=AutomationControlled'

            ],
            'experimental_options': [
                ['excludeSwitches', ["enable-automation"]],
                ['useAutomationExtension', False]
            ]
        },
        'class': AdzunaFactory,
        'jobsPerPage': 50,
        'hosts': [
            {
                'country': 'au',
                'host': 'https://www.adzuna.com.au'
            },
            {
                'country': 'ca',
                'host': 'https://www.adzuna.ca'
            },
            {
                'country': 'it',
                'host': 'https://www.adzuna.it'
            }, {
                'country': 'pl',
                'host': 'https://www.adzuna.pl'
            }, {
                'country': 'ch',
                'host': 'https://www.adzuna.ch'
            }, {
                'country': 'at',
                'host': 'https://www.adzuna.at'
            }, {
                'country': 'fr',
                'host': 'https://www.adzuna.fr'
            }, {
                'country': 'mx',
                'host': 'https://www.adzuna.com.mx'
            }, {
                'country': 'sg',
                'host': 'https://www.adzuna.sg'
            },
            {
                'country': 'uk',
                'host': 'https://www.adzuna.co.uk'
            },
            {
                'country': 'be',
                'host': 'https://www.adzuna.be'
            }, {
                'country': 'de',
                'host': 'https://www.adzuna.de'
            }, {
                'country': 'nl',
                'host': 'https://www.adzuna.nl'
            }, {
                'country': 'za',
                'host': 'https://www.adzuna.co.za'
            },
            {
                'country': 'us',
                'host': 'https://www.adzuna.com'
            },
            {
                'country': 'br',
                'host': 'https://adzuna.com.br'
            }, {
                'country': 'in',
                'host': 'https://www.adzuna.in'
            },
            {
                'country': 'nz',
                'host': 'https://www.adzuna.co.nz'
            }, {
                'country': 'es',
                'host': 'https://www.adzuna.es'
            },
        ]
    }
}
