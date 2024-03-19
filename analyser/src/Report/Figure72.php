<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure72 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 72. The trend of distribution of general skills between vacancies for top 30 skills for Job services.';
    }

    protected function getExcludedKeywords(): array
    {
        return [
            'ChatGPT',
            'C',
            'R',
            'Tool',
            'Website',
            'Action',
            'People',
            'Divi',
            'Review',
            'Driven',
            'Email',
            'Book',
            'Science',
            'Implementation',
        ]
            ;
    }

    protected function getSql(): string
    {
        $sql = <<<EOL
SELECT title, count(DISTINCT source_job_skills.job_source_id) AS count
FROM source_job_skills
WHERE title not in ('{titles}')
GROUP BY title
ORDER BY count DESC
LIMIT 30
EOL;

        return strtr($sql, ['{titles}' => implode("','",$this->getExcludedKeywords())]);
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
                'title'       => [
                    'align'    => 'left',
                    'floating' => false,
                    'text'     => $title,
                ],
            ]
        );
    }

    public function __invoke(): array
    {
        $data  = $this->execute();
        $title = $this->getTitle();

        return [
            'title'   => $title,
            'data'    => $data,
            'options' =>
                self::transformOptions($data, $title)
        ];
    }
}