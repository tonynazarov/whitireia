<?php

declare(strict_types=1);

namespace App\Report;

readonly class Table2 extends Figure
{
    protected function getTitle(): string
    {
        return 'Table 2. The distribution of vacancies mentioning ChatGPT and AI on 01.11.2023.';
    }

    protected function getSql(): string
    {
        $sql = <<<EOL
SELECT source, count(DISTINCT id), stage 
FROM {table} 
WHERE stage=2
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
            'Eures'        => 844,
            'Jobsdb'       => 1157,
            'Seek'         => 2325,
            'Jobstreet'    => 1748,
            'Catho.com.br' => 3945,
            'Upwork'       => 5435,
            'Jobsora'      => 152570,
            'Indeed'       => 203299,
            'Adzuna'       => 891638,
        ];
    }
}