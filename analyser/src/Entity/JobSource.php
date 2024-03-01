<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\JobSourceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'source_jobs')]
#[ORM\Entity(repositoryClass: JobSourceRepository::class)]
class JobSource
{
    public static function getTable(): string
    {
        return 'source_jobs';
    }

    private const SOURCE_ADZUNA = 'adzuna';
    private const SOURCE_CATHOCOMBR = 'cathocombr';
    private const SOURCE_EURES = 'eures';
    public const SOURCE_HHRU = 'hhru';
    private const SOURCE_INDEED = 'indeed';
    private const SOURCE_JOBSORA = 'jobsora';
    private const SOURCE_SEEK = 'seek';
    private const SOURCE_SEEK_JOBSTREET = 'seek_jobstreet';
    private const SOURCE_SEEK_JOBSDB = 'seek_jobsdb';
    private const SOURCE_UPWORK = 'upwork';

    public static function getSources(): array
    {
        return [
            self::SOURCE_ADZUNA,
            self::SOURCE_HHRU,
            self::SOURCE_EURES,
            self::SOURCE_CATHOCOMBR,
            self::SOURCE_INDEED,
            self::SOURCE_JOBSORA,
            self::SOURCE_SEEK,
            self::SOURCE_SEEK_JOBSTREET,
            self::SOURCE_SEEK_JOBSDB,

        ];
    }
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'sourceJobs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stage $stage = null;

    #[ORM\Column(length: 255)]
    private ?string $sourceId = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $posted_at = null;

    #[ORM\Column(type: 'text')]
    private ?string $link = null;

    #[ORM\Column(length: 255)]
    private ?string $source = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $employmentType = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): void
    {
        $this->id = $id;
    }

    public function getStage(): ?Stage
    {
        return $this->stage;
    }

    public function setStage(?Stage $stage): static
    {
        $this->stage = $stage;

        return $this;
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
        return $this->posted_at;
    }

    public function setPostedAt(?\DateTimeImmutable $posted_at): static
    {
        $this->posted_at = $posted_at;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getEmploymentType(): ?string
    {
        return $this->employmentType;
    }

    public function setEmploymentType(?string $employmentType): static
    {
        $this->employmentType = $employmentType;

        return $this;
    }

    public function isHH(): bool
    {
        return $this->source === self::SOURCE_HHRU;
    }

    public function hasSkill(string $skill): bool
    {
        $hasSkillInTitle = mb_stripos($this->title, $skill) !== false;
        $hasSkillInDescription = mb_stripos($this->description, $skill) !== false;

        return $hasSkillInDescription || $hasSkillInTitle;
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
