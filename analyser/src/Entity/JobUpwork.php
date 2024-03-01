<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\JobUpworkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'upwork_jobs')]
#[ORM\UniqueConstraint(name: 'unique_upwork_jobs', fields: ['stage', 'sourceId'])]
#[ORM\Entity(repositoryClass: JobUpworkRepository::class)]
class JobUpwork
{
    public static function getTable(): string
    {
        return 'upwork_jobs';
    }

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sourceId = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $postedAt = null;

    #[ORM\Column(type: 'text')]
    private ?string $link = null;

    #[ORM\ManyToOne(inversedBy: 'upworkJob')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stage $stage = null;

    #[ORM\OneToMany(mappedBy: 'upworkJobId', targetEntity: JobUpworkSkill::class)]
    private Collection $upworkSkills;

    #[ORM\OneToMany(mappedBy: 'upworkJob', targetEntity: JobUpworkLocation::class)]
    private Collection $upworkLocations;

    public function __construct()
    {
        $this->upworkSkills = new ArrayCollection();
        $this->upworkLocations = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): void
    {
        $this->id = $id;
    }

    public function getSourceId(): ?string
    {
        return $this->sourceId;
    }

    public function setSourceId(string $sourceId): static
    {
        $this->sourceId = $sourceId;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPostedAt(): ?\DateTimeImmutable
    {
        return $this->postedAt;
    }

    public function setPostedAt(\DateTimeImmutable $postedAt): static
    {
        $this->postedAt = $postedAt;

        return $this;
    }

    public function getStage(): Stage
    {
        return $this->stage;
    }

    public function setStage(Stage $stage): static
    {
        $this->stage = $stage;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return Collection<int, JobUpworkSkill>
     */
    public function getUpworkSkills(): Collection
    {
        return $this->upworkSkills;
    }

    public function addUpworkSkill(JobUpworkSkill $upworkSkill): static
    {
        if (!$this->upworkSkills->contains($upworkSkill)) {
            $this->upworkSkills->add($upworkSkill);
            $upworkSkill->setJobUpwork($this);
        }

        return $this;
    }

    public function removeUpworkSkill(JobUpworkSkill $upworkSkill): static
    {
        if ($this->upworkSkills->removeElement($upworkSkill)) {
            // set the owning side to null (unless already changed)
            if ($upworkSkill->getJobUpwork() === $this) {
                $upworkSkill->setJobUpwork(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, JobUpworkLocation>
     */
    public function getUpworkLocations(): Collection
    {
        return $this->upworkLocations;
    }

    public function addUpworkLocation(JobUpworkLocation $upworkLocation): static
    {
        if (!$this->upworkLocations->contains($upworkLocation)) {
            $this->upworkLocations->add($upworkLocation);
            $upworkLocation->setUpworkJob($this);
        }

        return $this;
    }

    public function removeUpworkLocation(JobUpworkLocation $upworkLocation): static
    {
        if ($this->upworkLocations->removeElement($upworkLocation)) {
            // set the owning side to null (unless already changed)
            if ($upworkLocation->getUpworkJob() === $this) {
                $upworkLocation->setUpworkJob(null);
            }
        }

        return $this;
    }
}
