<?php

declare(strict_types=1);

namespace App\Report;

readonly class AppendixD
{

    public function __invoke(): array
    {
        return [
            'Content'        => [
                'Content writing', 'English', 'Writing', 'Article writing', 'Blog content', 'Data entry', 'Copywriting', 'Blog writing', 'SEO writing', 'Creative writing', 'Email communication', 'Article'
            ],
            'Development'    => [
                'Python', 'API', 'JavaScript', 'ChatGPT API Integration', 'Web Development', 'API Integration', 'Automation', 'Chatbot Development', 'Wordpress', 'AI', 'Artificial Intelligence', 'Machine Learning'
            ],
            'Assistance'     => [
                'Virtual Assistance'
            ],
            'Marketing'      => [
                'Social Media Management', 'Search Engine Optimisation', 'Social Media Marketing'
            ],
            'Administrative' => [
                'Administrative Support'
            ],
            'Design'         => [
                'Web Design', 'Graphic Design'
            ]
        ];
    }
}