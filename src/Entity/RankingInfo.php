<?php

namespace App\Entity;

use App\Repository\RankingInfoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RankingInfoRepository::class)
 */
class RankingInfo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class, inversedBy="rankingInfos")
     */
    private $season;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $mark;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $startRound;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $endRound;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $closeAt;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getMark(): ?string
    {
        return $this->mark;
    }

    public function setMark(?string $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    public function getStartRound(): ?int
    {
        return $this->startRound;
    }

    public function setStartRound(?int $startRound): self
    {
        $this->startRound = $startRound;

        return $this;
    }

    public function getEndRound(): ?int
    {
        return $this->endRound;
    }

    public function setEndRound(?int $endRound): self
    {
        $this->endRound = $endRound;

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

    public function getCloseAt(): ?\DateTimeInterface
    {
        return $this->closeAt;
    }

    public function setCloseAt(?\DateTimeInterface $closeAt): self
    {
        $this->closeAt = $closeAt;

        return $this;
    }
}
