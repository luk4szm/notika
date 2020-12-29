<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
    private $is_club;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $short_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="home")
     */
    private $games_home;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="Guest")
     */
    private $games_guest;

    public function __construct()
    {
        $this->games_home = new ArrayCollection();
        $this->games_guest = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsClub(): ?bool
    {
        return $this->is_club;
    }

    public function setIsClub(bool $is_club): self
    {
        $this->is_club = $is_club;

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
        return $this->short_name;
    }

    public function setShortName(string $short_name): self
    {
        $this->short_name = $short_name;

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

    /**
     * @return Collection|Game[]
     */
    public function getGamesHome(): Collection
    {
        return $this->games_home;
    }

    public function addGamesHome(Game $games_home): self
    {
        if (!$this->games_home->contains($games_home)) {
            $this->games_home[] = $games_home;
            $games_home->setHome($this);
        }

        return $this;
    }

    public function removeGamesHome(Game $games_home): self
    {
        if ($this->games_home->removeElement($games_home)) {
            // set the owning side to null (unless already changed)
            if ($games_home->getHome() === $this) {
                $games_home->setHome(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGamesGuest(): Collection
    {
        return $this->games_guest;
    }

    public function addGamesGuest(Game $games_guest): self
    {
        if (!$this->games_guest->contains($games_guest)) {
            $this->games_guest[] = $games_guest;
            $games_guest->setGuest($this);
        }

        return $this;
    }

    public function removeGamesGuest(Game $games_guest): self
    {
        if ($this->games_guest->removeElement($games_guest)) {
            // set the owning side to null (unless already changed)
            if ($games_guest->getGuest() === $this) {
                $games_guest->setGuest(null);
            }
        }

        return $this;
    }
}
