<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="smallint")
     */
    private $nation;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $language;

    /**
     * @ORM\Column(type="boolean")
     */
    private $member;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastEmail;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Bet::class, mappedBy="user")
     */
    private $bets;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="createdBy")
     */
    private $gamedAdded;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="updatedBy")
     */
    private $gamesUpdated;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $locale;

    /**
     * @ORM\OneToMany(targetEntity=Classification::class, mappedBy="user", orphanRemoval=true)
     */
    private $classifications;

    public function __construct()
    {
        $this->bets            = new ArrayCollection();
        $this->gamedAdded      = new ArrayCollection();
        $this->gamesUpdated    = new ArrayCollection();
        $this->classifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getNation(): ?int
    {
        return $this->nation;
    }

    public function setNation(int $nation): self
    {
        $this->nation = $nation;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getMember(): ?bool
    {
        return $this->member;
    }

    public function setMember(bool $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getLastEmail(): ?string
    {
        return $this->lastEmail;
    }

    public function setLastEmail(?string $lastEmail): self
    {
        $this->lastEmail = $lastEmail;

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
            $bet->setUser($this);
        }

        return $this;
    }

    public function removeBet(Bet $bet): self
    {
        if ($this->bets->removeElement($bet)) {
            // set the owning side to null (unless already changed)
            if ($bet->getUser() === $this) {
                $bet->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGamedAdded(): Collection
    {
        return $this->gamedAdded;
    }

    public function addGamedAdded(Game $gamedAdded): self
    {
        if (!$this->gamedAdded->contains($gamedAdded)) {
            $this->gamedAdded[] = $gamedAdded;
            $gamedAdded->setCreatedBy($this);
        }

        return $this;
    }

    public function removeGamedAdded(Game $gamedAdded): self
    {
        if ($this->gamedAdded->removeElement($gamedAdded)) {
            // set the owning side to null (unless already changed)
            if ($gamedAdded->getCreatedBy() === $this) {
                $gamedAdded->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGamesUpdated(): Collection
    {
        return $this->gamesUpdated;
    }

    public function addGamesUpdated(Game $gamesUpdated): self
    {
        if (!$this->gamesUpdated->contains($gamesUpdated)) {
            $this->gamesUpdated[] = $gamesUpdated;
            $gamesUpdated->setUpdatedBy($this);
        }

        return $this;
    }

    public function removeGamesUpdated(Game $gamesUpdated): self
    {
        if ($this->gamesUpdated->removeElement($gamesUpdated)) {
            // set the owning side to null (unless already changed)
            if ($gamesUpdated->getUpdatedBy() === $this) {
                $gamesUpdated->setUpdatedBy(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return Collection|Classification[]
     */
    public function getClassifications(): Collection
    {
        return $this->classifications;
    }

    public function addClassification(Classification $classification): self
    {
        if (!$this->classifications->contains($classification)) {
            $this->classifications[] = $classification;
            $classification->setUser($this);
        }

        return $this;
    }

    public function removeClassification(Classification $classification): self
    {
        if ($this->classifications->removeElement($classification)) {
            // set the owning side to null (unless already changed)
            if ($classification->getUser() === $this) {
                $classification->setUser(null);
            }
        }

        return $this;
    }
}
