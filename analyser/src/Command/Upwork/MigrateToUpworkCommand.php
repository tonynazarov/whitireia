<?php

declare(strict_types=1);

namespace App\Command\Upwork;

use App\Entity\Job;
use App\Entity\JobUpwork;
use App\Entity\JobUpworkLocation;
use App\Entity\JobUpworkSkill;
use App\Entity\Stage;
use App\Repository\JobRepository;
use App\Repository\JobUpworkLocationsRepository;
use App\Repository\JobUpworkRepository;
use App\Repository\JobUpworkSkillRepository;
use App\Repository\StageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'a:migrate:upwork:data')]
class MigrateToUpworkCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface       $entityManager,
        private readonly JobRepository                $jobsRepository,
        private readonly JobUpworkRepository          $jobUpworkRepository,
        private readonly StageRepository              $stageRepository,
        private readonly JobUpworkSkillRepository     $upworkSkillRepository,
        private readonly JobUpworkLocationsRepository $upworkLocationsRepository,
        string                                        $name = null
    )
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '1G');

        $iterator = $this->jobsRepository->getUpworkIterator('upwork');
        $count    = $this->jobsRepository->count(['source' => 'upwork']);
        $i        = 0;
        /** @var Job $job */
        $this->entityManager->wrapInTransaction(function () use ($iterator, $output, $count, $i) {
            foreach ($iterator as $job) {
                $stage = $this->getStage((int)$job->getStage());

                $output->writeln(strtr('Count: {count}', ['{count}' => $count - ++$i]));
                $upworkJob = $this->getUpworkJob($job, $stage);

                foreach ($job->getExtra()['skills'] ?? [] as $skill) {
                    if (!empty($skill)) {
                        $this->getUpworkSkill($job, $upworkJob, $skill);
                    }
                }

                $locations = str_replace('"', '', explode(',', mb_substr($job->getLocation(), 1, -1)));
                foreach ($locations as $location) {
                    if (!empty($location)) {
                        $this->getUpworkLocation($job, $upworkJob, $location);
                    }
                }

                if ($i % 1000 === 0) {
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                }
            }

            $this->entityManager->flush();
        });

        return 0;
    }

    private function getUpworkLocation(Job $job, JobUpwork $upworkJob, string $location): void
    {
        /** @var JobUpworkLocation $upworkLocation */
        $upworkLocation = $this->upworkLocationsRepository->findOneBy(['jobUpwork' => $upworkJob, 'title' => $location]);

        if ($upworkLocation instanceof JobUpworkLocation) {
            return;
        }

        preg_match('/[A-Za-z ]+/', trim($location), $matches);

        $upworkLocation = new JobUpworkLocation();
        $upworkLocation->setTitle($matches[0]);
        $upworkLocation->setCreatedAt($job->getPostedAt());
        $upworkLocation->setJobUpwork($upworkJob);

        $this->upworkLocationsRepository->save($upworkLocation);
    }

    private function getUpworkSkill(Job $job, JobUpwork $upworkJob, string $skill): void
    {
        /** @var JobUpworkSkill $upworkSkill */
        $upworkSkill = $this->upworkSkillRepository->findOneBy(['jobUpwork' => $upworkJob, 'title' => $skill]);

        if ($upworkSkill instanceof JobUpworkSkill) {
            return;
        }

        $upworkSkill = new JobUpworkSkill();
        $upworkSkill->setTitle($skill);
        $upworkSkill->setCreatedAt($job->getPostedAt());
        $upworkSkill->setJobUpwork($upworkJob);

        $this->upworkSkillRepository->save($upworkSkill);
    }

    private function getUpworkJob(Job $job, Stage $stage): JobUpwork
    {
        /** @var JobUpwork $upworkJob */
        $upworkJob = $this->jobUpworkRepository->findOneBy(['sourceId' => $job->getSourceId()]);

        if ($upworkJob instanceof JobUpwork) {
            return $upworkJob;
        }

        $upworkJob = new JobUpwork();
        $upworkJob->setTitle($job->getTitle());
        $upworkJob->setDescription($job->getDescription());
        $upworkJob->setStage($stage);
        $upworkJob->setLink($job->getLink());
        $upworkJob->setSourceId($job->getSourceId());
        $upworkJob->setPostedAt($job->getPostedAt());

        $this->entityManager->persist($upworkJob);

        return $upworkJob;
    }

    private function getStage(int $stage): Stage
    {
        $stage = $this->stageRepository->findOneBy(['title' => $stage]);

        if ($stage === null) {
            throw new \Exception('Stage not founded');
        }

        return $stage;
    }
}
