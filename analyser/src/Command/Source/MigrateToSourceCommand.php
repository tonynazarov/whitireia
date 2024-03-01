<?php

declare(strict_types=1);

namespace App\Command\Source;

use App\CountryEnum;
use App\Entity\Job;
use App\Entity\JobSource;
use App\Entity\JobSourceIndustry;
use App\Entity\JobSourceSkill;
use App\Entity\Stage;
use App\Repository\JobRepository;
use App\Repository\JobSourceRepository;
use App\Repository\StageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'a:migrate:source:data')]
class MigrateToSourceCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly JobRepository          $jobsRepository,
        private readonly JobSourceRepository    $jobSourceRepository,
        private readonly StageRepository        $stageRepository,
        string                                  $name = null
    )
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '1G');

        $iterator = $this->jobsRepository->getSourceIterator(JobSource::getSources());
        $count    = $this->jobsRepository->count(['source' => JobSource::getSources()]);
        $i        = 0;

        $this->entityManager->wrapInTransaction(function () use ($iterator, $output, $count, $i) {
            /** @var Job $job */
            foreach ($iterator as $job) {
                $stage = $this->getStage((int)$job->getStage());

                $output->writeln(strtr('Count: {count}', ['{count}' => $count - ++$i]));


                try {
                    $sourceJob = $this->getSourceJob($job, $stage);
                } catch (\Exception $e) {
                    continue;
                }

                $this->handleIndustries($job, $sourceJob);
                $this->handleHHruExtra($job, $sourceJob);


                if ($i % 1000 === 0) {
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                }
            }

            $this->entityManager->flush();
        });

        return 0;
    }

    private function handleHHruExtra(Job $job, JobSource $jobSource): void
    {
        if ($job->isHH() === false) {
            return;
        }

        if (empty($job->getExtra())) {
            return;
        }

        foreach ($job->getExtra() as $skill) {
            $sourceSkill = new JobSourceSkill();
            $sourceSkill->setJobSource($jobSource);
            $sourceSkill->setTitle($skill);
            $sourceSkill->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($sourceSkill);
        }
    }

    private function handleIndustries(Job $job, JobSource $jobSource): void
    {
        $industries = [];
        if ($job->getIndustry()) {
            preg_match_all('/\w+/',$job->getIndustry(), $industries);

            if (empty($industries[0])) {
                return;
            }

            $industries = $industries[0];
        }

        foreach ($industries as $industry) {
            if (in_array($industry, ['&', 'i', 'y', 'en', 'time', 'vagas', 'et', 'voor', 'aus', 'e', 'de', 'em', 'tiendas'], true)) {
                continue;
            }

            $newIndustry = new JobSourceIndustry();
            $newIndustry->setTitle($industry);
            $newIndustry->setJobSource($jobSource);
            $newIndustry->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($newIndustry);
        }
    }

    private function getSourceJob(Job $job, Stage $stage): JobSource
    {
        /** @var JobSource $jobSource */
        $jobSource = $this->jobSourceRepository->findOneBy(['sourceId' => $job->getSourceId()]);

        if ($jobSource instanceof JobSource) {
            return $jobSource;
        }

        $keyword                 = 'chatgpt';
        $hasKeywordInDescription = mb_stripos($job->getDescription(), $keyword);
        $hasKeywordInTitle       = mb_stripos($job->getTitle(), $keyword);

        if ($hasKeywordInDescription === false && $hasKeywordInTitle === false) {
            throw new \Exception(sprintf('Has no keyword related to %s', $keyword));
        }

        $jobSource = new JobSource();
        $jobSource->setTitle($job->getTitle());
        $jobSource->setDescription($job->getDescription());
        $jobSource->setCountry(CountryEnum::getCountry($job->getCountry()));
        $jobSource->setStage($stage);
        $jobSource->setLink($job->getLink());
        $jobSource->setSourceId($job->getSourceId());
        $jobSource->setSource($job->getSource());
        $jobSource->setPostedAt($job->getPostedAt());
        $jobSource->setEmploymentType($job->getEmploymentType());
        $jobSource->setCompany($job->getCompany());

        $this->entityManager->persist($jobSource);

        return $jobSource;
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
