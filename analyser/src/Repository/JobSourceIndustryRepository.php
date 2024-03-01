<?php

namespace App\Repository;

use App\Entity\JobSourceIndustry;
use App\Entity\SourceJobIndustry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SourceJobIndustry>
 *
 * @method SourceJobIndustry|null find($id, $lockMode = null, $lockVersion = null)
 * @method SourceJobIndustry|null findOneBy(array $criteria, array $orderBy = null)
 * @method SourceJobIndustry[]    findAll()
 * @method SourceJobIndustry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobSourceIndustryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobSourceIndustry::class);
    }
}
