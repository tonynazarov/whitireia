<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\JobUpworkSkillRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'upwork_job_skills')]
#[ORM\Entity(repositoryClass: JobUpworkSkillRepository::class)]
class JobUpworkSkill
{
    public static function getTable(): string
    {
        return 'upwork_job_skills';
    }

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'upworkSkills')]
    #[ORM\JoinColumn(nullable: false)]
    private ?JobUpwork $jobUpwork = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getJobUpwork(): ?JobUpwork
    {
        return $this->jobUpwork;
    }

    public function setJobUpwork(?JobUpwork $jobUpwork): static
    {
        $this->jobUpwork = $jobUpwork;

        return $this;
    }
}
