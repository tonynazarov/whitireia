<?php

declare(strict_types=1);

namespace App\Report;

use App\TitleGroups;

readonly class Figure70 extends Figure
{

    protected function group(): array
    {
        return TitleGroups::UPWORK_GROUPS;
    }

    protected function getTitle(): string
    {
        return 'Figure 70. Distribution of skills of top 300 among the groups (number of skills in a group) for Upwork.';
    }

    protected function getSql(): string
    {
        return <<<EOL
SELECT title, count(DISTINCT upwork_job_skills.job_upwork_id) AS count
FROM upwork_job_skills
WHERE title in ('{titles}')
GROUP BY title
ORDER BY count DESC
LIMIT 300

EOL;

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
                'title'       => [
                    'align'    => 'left',
                    'floating' => false,
                    'text'     => $title,
                ],
            ]
        );
    }

    private static function transformToAmountOfSkills(array &$data): void
    {
        foreach ($data as &$value) {
            $value = count($value);
        }
    }

    public function __invoke(): array
    {

        foreach ($this->group() as $name => $group) {
            $sql = strtr($this->getSql(), ['{titles}' => implode("','", $group)]);

            $data[$name] = $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAllKeyValue();
        }

        self::transformToAmountOfSkills($data);
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