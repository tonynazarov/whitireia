<?php

declare(strict_types=1);

namespace App\Report;

readonly class AppendixE
{

    public function __invoke(): array
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
            'Computer Science' => [
                'Science'
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
}