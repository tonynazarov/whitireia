<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure85 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 85. Mention of AI-related technologies among vacancies in the context of the specified skills (Upwork).';
    }

    protected function getSql(): string
    {
        $sql = <<<EOL
SELECT title as skill, count(DISTINCT job_upwork_id) as related_job_count
FROM upwork_job_skills
WHERE title in
      ('CycleGAN',
            'Machine Learning',
            'ChatGPT Prompt',
            'ChatGPT API Integration',
            'Artificial Intelligence',
            'Natural Language Processing',
            'Prompt Engineering',
            'Artificial Neural Network',
            'Neural Network',
            'Machine Learning Model',
            'OpenAI Embeddings',
            'LLM Prompt Engineering',
            'LLM Prompt',
            'Model Tuning',
            'AI Text-to-Image',
            'AI Text-to-Speech',
            'GPT Chatbot',
            'OpenAI Codex',
            'NLP Tokenization',
            'Generative AI Prompt',
            'Generative AI Prompt Engineering',
            'Prompt Engineering',
            'Stable Diffusion Prompt',
            'OpenAI Codex Prompt',
            'LLM Prompt Engineering',
            'Image Prompt Engineering',
            'LLM Prompt',
            'Midjourney Prompt',
            'Chatbot Prompt',
            'Image Prompt',
            'ChatGPT Prompt',
            'Machine Learning Framework',
            'Machine Translation',
            'Azure Machine Learning',
            'Machine Learning Model',
            'Machine Learning',
            'Generative AI',
            'Generative AI Prompt',
            'Generative AI Prompt Engineering',
            'Generative AI Software',
            'Generative Adversarial Network',
            'Generative Design',
            'Generative Model',
            'Deep Learning',
            'Machine Learning Framework',
            'Apache OpenNLP',
            'CogCompNLP',
            'NLP Tokenization',
            'Stanford CoreNLP')
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