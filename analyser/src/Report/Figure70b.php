<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure70b extends Figure
{

    protected function group(): array
    {
        return [
            'Content'        => [
                'Content Writing',
                'Writing',
                'Article Writing',
                'Blog Content',
                'Data Entry',
                'Copywriting',
                'Blog Writing',
                'Creative Writing',
                'Email Communication',
                'Article',
                'English',
                'SEO Writing',
            ],
            'Development'    => [
                'Python',
                'JavaScript',
                'Web Development',
                'WordPress',
                'Automation',
                'Chatbot Development',
                'API',
                'ChatGPT API Integration',
                'API Integration',
            ],
            'AI'             => [
                'Artificial Intelligence',
                'Machine Learning',
            ],
            'Assistance'     => [
                'Virtual Assistance'
            ],
            'Administrative' => [
                'Administrative Support'
            ],
            'Marketing'      => [
                'Search Engine Optimization',
                'Social Media Marketing',
                'Social Media Management',
            ],
            'Design'         => [
                'Graphic Design',
                'Web Design',
            ]
        ];

    }

    protected function getTitle(): string
    {
        return 'Figure 70b. Distribution of top 30 skills among the groups (number of skills in a group) for Upwork.';
    }

    protected function getSql(): string
    {
        return <<<EOL
SELECT title, count(DISTINCT upwork_job_skills.job_upwork_id) AS count
FROM upwork_job_skills
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
            $value = count($value);
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