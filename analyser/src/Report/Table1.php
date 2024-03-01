<?php

declare(strict_types=1);

namespace App\Report;

readonly class Table1 extends Figure
{
    protected function getTitle(): string
    {
        return 'Table 1. The distribution of vacancies mentioning ChatGPT and AI on 02.10.2023.';
    }

    protected function getSql(): string
    {
        $sql = <<<EOL
SELECT source, count(DISTINCT id), stage 
FROM {table} 
WHERE stage=1 
GROUP BY source, stage 
ORDER BY source, stage
EOL;

        return strtr($sql, ['{table}' => $this->getJobTable()]);
    }

    private static function transformData(array &$data, $AiData): void
    {
        foreach ($data as $key => &$value) {
            $ai    = $AiData[$key];
            $value = [
                'ai'          => $ai,
                'gpt'         => $value,
                'percentage' => number_format($value / $ai * 100,2).'%',
            ];
        }
    }

    public function __invoke(): array
    {
        $data = self::transformLabels($this->execute());

        uasort($data, function ($value1, $value2) {
            return $value1 <= $value2;
        });

        self::transformData($data, $this->getAiResults());

        $title = $this->getTitle();

        return [
            'title' => $title,
            'data'  => $data,
        ];
    }

    private function getAiResults(): array
    {
        return [
            'HH.ru'        => 2,
            'Eures'        => 979,
            'Jobsdb'       => 1127,
            'Seek'         => 1191,
            'Jobstreet'    => 2302,
            'Catho.com.br' => 3464,
            'Upwork'       => 5211,
            'Jobsora'      => 152292,
            'Indeed'       => 346708,
            'Adzuna'       => 795287,
        ];

    }
}