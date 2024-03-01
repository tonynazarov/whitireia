<?php

namespace App\Repository;

use App\Entity\JobUpwork;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobUpwork>
 *
 * @method JobUpwork|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobUpwork|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobUpwork[]    findAll()
 * @method JobUpwork[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobUpworkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobUpwork::class);
    }

    public function getIterator(): iterable
    {
        return $this->createQueryBuilder('ju')->getQuery()->toIterable();
    }

    public function save(JobUpwork $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }
}
