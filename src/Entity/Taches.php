<?php

namespace App\Entity;

use App\Repository\TachesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TachesRepository::class)]
class Taches
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'taches')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'leadProjectTaches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $leaderProject = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: "string", length: 100)] 
    private ?string $code = null;

    #[ORM\Column(type: "text")]
    private ?string $description = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $beginDate = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $estimateEndDate = null;

    #[ORM\Column(type: "boolean")]
    private bool $isFinished = false;

    #[ORM\Column(type: "boolean")]
    private bool $isSuccess = false;

    // Getters et setters...
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getLeaderProject(): ?User
    {
        return $this->leaderProject;
    }

    public function setLeaderProject(?User $leaderProject): static
    {
        $this->leaderProject = $leaderProject;
        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getBeginDate(): ?\DateTimeInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(?\DateTimeInterface $beginDate): self
    {
        $this->beginDate = $beginDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getEstimateEndDate(): ?\DateTimeInterface
    {
        return $this->estimateEndDate;
    }

    public function setEstimateEndDate(?\DateTimeInterface $estimateEndDate): self
    {
        $this->estimateEndDate = $estimateEndDate;
        return $this;
    }

    public function getIsFinished(): bool
    {
        return $this->isFinished;
    }

    public function setIsFinished(bool $isFinished): self
    {
        $this->isFinished = $isFinished;
        return $this;
    }

    public function getIsSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function setIsSuccess(bool $isSuccess): self
    {
        $this->isSuccess = $isSuccess;
        return $this;
    }
}
