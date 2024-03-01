<?php

declare(strict_types=1);

namespace App\Report;

readonly class Figure77 extends Figure
{

    protected function group(): array
    {
        return [
            'Software Development'        => [
                'IDE', 'Script', 'Unity', 'Git', 'Bot', 'Software', 'API', 'Python', 'Java', 'SQL', 'Scala', 'iOS', 'Automation', 'Database', 'GIS', 'Testing', 'SaaS', 'JavaScript', 'React', 'HTML', 'Docker', 'Software Development', 'GitHub', 'TypeScript', 'CSS', 'PyTorch', 'Algorithms', 'TensorFlow', 'DevOps', 'Chatbot', 'PHP', 'Kubernetes', 'Server', 'WordPress', 'Search Engine', 'Linux', 'CI/CD', 'MySQL', 'Game', 'Web Service', 'Code Review', 'HubSpot', 'C++', 'Information Technology', 'JSON', 'XML', 'Computer Vision', 'Terraform', 'C#', 'EJS', 'Web Development', 'Web Application', 'Notion', 'Prototype', 'Node.js', 'Microsoft Azure', 'Android', 'Containerization', 'PostgreSQL', 'Angular', 'MongoDB', 'Scripting', 'Ruby', 'Version Control', 'Ecommerce', 'Redis', 'Vue.js', 'Quality Assurance', 'Elasticsearch', 'Open Source', 'MLOps', 'Swift', 'REST API', 'Jenkins', 'Django', 'Laravel', 'Web3', 'pandas', 'GitLab', 'Mobile App', 'Google Cloud Platform', 'Kotlin', 'Next.js', 'Marketing Automation', 'Migration', 'Flask', 'Sass', 'Cloud Computing', 'Jasper', 'HTML5', 'RESTful API', 'Prototyping', 'Ruby on Rails', 'Software Architecture', 'Data Modeling', 'Zapier', 'NumPy', 'GPT API', 'Technical SEO', 'Spring Boot', 'Web Application Development', 'Blockchain', 'Flutter', 'GraphQL', 'NoSQL Database', 'Performance Optimization', 'Software Design', 'Azure App Service', 'A/B Testing', 'API Development', 'Azure DevOps', 'AI Development', 'Robotic Process Automation', 'jQuery', 'Scripting Language'
            ],
            'Business'           => [
                'Business', 'Business Plan', 'Business Intelligence', 'White Paper'
            ],
            'Marketing'          => [
                'Market Research', 'Marketing', 'Strategy', 'Digital Marketing', 'Google Analytics', 'SAS', 'Email Marketing', 'Branding', 'Content Marketing', 'Landing Page', 'Google Ads', 'Customer Engagement', 'Lead Generation', 'Social Media Marketing', 'Mailchimp', 'Email Campaign', 'Marketing Strategy', 'Search Engine Optimization', 'Product Marketing', 'SMM', 'Infographic', 'Google Tag Manager', 'Cold Calling', 'Strategic Plan', 'Intercom'
            ],
            'Communication'      => [
                'Communication Skills', 'Communications', 'Interpersonal Skills', 'Relationship Management', 'Public Relations'
            ],
            'Software'               => [
                'CAD', 'Canva', 'Figma', 'Adobe Photoshop', 'Google', 'Ebook', 'Microsoft Office', 'Google Search', 'Google Workspace', 'Google Docs', 'Microsoft Excel', 'Google Sheets', 'Office 365', 'Google Suite'
            ],
            'Content'            => [
                'Report', 'Writing', 'Blog', 'Article', 'Documentation', 'Content Creation', 'Presentations', 'Copywriting', 'Content Management', 'Video Editing', 'Google Search Console', 'Content Strategy', 'Data Management', 'Content Management System', 'Social Media Content', 'Content Writing', 'Typing', 'Content Development', 'Proofreading', 'Копирайтинг', 'Website Content', 'Technical Writing', 'Narrative', 'Data Collection', 'English', 'Data Processing', 'Japanese', 'Content Calendar', 'Looker', 'Polish', 'Competitive Analysis', 'Text Analytics', 'English', 'German', 'French', 'Spanish', 'Translation', 'Chinese'
            ],
            'Health'             => [
                'Health', 'Healthcare'
            ],
            'Analysis'           => [
                'Analytics', 'Data Analysis', 'Big Data', 'Statistics', 'Data Analytics', 'Data Visualization', 'Data Engineering'
            ],
            'AI'                 => [
                'Generative AI', 'Machine Learning', 'Artificial Intelligence', 'Large Language Model', 'Bard', 'Natural Language Processing', 'Deep Learning', 'LangChain', 'LLaMA', 'BERT', 'DALL-E', 'GitHub Copilot', 'Machine Learning Model', 'GPT-4', 'Conversational AI', 'Stable Diffusion', 'Keras', 'AI Chatbot', 'Neural Network', 'GPT-3'
            ],
            'Sales'              => [
                'Customer Service', 'Sales', 'Customer Experience', 'Salesforce', 'Customer Support', 'Customer Satisfaction', 'Real Estate', 'Shopify'
            ],
            'Design'             => [
                'User Experience', 'Graphic Design', 'Animation'
            ],
            'Education'          => [
                'Education', 'Mathematics', 'Educational', 'Webinar', 'History', 'Reinforcement Learning', 'Training Session', 'Philosophy'
            ],
            'Law'                => [
                'Law',
                'Legal',
            ],
            'Science' => [
                'Computer Science','Data Science','Genetics'
            ],
            'Architecture'       => ['Architecture'],
            'HR'                 => [
                'CV', 'Resume', 'Recruiting', 'Cover Letter', 'Human Resources', 'Job Posting'
            ],
            'Management'         => [
                'Project Management', 'Management Skills', 'Scrum', 'Slack', 'Product Management', 'Jira', 'Business Development', 'Time Management', 'Product Roadmap', 'Process Improvement', 'Project Plans', 'Trello', 'Asana', 'Team Building', 'Team Management', 'Account Management'
            ],
            'Social Networks'    => [
                'LinkedIn', 'Facebook', 'Instagram', 'YouTube', 'Twitter', 'TikTok', 'Social Media Management', 'WhatsApp', 'Podcast', 'Pinterest'
            ],
            'Finance'            => [
                'Finance', 'Financial Planning', 'Economics', 'GAAP', 'Financial Model'
            ],
            'Crypto'             => ['NFT'],
            'Prompt Engineering' => ['Prompt Engineering'],
            'Accounting'         => [
                'Accounting', 'Accounts Payable'
            ],
            'Sports'             => ['Sports'],
            'Journalism'         => ['Journalism'],
            'Support'            => ['Technical Support', 'Zendesk']

        ];

    }

    protected function getTitle(): string
    {
        return 'Figure 77. The distribution of top 300 skills by groups (number of skills) for Job Services.';
    }

    protected function getSql(): string
    {
        return <<<EOL
SELECT title, count(DISTINCT source_job_skills.job_source_id) AS count
FROM source_job_skills
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
                'type' => 'donut',
            ],
            'labels'      => array_keys($data),
            'series'      => array_values($data),
            'title'       => [
                'align'    => 'left',
                'floating' => false,
                'text'     => $title,
            ],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'labels' => [
                            'show'  => true,
                            'name'  => [
                                'show' => true,
                            ],
                            'total' => [
                                'show' => true,
                            ]
                        ]
                    ]
                ]
            ]
        ]);
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