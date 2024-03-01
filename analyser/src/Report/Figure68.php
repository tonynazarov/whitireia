<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure68 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 68. The comparison of skill selection between source types.';
    }

    protected function getSql(): string
    {
        return <<<EOL
SELECT 'Upwork' as source, count(DISTINCT title)
FROM upwork_job_skills
UNION
SELECT 'Job Services' as source, count(DISTINCT title)
FROM source_job_skills
EOL;
    }

    protected static function transformOptions(array $data, string $title): string
    {
        return json_encode([
                'chart'  => [
                    'type' => 'pie',
                ],
                'series' => array_values($data),
                'labels' => array_keys($data),
                'title'  => [
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
        ksort($data);
        $title = $this->getTitle();

        return [
            'title'   => $title,
            'data'    => $data,
            'options' =>
                self::transformOptions($data, $title)
        ];
    }
}