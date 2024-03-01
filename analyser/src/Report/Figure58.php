<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure58 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 58. An example of the distribution of vacancies from the sample by data sources in numerical equivalent.';
    }

    protected function getSql(): string
    {
        $sql = <<<EOL
SELECT source,count(DISTINCT id) as count
FROM {table}
GROUP BY source
EOL;

        return strtr($sql, ['{table}' => $this->getJobTable()]);
    }

    protected static function transformOptions(array $data, string $title): string
    {
        return json_encode([
            'chart'       => [
                'type' => 'donut',
            ],
            'labels'      => array_keys($data),
            'series'      => array_values($data),
            'title'       => [
                'align'    => 'left',
                'floating' => false,
                'text'     => $title,
            ],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'labels' => [
                            'show'  => true,
                            'name'  => [
                                'show' => true,
                            ],
                            'total' => [
                                'show' => true,
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function __invoke(): array
    {
        $data = self::transformLabels($this->execute());

        uasort($data, function ($value1, $value2) {
            return $value1 <= $value2;
        });

        $title = $this->getTitle();

        return [
            'title'   => $title,
            'data'    => $data,
            'options' => self::transformOptions($data, $title)
        ];
    }
}