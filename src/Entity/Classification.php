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
    private $pts;

    /**
     * @ORM\Column(type="integer")
     */
    private $hit;

    /**
     * @ORM\Column(type="integer")
     */
    private $typedRounds;

    /**
     * @ORM\Column(type="integer")
     */
    private $typedGames;

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

    public function getHit(): ?int
    {
        return $this->hit;
    }

    public function setHit(int $hit): self
    {
        $this->hit = $hit;

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

    public function getTypedGames(): ?int
    {
        return $this->typedGames;
    }

    public function setTypedGames(int $typedGames): self
    {
        $this->typedGames = $typedGames;

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
}