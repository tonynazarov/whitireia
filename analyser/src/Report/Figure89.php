<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure89 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 89. The Comparison of the Number of Users Mentioned ChatGPT as a Skill in Their Profile on Upwork and LinkedIn (13.02.2024)';
    }

    protected function getSql(): string
    {
        return '';
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
        $data = [
            'Upwork'   => 35670,
            'LinkedIn' => 154000,
        ];

        $title = $this->getTitle();

        return [
            'title'   => $title,
            'data'    => $data,
            'options' =>
                self::transformOptions($data, $title)
        ];
    }
}