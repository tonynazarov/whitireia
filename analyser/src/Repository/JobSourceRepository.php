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

    public function getTitleCountsForLatinHavingGT(string $title, int $gt)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('c', 'c');

        $sql = <<<EOL
SELECT SUM(c.jobs) as c
FROM (
    SELECT title, count(*) jobs FROM source_jobs
    WHERE title ~* '\A[A-Za-z0-9 ]*\Z'
    AND
    title ILIKE ?
    GROUP BY title
    HAVING count(*) > ?) c
EOL;

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, '%' . $title . '%');
        $query->setParameter(2, $gt);

        return $query->getResult();
    }

    public function getTitleCountsForLatin(string $title)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('c', 'c');

        $sql = <<<EOL
SELECT SUM(a.count) as c
FROM (
    SELECT count(*) FROM source_jobs
    WHERE title ~* '\A[A-Za-z0-9 ]*\Z'
    AND
    title ILIKE ? ) a

EOL;

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, '%' . $title . '%');

        return $query->getResult();
    }


    public function getTitleCountsForAll(string $title)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('c', 'c');
        $rsm->addScalarResult('t', 't');

        $sql = <<<EOL
SELECT SUM(a.count) as c
FROM (
    SELECT count(*) FROM source_jobs
    WHERE title ILIKE ? ) a
EOL;

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, '%' . $title . '%');

        return $query->getResult();
    }
}
