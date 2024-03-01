<?php

namespace App\Repository;

use App\Entity\JobSourceLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobSourceLocation>
 *
 * @method JobSourceLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobSourceLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobSourceLocation[]    findAll()
 * @method JobSourceLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SourceLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobSourceLocation::class);
    }

//    /**
//     * @return SourceLocation[] Returns an array of SourceLocation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SourceLocation
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
