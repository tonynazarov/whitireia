<?php

namespace App\Repository;

use App\Entity\JobSourceCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobSourceCompany>
 *
 * @method JobSourceCompany|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobSourceCompany|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobSourceCompany[]    findAll()
 * @method JobSourceCompany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobSourceCompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobSourceCompany::class);
    }

//    /**
//     * @return JobSourceCompany[] Returns an array of JobSourceCompany objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?JobSourceCompany
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
