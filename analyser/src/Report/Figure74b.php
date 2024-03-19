<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure74b extends Figure
{

    protected function group(): array
    {
        return [
            'Development'      => ['IDE', 'Script', 'Unity', 'Git', 'Bot', 'Software', 'API', 'Python', 'Java', 'SQL', 'Scala', 'iOS'],
            'Business'         => ['Business'],
            'Marketing'        => [
                'Marketing',
                'Strategy',
            ],
            'Communication'    => ['Communication Skills'],
            'Tool'             => [
                'Google',
                'CAD',
            ],
            'Content'          => [
                'Report',
                'Writing',
                'Blog',
                'English',
            ],
            'Health'           => [
                'Health',
            ],
            'Analytics'        => [
                'Analytics'
            ],
            'AI'               => [
                'Generative AI',
                'Machine Learning'
            ],
            'Sales'            => [
                'Sales'
            ],
            'Science' => [
                'Computer Science'
            ],
            'Education'        => [
                'Education'
            ],
            'Law'              => [
                'Law'
            ],
            'Architecture'     => [
                'Architecture'
            ]
        ];
    }

    protected function getTitle(): string
    {
        return 'Figure 74b. The distribution of top 30 skills by groups in quantitative terms (number of jobs) for Job services.';
    }

    protected function getSql(): string
    {
        return <<<EOL
SELECT title, count(DISTINCT source_job_skills.job_source_id) AS count
FROM source_job_skills
WHERE title in ('{titles}')
GROUP BY title
ORDER BY count DESC
LIMIT 30

EOL;

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

    private static function transformToAmountOfSkills(array &$data): void
    {
        foreach ($data as &$value) {
            $value = array_sum($value);
        }
    }

    public function __invoke(): array
    {
        foreach ($this->group() as $name => $group) {
            $sql = strtr($this->getSql(), ['{titles}' => implode("','", $group)]);

            $data[$name] = $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAllKeyValue();
        }

        self::transformToAmountOfSkills($data);
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