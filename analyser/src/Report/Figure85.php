<?php

declare(strict_types=1);

namespace App\Report;

use App\Entity\JobSource;

readonly class Figure85 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 85. Distribution of professional areas for titles.';
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
            'Engineer'       => ['engineer'],
            'Intern'         => ['intern'],
            'Content'        => ['data scientists','writer','creator', 'editor', 'content', 'copywriter', 'annotator', 'journalist', 'tekstschrijver'],
            'Developer'      => ['developer', 'front-end', 'frontend', 'full stack', 'full-stack', 'tech lead', 'backend', 'back-end', 'back end'],
            'Manager'        => ['manager', 'mgr', 'head of'],
            'Specialist'     => ['specialist'],
            'Expert'         => ['expert'],
            'Coordinator'    => ['coordinator'],
            'Executive'      => ['executive'],
            'Designer'       => ['designer'],
            'Assistant'      => ['assistant'],
            'Representative' => ['representative'],
            'Analyst'        => ['analista', 'analyst'],
            'Associate'      => ['associate'],
            'Director'       => ['director'],
            'Recruiter'      => ['recruiter'],
            'Architect'      => ['architect'],
            'Marketer'       => ['marketer'],
            'Consultant'     => ['consultant'],
            'Researcher'     => ['researcher'],
            'QA'             => ['qa', 'tester', 'quality assurance'],
        ];

        foreach ($titles as $group => $groupedTitles) {
            $value        = $this->getEntityManager()
                                ->getRepository(JobSource::class)
                                ->getTitleCountsHavingGT($groupedTitles, 3)[0]['c'];
            $data[$group] = (int)$value;
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