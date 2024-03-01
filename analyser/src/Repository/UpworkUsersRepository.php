<?php

namespace App\Repository;

use App\Entity\UpworkUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UpworkUsers>
 *
 * @method UpworkUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method UpworkUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method UpworkUsers[]    findAll()
 * @method UpworkUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UpworkUsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UpworkUsers::class);
    }

//    /**
//     * @return UpworkUsers[] Returns an array of UpworkUsers objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UpworkUsers
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
