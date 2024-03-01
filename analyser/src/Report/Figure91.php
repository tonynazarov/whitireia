<?php

declare(strict_types=1);

namespace App\Report;

use App\Entity\JobSource;

readonly class Figure91 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 91. Distribution of vacancies by job keywords for titles without additional filters.';
    }

    protected function getSql(): string
    {
        return '';
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
        $titles = [
            'Engineer',
            'Intern',
            'Content',
            'Developer',
            'Manager',
            'Specialist',
            'Expert',
            'Scientist',
            'Coordinator',
            'Executive',
            'Designer',
            'Assistant',
            'Representative',
            'Analyst',
            'Associate',
            'Director',
            'Recruiter',
            'Architect',
            'Marketer',
            'Consultant',
            'Researcher',
            'QA'
        ];

        foreach ($titles as $title) {
            $value        = $this->getEntityManager()->getRepository(JobSource::class)->getTitleCountsForAll($title, 3)[0]['c'];
            $data[$title] = (int)$value;
        }

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