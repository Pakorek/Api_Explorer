<?php

namespace App\Entity;

use App\Repository\ApiSeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiSeasonRepository::class)
 */
class ApiSeason
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=ApiEpisode::class, mappedBy="season")
     */
    private $apiEpisodes;

    /**
     * @ORM\ManyToOne(targetEntity=ApiProgram::class, inversedBy="apiSeasons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $program;

    public function __construct()
    {
        $this->apiEpisodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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

    /**
     * @return Collection|ApiEpisode[]
     */
    public function getApiEpisodes(): Collection
    {
        return $this->apiEpisodes;
    }

    public function addApiEpisode(ApiEpisode $apiEpisode): self
    {
        if (!$this->apiEpisodes->contains($apiEpisode)) {
            $this->apiEpisodes[] = $apiEpisode;
            $apiEpisode->setSeason($this);
        }

        return $this;
    }

    public function removeApiEpisode(ApiEpisode $apiEpisode): self
    {
        if ($this->apiEpisodes->contains($apiEpisode)) {
            $this->apiEpisodes->removeElement($apiEpisode);
            // set the owning side to null (unless already changed)
            if ($apiEpisode->getSeason() === $this) {
                $apiEpisode->setSeason(null);
            }
        }

        return $this;
    }

    public function getProgram(): ?ApiProgram
    {
        return $this->program;
    }

    public function setProgram(?ApiProgram $program): self
    {
        $this->program = $program;

        return $this;
    }
}
