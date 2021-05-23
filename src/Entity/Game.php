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
     * Time in minutes after which the games is probably is end
     */
    public const GAMETIME = 108;

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
    private $isCounted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    private $endDate;

    /**
     * @ORM\Column(type="smallint")
     */
    private $round;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $goalsHome;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $goalsGuest;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAwarded;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="gamedAdded")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="gamesUpdated")
     */
    private $updatedBy;

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
        return $this->isCounted;
    }

    public function setIsCounted(bool $isCounted): self
    {
        $this->isCounted = $isCounted;

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

    public function getEndDate(): ?\DateTimeInterface
    {
        $this->endDate = new \DateTime($this->getDate()->format("Y-m-d H:i:s"));
        $this->endDate->add(new \DateInterval("PT" . self::GAMETIME . "M"));
        return $this->endDate;
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

    public function setGoalsGuest(?int $goalsGuest): self
    {
        $this->goalsGuest = $goalsGuest;

        return $this;
    }

    public function getIsAwarded(): ?bool
    {
        return $this->isAwarded;
    }

    public function setIsAwarded(bool $isAwarded): self
    {
        $this->isAwarded = $isAwarded;

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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

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
