<?php

namespace App\Entity;

use App\Repository\BetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BetRepository::class)
 */
class Bet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $goalsHome;

    /**
     * @ORM\Column(type="smallint")
     */
    private $goalsGuest;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="float", scale=1, nullable=true)
     */
    private $pts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hit = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="bets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGoalsHome(): ?int
    {
        return $this->goalsHome;
    }

    public function setGoalsHome(?int $goalsHome): self
    {
        $this->goalsHome = $goalsHome;

        return $this;
    }

    public function getGoalsGuest(): ?int
    {
        return $this->goalsGuest;
    }

    public function setGoalsGuest(int $goalsGuest): self
    {
        $this->goalsGuest = $goalsGuest;

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

    public function getPts(): ?float
    {
        return $this->pts;
    }

    public function setPts(?float $pts): self
    {
        $this->pts = $pts;

        return $this;
    }

    public function getHit(): ?bool
    {
        return $this->hit;
    }

    public function setHit(bool $hit): self
    {
        $this->hit = $hit;

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

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
