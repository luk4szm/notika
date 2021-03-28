<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableRepository::class)
 * @ORM\Table(name="`table`")
 */
class Table
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class, inversedBy="tables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="tables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $division;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bracket;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $place;

    /**
     * @ORM\Column(type="integer")
     */
    private $games;

    /**
     * @ORM\Column(type="integer")
     */
    private $won;

    /**
     * @ORM\Column(type="integer")
     */
    private $drawn;

    /**
     * @ORM\Column(type="integer")
     */
    private $lost;

    /**
     * @ORM\Column(type="integer")
     */
    private $goals_for;

    /**
     * @ORM\Column(type="integer")
     */
    private $goals_against;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

    public function __construct()
    {
        $this->team = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Team[]
     */
    public function getTeam(): Collection
    {
        return $this->team;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->team->contains($team)) {
            $this->team[] = $team;
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        $this->team->removeElement($team);

        return $this;
    }

    public function getDivision(): ?string
    {
        return $this->division;
    }

    public function setDivision(?string $division): self
    {
        $this->division = $division;

        return $this;
    }

    public function getBracket(): ?string
    {
        return $this->bracket;
    }

    public function setBracket(?string $bracket): self
    {
        $this->bracket = $bracket;

        return $this;
    }

    public function getPlace(): ?int
    {
        return $this->place;
    }

    public function setPlace(?int $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getGames(): ?int
    {
        return $this->games;
    }

    public function setGames(int $games): self
    {
        $this->games = $games;

        return $this;
    }

    public function getWon(): ?int
    {
        return $this->won;
    }

    public function setWon(int $won): self
    {
        $this->won = $won;

        return $this;
    }

    public function getDrawn(): ?int
    {
        return $this->drawn;
    }

    public function setDrawn(int $drawn): self
    {
        $this->drawn = $drawn;

        return $this;
    }

    public function getLost(): ?int
    {
        return $this->lost;
    }

    public function setLost(int $lost): self
    {
        $this->lost = $lost;

        return $this;
    }

    public function getGoalsFor(): ?int
    {
        return $this->goals_for;
    }

    public function setGoalsFor(int $goals_for): self
    {
        $this->goals_for = $goals_for;

        return $this;
    }

    public function getGoalsAgainst(): ?int
    {
        return $this->goals_against;
    }

    public function setGoalsAgainst(int $goals_against): self
    {
        $this->goals_against = $goals_against;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }
}
