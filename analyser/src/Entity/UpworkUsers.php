<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UpworkUsersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UpworkUsersRepository::class)]
class UpworkUsers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255)]
    private ?string $usersTotal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $jobsTotal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUsersTotal(): ?string
    {
        return $this->usersTotal;
    }

    public function setUsersTotal(string $usersTotal): static
    {
        $this->usersTotal = $usersTotal;

        return $this;
    }

    public function getJobsTotal(): ?string
    {
        return $this->jobsTotal;
    }
}
