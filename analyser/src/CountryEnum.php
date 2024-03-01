<?php

declare(strict_types=1);

namespace App;

class CountryEnum
{
    public static function getCountry(string $code): string
    {
        return static::$data[$code];
    }

    public static array $data = [
        'ca' => 'Canada',
        'au' => 'Australia',
        'sg' => 'Singapore',
        'nl' => 'Netherlands',
        'in' => 'India',
        'at' => 'Austria',
        'za' => 'South Africa',
        'fr' => 'France',
        'es' => 'Spain',
        'uk' => 'UK',
        'be' => 'Belgium',
        'pl' => 'Poland',
        'ch' => 'Chili',
        'br' => 'Brazil',
        'nz' => 'New Zealand',
        'us' => 'USA',
        'de' => 'Denmark',
        'mx' => 'Mexico',
        'pa' => 'Panama',
        'en' => 'UK',
        'ru' => 'Russia',
        'pe' => 'Peru',
        'cs' => 'Czech Republic',
        'cn' => 'China',
        'cr' => 'Costa Rica',
        'cl' => 'Chile',
        'dk' => 'Denmark',
        'cz' => 'Czech Republic',
        'ae' => 'UAE',
        'vn' => 'Vietnam',
        'ro' => 'Romania',
        'se' => 'Sweden',
        'tw' => 'Taiwan',
        'ie' => 'Ireland',
        'ar' => 'Argentina',
        'ph' => 'Philippines',
        'th' => 'Thailand',
        'pt' => 'Portugal',
        'hu' => 'Hungary',
        'jp' => 'Japan',
        'my' => 'Malaysia',
        'kz' => 'Kazakhstan',
        'pk' => 'Pakistan',
        'kr' => 'Korea',
        'tr' => 'Turkey',
        'fi' => 'Finland',
        'lu' => 'Luxembourg',
        'ng' => 'Nigeria',
        'ma' => 'Morocco',
        'sv' => 'Sweden',
        'co' => 'Columbia',
        'il' => 'Israel',
        'id' => 'Indonesia',
        'no' => 'Norway',
        'hk' => 'Hong Kong',
        'it' => 'Italy',
        'gr' => 'Greece',
        'ua' => 'Ukraine',
        'upwork' => 'upwork'
    ];
}