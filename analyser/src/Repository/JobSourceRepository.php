<?php

namespace App\Repository;

use App\Entity\JobSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobSource>
 *
 * @method JobSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobSource[]    findAll()
 * @method JobSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobSourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobSource::class);
    }

    public function getIterator(): iterable
    {
        return $this->createQueryBuilder('ju')->getQuery()->toIterable();
    }

    public function getTitleCountsHavingGT(array $titles, int $gt)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('c', 'c');

        $sql = <<<SQL
SELECT SUM(a.c) as c
FROM (
    SELECT count(DISTINCT (source_id)) c FROM jobs
    WHERE 
    lower(title) SIMILAR TO ?
    GROUP BY title
    HAVING count(*) > ?) a
SQL;

        $titles = '%('.implode('|', $titles).')%';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $titles);
        $query->setParameter(2, $gt);

        return $query->getResult();
    }

    public function getTitleCountsForAll(array $titles)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('c', 'c');
        $rsm->addScalarResult('t', 't');

        $sql = <<<SQL
    SELECT count(DISTINCT (source_id)) as c FROM jobs
    WHERE  lower(title) SIMILAR TO ?
SQL;

        $titles = '%('.implode('|', $titles).')%';
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $titles);

        return $query->getResult();
    }

    public function getTitleCountsForLatin(array $titles)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('c', 'c');

        $sql = <<<SQL
SELECT SUM(a.count) as c
FROM (
    SELECT count(*) FROM jobs
    WHERE 
        title ~* '\A[A-Za-z0-9 ]*\Z'
    AND
        lower(title) SIMILAR TO ? ) a

SQL;

        $titles = '%('.implode('|', $titles).')%';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $titles);

        return $query->getResult();
    }
}
