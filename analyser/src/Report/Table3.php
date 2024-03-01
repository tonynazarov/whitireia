<?php

declare(strict_types=1);

namespace App\Report;

readonly class Table3 extends Figure
{
    protected function getTitle(): string
    {
        return 'Table 3. The distribution of vacancies mentioning ChatGPT and AI on 01.12.2023.';
    }

    protected function getSql(): string
    {
        $sql = <<<EOL
SELECT source, count(DISTINCT id), stage 
FROM {table} 
WHERE stage=3
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
            'Eures'        => 720,
            'Jobsdb'       => 954,
            'Seek'         => 967,
            'Jobstreet'    => 1926,
            'Catho.com.br' => 3607,
            'Upwork'       => 5612,
            'Jobsora'      => 154143,
            'Indeed'       => 193241,
            'Adzuna'       => 835116,
        ];

    }
}