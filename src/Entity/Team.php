<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isClub;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=3, unique=true)
     */
    private $shortName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="home")
     */
    private $gamesHome;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="guest")
     */
    private $gamesGuest;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity=Table::class, mappedBy="team")
     */
    private $tables;

    public function __construct()
    {
        $this->gamesHome = new ArrayCollection();
        $this->gamesGuest = new ArrayCollection();
        $this->tables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsClub(): ?bool
    {
        return $this->isClub;
    }

    public function setIsClub(bool $isClub): self
    {
        $this->isClub = $isClub;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGamesHome(): Collection
    {
        return $this->gamesHome;
    }

    public function addGamesHome(Game $gamesHome): self
    {
        if (!$this->gamesHome->contains($gamesHome)) {
            $this->gamesHome[] = $gamesHome;
            $gamesHome->setHome($this);
        }

        return $this;
    }

    public function removeGamesHome(Game $gamesHome): self
    {
        if ($this->gamesHome->removeElement($gamesHome)) {
            // set the owning side to null (unless already changed)
            if ($gamesHome->getHome() === $this) {
                $gamesHome->setHome(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGamesGuest(): Collection
    {
        return $this->gamesGuest;
    }

    public function addGamesGuest(Game $gamesGuest): self
    {
        if (!$this->gamesGuest->contains($gamesGuest)) {
            $this->gamesGuest[] = $gamesGuest;
            $gamesGuest->setGuest($this);
        }

        return $this;
    }

    public function removeGamesGuest(Game $gamesGuest): self
    {
        if ($this->gamesGuest->removeElement($gamesGuest)) {
            // set the owning side to null (unless already changed)
            if ($gamesGuest->getGuest() === $this) {
                $gamesGuest->setGuest(null);
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
            $table->addTeam($this);
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
}
