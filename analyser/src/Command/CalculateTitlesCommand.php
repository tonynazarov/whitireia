<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\JobSourceRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'a:titles')]
class CalculateTitlesCommand extends Command
{
    public function __construct(
        private readonly JobSourceRepository $jobSourceRepository
    )
    {
        parent::__construct();
    }

    protected function execute(
        InputInterface  $input,
        OutputInterface $output,
    ): int
    {

        $titles = [
            'Engineer',
            'Intern',
            'Content',
            'Developer',
            'Manager',
            'Specialist',
            'Expert',
            'Scientist',
            'Coordinator',
            'Executive',
            'Designer',
            'Assistant',
            'Representative',
            'Analyst',
            'Associate',
            'Director',
            'Recruiter',
            'Architect',
            'Marketer',
            'Consultant',
            'Researcher',
            'QA'
        ];

        foreach ($titles as $title) {
            $value                 = $this->jobSourceRepository->getTitleCountsForLatin($title)[0]['c'];
            $data['latin'][$title] = $value;

            echo sprintf('%s, %s' . PHP_EOL, $title, $value);
        }

        foreach ($titles as $title) {
            $value               = $this->jobSourceRepository->getTitleCountsForAll($title)[0]['c'];
            $data['all'][$title] = $value;

            echo sprintf('%s,%s' . PHP_EOL, $title, $value);
        }


        return self::SUCCESS;

    }
}