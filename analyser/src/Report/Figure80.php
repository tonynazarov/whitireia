<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure80 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 80. Mention of Programming Languages among vacancies in the context of the specified skills (Job services).';
    }

    protected function getSql(): string
    {
        $sql = <<<EOL
SELECT title as skill, count(DISTINCT job_source_id) as related_job_count
FROM source_job_skills
WHERE title in
      ('Python', 'PHP', 'TypeScript', 'JavaScript', 'Java', 'C#', 'C++', 'VisualBasic', 'SQL', 'Scratch', 'Go',
       'Fortran', 'Delphi', 'Pascal', 'Assembly', 'Swift', 'Kotlin', 'Ruby', 'Rust', 'COBOL', 'Kotlin', 'MATLAB')
GROUP BY skill
ORDER BY related_job_count desc
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