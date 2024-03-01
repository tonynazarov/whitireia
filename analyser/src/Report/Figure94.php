<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure94 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 94. The comparison of the number of users mentioned ChatGPT skill in their profile on Upwork and LinkedIn on 13.02.2024.';
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