<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\JobSource;
use App\Entity\JobSourceSkill;
use App\Repository\JobSourceRepository;
use App\Repository\JobSourceSkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'a:migrate:upwork:skills')]
class ExportUpworkSkillsToSourceCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface   $entityManager,
        private readonly JobSourceRepository      $sourceJobRepository,
        private readonly JobSourceSkillRepository $sourceSkillRepository,
    )
    {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '1G');

        $skills = $this->getUpworkSkills();

        $iterator = $this->sourceJobRepository->getIterator();
        $count = $this->sourceJobRepository->count([]);

        foreach ($iterator as $job) {
            $output->writeln((string)$count--);
            \assert($job instanceof JobSource);

            if ($job->isHH()) {
                continue;
            }

            foreach ($skills as $skill) {
                if ($job->hasSkill($skill)) {
                    if ($this->sourceSkillRepository->findOneBy(['jobSource' => $job, 'title' => $skill])) {
                        continue;
                    }

                    $sourceSkill = new JobSourceSkill();
                    $sourceSkill->setJobSource($job);
                    $sourceSkill->setTitle($skill);
                    $sourceSkill->setCreatedAt(new \DateTimeImmutable());
                    $this->entityManager->persist($sourceSkill);
                }

            }

            $this->entityManager->flush();
            $this->entityManager->clear();
        }

        return self::SUCCESS;
    }

    private function getUpworkSkills(): array
    {
        $query = $this->entityManager->getConnection()->createQueryBuilder()
            ->select('distinct title as skill')
            ->from('upwork_job_skills');

        return $query->executeQuery()->fetchFirstColumn();
    }
}
