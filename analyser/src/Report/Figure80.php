<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure80 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 80. The distribution of ChatGPT mentions vacancies from countries on Job Sources.';
    }

    protected function getSql(): string
    {
        $sql = <<<EOL
SELECT country, count(DISTINCT source_id) as count
FROM source_jobs
GROUP BY country
ORDER BY count desc
LIMIT 30
EOL;

        return $sql;
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
                        'horizontal' => false,
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
        arsort($data);
        $title = $this->getTitle();

        return [
            'title'   => $title,
            'data'    => $data,
            'options' =>
                self::transformOptions($data, $title)
        ];
    }
}