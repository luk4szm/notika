<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Bet::class, mappedBy="game")
     */
    private $bets;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_counted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="smallint")
     */
    private $round;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $goals_home;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $goals_guest;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_awarded;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="gamedAdded")
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="gamesUpdated")
     */
    private $updated_by;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="gamesHome")
     * @ORM\JoinColumn(nullable=false)
     */
    private $home;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="gamesGuest")
     * @ORM\JoinColumn(nullable=false)
     */
    private $guest;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class, inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;

    public function __construct()
    {
        $this->bets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Bet[]
     */
    public function getBets(): Collection
    {
        return $this->bets;
    }

    public function addBet(Bet $bet): self
    {
        if (!$this->bets->contains($bet)) {
            $this->bets[] = $bet;
            $bet->setGame($this);
        }

        return $this;
    }

    public function removeBet(Bet $bet): self
    {
        if ($this->bets->removeElement($bet)) {
            // set the owning side to null (unless already changed)
            if ($bet->getGame() === $this) {
                $bet->setGame(null);
            }
        }

        return $this;
    }

    public function getIsCounted(): ?bool
    {
        return $this->is_counted;
    }

    public function setIsCounted(bool $is_counted): self
    {
        $this->is_counted = $is_counted;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRound(): ?int
    {
        return $this->round;
    }

    public function setRound(int $round): self
    {
        $this->round = $round;

        return $this;
    }

    public function getGoalsHome(): ?int
    {
        return $this->goals_home;
    }

    public function setGoalsHome(?int $goals_home): self
    {
        $this->goals_home = $goals_home;

        return $this;
    }

    public function getGoalsGuest(): ?int
    {
        return $this->goals_guest;
    }

    public function setGoalsGuest(?int $goals_guest): self
    {
        $this->goals_guest = $goals_guest;

        return $this;
    }

    public function getIsAwarded(): ?bool
    {
        return $this->is_awarded;
    }

    public function setIsAwarded(bool $is_awarded): self
    {
        $this->is_awarded = $is_awarded;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updated_by;
    }

    public function setUpdatedBy(?User $updated_by): self
    {
        $this->updated_by = $updated_by;

        return $this;
    }

    public function getHome(): ?Team
    {
        return $this->home;
    }

    public function setHome(?Team $home): self
    {
        $this->home = $home;

        return $this;
    }

    public function getGuest(): ?Team
    {
        return $this->guest;
    }

    public function setGuest(?Team $guest): self
    {
        $this->guest = $guest;

        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }
}
