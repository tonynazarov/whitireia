<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure83 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 83. The trend of ChatGPT-related skills among the job descriptions for Upwork.';
    }

    protected function getSql(): string
    {
        $sql = <<<EOL
SELECT title as skill, count(DISTINCT job_upwork_id) as related_job_count
FROM upwork_job_skills
WHERE title in
      (
       'Agent GPT',
       'GPT-4 API',
       'GPT-4 Developer',
       'ChatGPT API Integration',
       'GPT-Neo',
       'GPT Chatbot',
       'GPT API',
       'GPT-4',
       'GPT-3',
       'ChatGPT Prompt',
       'GPT-J',
       'Auto-GPT',
       'GPT-3.5'
          )
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
                'title'       => [
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