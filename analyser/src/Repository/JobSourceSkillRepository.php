<?php

namespace App\Repository;

use App\Entity\JobSourceSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobSourceSkill>
 *
 * @method JobSourceSkill|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobSourceSkill|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobSourceSkill[]    findAll()
 * @method JobSourceSkill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobSourceSkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobSourceSkill::class);
    }
}
