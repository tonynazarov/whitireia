<?php

namespace App\Repository;

use App\Entity\JobUpworkLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobUpworkLocation>
 *
 * @method JobUpworkLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobUpworkLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobUpworkLocation[]    findAll()
 * @method JobUpworkLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobUpworkLocationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobUpworkLocation::class);
    }

    public function save($entity): void
    {
        $this->getEntityManager()->persist($entity);
    }
}
