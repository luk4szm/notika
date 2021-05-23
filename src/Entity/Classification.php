<?php

namespace App\Entity;

use App\Repository\ClassificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClassificationRepository::class)
 */
class Classification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $pts = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $hits = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $typedRounds = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $typedGames = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="classifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Ranking::class, inversedBy="classifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ranking;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPts(): ?float
    {
        return $this->pts;
    }

    public function setPts(float $pts): self
    {
        $this->pts = $pts;

        return $this;
    }

    public function addPts(float $pts): self
    {
        $this->pts = $this->getPts() + $pts;

        return $this;
    }

    public function getHits(): ?int
    {
        return $this->hits;
    }

    public function setHits(int $hits): self
    {
        $this->hits = $hits;

        return $this;
    }

    public function increaseHit(): self
    {
        $this->hits++;

        return $this;
    }

    public function getTypedRounds(): ?int
    {
        return $this->typedRounds;
    }

    public function setTypedRounds(int $typedRounds): self
    {
        $this->typedRounds = $typedRounds;

        return $this;
    }

    public function increaseTypedRounds(): self
    {
        $this->typedRounds++;

        return $this;
    }

    public function getTypedGames(): ?int
    {
        return $this->typedGames;
    }

    public function setTypedGames(int $typedGames): self
    {
        $this->typedGames = $typedGames;

        return $this;
    }

    public function increaseTypedGames(): self
    {
        $this->typedGames++;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
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

    public function getRanking(): ?Ranking
    {
        return $this->ranking;
    }

    public function setRanking(?Ranking $ranking): self
    {
        $this->ranking = $ranking;

        return $this;
    }
}
