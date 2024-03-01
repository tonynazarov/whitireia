<?php

declare(strict_types=1);

namespace App;

class RegionsEnum
{
    public static array $regions = [
        'Asia'          => ['hk', 'id', 'il', 'tr', 'kr', 'pk', 'kz', 'my', 'jp', 'sg', 'in', 'cn', 'ae', 'vn', 'tw', 'ph', 'th'],
        'Europe'        => ['cs','sv','ua', 'gr', 'it', 'no', 'lu', 'fi', 'hu', 'pt', 'ie', 'se', 'ro', 'cz', 'nl', 'at', 'fr', 'es', 'uk', 'be', 'pl', 'de', 'ru', 'dk',],
        'Oceania'       => ['nz', 'au'],
        'North America' => ['ca', 'us', 'mx', 'cr',],
        'South America' => ['ch', 'br', 'pe', 'cl', 'ar', 'co'],
        'Africa'        => ['za', 'ng', 'ma',],
    ];

    public static function getRegion(string $country): ?string
    {
        foreach (self::$regions as $region => $countries) {
            if (in_array($country, $countries, true)) {
                return $region;
            }
        }

        return null;
    }

    public static array $data = [
        'upwork' => 'upwork'
    ];
}