<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\StageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StageRepository::class)]
class Stage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $stageDateAt = null;

    #[ORM\OneToMany(mappedBy: 'stage', targetEntity: JobUpwork::class)]
    private Collection $upworkJobs;

    #[ORM\OneToMany(mappedBy: 'stage', targetEntity: JobSource::class)]
    private Collection $sourceJobs;

    public function __construct()
    {
        $this->upworkJobs = new ArrayCollection();
        $this->sourceJobs = new ArrayCollection();
    }

    public function setStageOneDateAt(): void
    {
        $this->stageDateAt = new \DateTimeImmutable('2023-02-10');
    }

    public function getId(): ?int
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

    public function getStageDateAt(): ?\DateTimeImmutable
    {
        return $this->stageDateAt;
    }

    public function setStageDateAt(\DateTimeImmutable $stageDateAt): static
    {
        $this->stageDateAt = $stageDateAt;

        return $this;
    }

    /**
     * @return Collection<int, JobUpwork>
     */
    public function getUpworkJobs(): Collection
    {
        return $this->upworkJobs;
    }

    /**
     * @return Collection<int, JobSource>
     */
    public function getSourceJobs(): Collection
    {
        return $this->sourceJobs;
    }

    public function addSourceJob(JobSource $sourceJob): static
    {
        if (!$this->sourceJobs->contains($sourceJob)) {
            $this->sourceJobs->add($sourceJob);
            $sourceJob->setStage($this);
        }

        return $this;
    }

    public function removeSourceJob(JobSource $sourceJob): static
    {
        if ($this->sourceJobs->removeElement($sourceJob)) {
            // set the owning side to null (unless already changed)
            if ($sourceJob->getStage() === $this) {
                $sourceJob->setStage(null);
            }
        }

        return $this;
    }
}
