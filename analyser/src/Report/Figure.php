<?php

declare(strict_types=1);

namespace App\Report;

use Doctrine\ORM\EntityManagerInterface;

readonly abstract class Figure
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {

    }

    protected static function transformLabels(array $data): array
    {
        $transformed = [];

        $sources = [
            'cathocombr'     => 'Catho.com.br',
            'seek'           => 'Seek',
            'seek_jobsdb'    => 'Jobsdb',
            'seek_jobstreet' => 'Jobstreet',
            'hhru'           => 'HH.ru',
            'adzuna'         => 'Adzuna',
            'eures'          => 'Eures',
            'jobsora'        => 'Jobsora',
            'upwork'         => 'Upwork',
            'indeed'         => 'Indeed'
        ];

        foreach ($data as $label => $value) {
            $transformed[$sources[$label]] = $value;
        }

        return $transformed;
    }

    abstract protected function getTitle(): string;

    abstract protected function getSql(): string;

    protected function execute(): array
    {
        return $this->getEntityManager()->getConnection()->executeQuery($this->getSql())->fetchAllKeyValue();
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function getJobTable(): string
    {
        return 'jobs';
    }
}