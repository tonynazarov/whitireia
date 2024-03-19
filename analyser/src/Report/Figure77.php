<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure77 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 77. Mention large language models among vacancies in the context of the specified skills (Upwork).';
    }

    protected function getSql(): string
    {
        $sql = <<<EOL
SELECT title as skill, count(job_upwork_id) as related_job_count
FROM upwork_job_skills
WHERE title in
      ('GPT-4', 'GPT-3', 'GPT-3.5', 'LLaMA', 'Llama 2', 'GPT-J', 'LaMDA', 'BERT', 'Claude', 'Falcon 40B', 'BLOOM',
       'Bard', 'GPT-Neo')
GROUP BY skill
ORDER BY related_job_count desc;
EOL;

        return $sql;
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
                'title' => [
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