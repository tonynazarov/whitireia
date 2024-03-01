<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure69 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 69. Numbers of mentions of the 30 most popular skills on Upwork.';
    }

    protected function getSql(): string
    {
        return <<<EOL
SELECT title, count(DISTINCT upwork_job_skills.job_upwork_id) AS count
FROM upwork_job_skills
WHERE title!='ChatGPT'
GROUP BY title
ORDER BY count DESC
LIMIT 30

EOL;
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
        $data = $this->execute();
        $title = $this->getTitle();

        return [
            'title'   => $title,
            'data'    => $data,
            'options' =>
                self::transformOptions($data, $title)
        ];
    }
}