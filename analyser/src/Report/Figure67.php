<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure67 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 67. Numbers of vacancies found in three iterations for Upwork.';
    }

    protected function getSql(): string
    {
        $sql = <<<EOL
SELECT s.stage_date_at::date, count(DISTINCT j.id) as number
      FROM {table} as j
               LEFT JOIN stage s on j.stage = s.title::int
      WHERE j.source = '{source}'
      GROUP BY s.stage_date_at
      ORDER BY s.stage_date_at ASC
EOL;

        return strtr($sql, [
            '{table}'  => $this->getJobTable(),
            '{source}' => 'upwork'
        ]);
    }

    protected static function transformOptions(array $data, string $title): string
    {
        return json_encode([
                'chart'       => [
                    'type' => 'bar',
                ],
                'series'      => [
                    [
                        'data' => array_values($data)
                    ]
                ],
                'plotOptions' => [
                    'bar' => [
                        'horizontal' => true,
                    ]
                ],
                'xaxis'       => [
                    'categories' => array_keys($data)
                ],
                'title' => [
                    'align'    => 'left',
                    'floating' => false,
                    'text'     => $title,
                ],
            ]
        );
    }

    public function __invoke(): array
    {
        $data = $this->execute();
        ksort($data);
        $title = $this->getTitle();

        return [
            'title'   => $title,
            'data'    => $data,
            'options' =>
                self::transformOptions($data, $title)
        ];
    }
}