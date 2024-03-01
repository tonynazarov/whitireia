<?php

namespace App\Repository;

use App\Entity\Job;
use App\Entity\Stage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Job>
 *
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    public function getUpworkIterator(string $source): iterable
    {
        return $this->createQueryBuilder('j')
            ->where('j.source=:source')
            ->setParameter('source', $source)
            ->getQuery()
            ->toIterable();
    }

    public function getSourceIterator(array $sources): iterable
    {
        return $this->createQueryBuilder('j')
            ->where('j.source in (:source)')
            ->setParameter('source', $sources, ArrayParameterType::STRING)
            ->getQuery()
            ->toIterable();
    }

    public function findUpworkJobs(Stage $stage): array
    {
        return $this->findBy(['source' => 'upwork', 'stage' => $stage]);
    }
}
