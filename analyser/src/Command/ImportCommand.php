<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(name: 'a:import')]
class ImportCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        string                         $name = null
    )
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '512M');

        $i = 0;
        $unique = [];
        $this->entityManager->wrapInTransaction(function () use ($output, $unique, $i) {
            try {
                $generator = $this->read(__DIR__ . '/../../../data/result.csv');

                foreach ($generator as $row) {
                    if ($i++ === 0) {
                        continue;
                    }

                    $key = md5($row[7] . $row[2] . $row[1]);

                    if (isset($unique[$key])) {
                        continue;
                    }

                    $unique[$key] = true;

                    $job = new Job();
                    $job->setId(Uuid::fromString($row[0]));
                    $job->setSourceId($row[1]);
                    $job->setSource($row[2]);
                    $job->setCountry($row[3]);
                    $job->setTitle($row[4]);
                    $job->setDescription($row[5]);
                    $job->setPostedAt(new \DateTimeImmutable($row[6]));
                    $job->setStage((int)$row[7]);
                    $job->setCompany($row[8]);
                    $job->setSalary($row[9]);
                    $job->setContractType($row[10]);
                    $job->setLocation($row[11]);
                    $job->setEmploymentType($row[12]);
                    $job->setIndustry($row[13]);
                    $job->setLink($row[14]);
                    $job->setExtra(json_decode($row[15], true));

                    $this->entityManager->persist($job);
                    $output->writeln('Persisted: '. $i);

                    if ($i % 1000 === 0) {
                        $output->writeln('Done: ' . $i);
                        $this->entityManager->flush();
                        $this->entityManager->clear();
                    }
                }

                $output->writeln('Done: ' . $i);
                $this->entityManager->flush();

            } catch (\Exception $e) {
                dd($i, $e);
                throw $e;
            }
        });

        $output->writeln('Done: ' . $i);

        return self::SUCCESS;
    }


    private function read(string $filename, string $separator = ','): \Generator|bool
    {
        $handle = fopen($filename, "r");

        if ($handle === false) {
            return;
        }

        while (($data = fgetcsv($handle, 0, $separator)) !== false) {
            yield $data;
        }

        fclose($handle);
    }
}