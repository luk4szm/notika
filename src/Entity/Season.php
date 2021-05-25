<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=SeasonRepository::class)
 */
class Season
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=League::class, inversedBy="seasons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $league;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortName;

     /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="smallint")
     */
    private $year;

    /**
     * @ORM\Column(type="smallint")
     */
    private $teamsCount;

    /**
     * @ORM\Column(type="smallint")
     */
    private $roundsCount;

    /**
     * @ORM\Column(type="smallint")
     */
    private $roundGames;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $groupGames;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="season")
     */
    private $games;

    /**
     * @ORM\OneToMany(targetEntity=Table::class, mappedBy="season")
     */
    private $tables;

    /**
     * @ORM\OneToMany(targetEntity=Ranking::class, mappedBy="season")
     */
    private $rankings;

    /**
     * @ORM\OneToMany(targetEntity=Round::class, mappedBy="season", orphanRemoval=true)
     */
    private $rounds;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->tables = new ArrayCollection();
        $this->rankings = new ArrayCollection();
        $this->rounds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeague(): ?League
    {
        return $this->league;
    }

    public function setLeague(?League $league): self
    {
        $this->league = $league;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getTeamsCount(): ?int
    {
        return $this->teamsCount;
    }

    public function setTeamsCount(int $teamsCount): self
    {
        $this->teamsCount = $teamsCount;

        return $this;
    }

    public function getRoundsCount(): ?int
    {
        return $this->roundsCount;
    }

    public function setRoundsCount(int $roundsCount): self
    {
        $this->roundsCount = $roundsCount;

        return $this;
    }

    public function getRoundGames(): ?int
    {
        return $this->roundGames;
    }

    public function setRoundGames(int $roundGames): self
    {
        $this->roundGames = $roundGames;

        return $this;
    }

    public function getGroupGames(): ?int
    {
        return $this->groupGames;
    }

    public function setGroupGames(?int $groupGames): self
    {
        $this->groupGames = $groupGames;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setSeason($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getSeason() === $this) {
                $game->setSeason(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Table[]
     */
    public function getTables(): Collection
    {
        return $this->tables;
    }

    public function addTable(Table $table): self
    {
        if (!$this->tables->contains($table)) {
            $this->tables[] = $table;
            $table->setSeason($this);
        }

        return $this;
    }

    public function removeTable(Table $table): self
    {
        if ($this->tables->removeElement($table)) {
            // set the owning side to null (unless already changed)
            if ($table->getSeason() === $this) {
                $table->setSeason(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ranking[]
     */
    public function getRankings(): Collection
    {
        return $this->rankings;
    }

    public function addRanking(Ranking $ranking): self
    {
        if (!$this->rankings->contains($ranking)) {
            $this->rankings[] = $ranking;
            $ranking->setSeason($this);
        }

        return $this;
    }

    public function removeRanking(Ranking $ranking): self
    {
        if ($this->rankings->removeElement($ranking)) {
            // set the owning side to null (unless already changed)
            if ($ranking->getSeason() === $this) {
                $ranking->setSeason(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Round[]
     */
    public function getRounds(): Collection
    {
        return $this->rounds;
    }

    public function addRound(Round $round): self
    {
        if (!$this->rounds->contains($round)) {
            $this->rounds[] = $round;
            $round->setSeason($this);
        }

        return $this;
    }

    public function removeRound(Round $round): self
    {
        if ($this->rounds->removeElement($round)) {
            // set the owning side to null (unless already changed)
            if ($round->getSeason() === $this) {
                $round->setSeason(null);
            }
        }

        return $this;
    }
}
