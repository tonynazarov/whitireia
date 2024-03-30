<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure88 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 88. The Comparison of the Number of Users Mentioned ChatGPT Skill in Their Profile and the Number of Jobs Related to ChatGPT on the Upwork.';
    }

    protected function getSql(): string
    {
        return "";
    }

    protected static function transformOptions(array $data, string $title): string
    {
        foreach ($data as $stage) {
            $users[] = $stage['users_total'];
            $jobs[] = $stage['jobs_total'];
        }

        return json_encode([
                'chart'       => [
                    'type' => 'bar',
                ],
                'series'      => [
                    [
                        'data' => $users ?? [0]
                    ],
                    [
                        'data' => $jobs ?? [0]
                    ],

                ],
                'legend' => [
                    'show' => false
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
        $data = $this->getEntityManager()->getConnection()->executeQuery('SELECT created_at::date, users_total, jobs_total FROM upwork_users')->fetchAllAssociativeIndexed();

        $title = $this->getTitle();

        return [
            'title'   => $title,
            'data'    => $data,
            'options' =>
                self::transformOptions($data, $title)
        ];
    }
}