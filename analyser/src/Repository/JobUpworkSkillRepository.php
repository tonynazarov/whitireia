<?php

namespace App\Repository;

use App\Entity\JobUpworkSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobUpworkSkill>
 *
 * @method JobUpworkSkill|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobUpworkSkill|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobUpworkSkill[]    findAll()
 * @method JobUpworkSkill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobUpworkSkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobUpworkSkill::class);
    }

    public function save(JobUpworkSkill $skill): void
    {
        $this->getEntityManager()->persist($skill);
    }
}
