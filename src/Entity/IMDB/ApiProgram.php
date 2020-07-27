<?php

namespace App\Entity\IMDB;

use App\Repository\ApiProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiProgramRepository::class)
 */
class ApiProgram
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $api_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="text")
     */
    private $plot;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $poster;

    /**
     * @ORM\Column(type="integer")
     */
    private $runtime;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $awards;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_seasons;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $endYear;

    /**
     * @ORM\ManyToMany(targetEntity=ApiCreator::class, mappedBy="programs")
     */
    private $apiCreators;

    /**
     * @ORM\ManyToMany(targetEntity=ApiActor::class, mappedBy="programs")
     */
    private $apiActors;

    /**
     * @ORM\ManyToMany(targetEntity=ApiCategory::class, mappedBy="programs")
     */
    private $apiCategories;

    /**
     * @ORM\OneToMany(targetEntity=ApiSeason::class, mappedBy="program")
     */
    private $apiSeasons;

    public function __construct()
    {
        $this->apiCreators = new ArrayCollection();
        $this->apiActors = new ArrayCollection();
        $this->apiCategories = new ArrayCollection();
        $this->apiSeasons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiId(): ?string
    {
        return $this->api_id;
    }

    public function setApiId(string $api_id): self
    {
        $this->api_id = $api_id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPlot(): ?string
    {
        return $this->plot;
    }

    public function setPlot(string $plot): self
    {
        $this->plot = $plot;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getRuntime(): ?int
    {
        return $this->runtime;
    }

    public function setRuntime(int $runtime): self
    {
        $this->runtime = $runtime;

        return $this;
    }

    public function getAwards(): ?string
    {
        return $this->awards;
    }

    public function setAwards(?string $awards): self
    {
        $this->awards = $awards;

        return $this;
    }

    public function getNbSeasons(): ?int
    {
        return $this->nb_seasons;
    }

    public function setNbSeasons(int $nb_seasons): self
    {
        $this->nb_seasons = $nb_seasons;

        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(?int $endYear): self
    {
        $this->endYear = $endYear;

        return $this;
    }

    /**
     * @return Collection|ApiCreator[]
     */
    public function getApiCreators(): Collection
    {
        return $this->apiCreators;
    }

    public function addApiCreator(ApiCreator $apiCreator): self
    {
        if (!$this->apiCreators->contains($apiCreator)) {
            $this->apiCreators[] = $apiCreator;
            $apiCreator->addProgram($this);
        }

        return $this;
    }

    public function removeApiCreator(ApiCreator $apiCreator): self
    {
        if ($this->apiCreators->contains($apiCreator)) {
            $this->apiCreators->removeElement($apiCreator);
            $apiCreator->removeProgram($this);
        }

        return $this;
    }

    /**
     * @return Collection|ApiActor[]
     */
    public function getApiActors(): Collection
    {
        return $this->apiActors;
    }

    public function addApiActor(ApiActor $apiActor): self
    {
        if (!$this->apiActors->contains($apiActor)) {
            $this->apiActors[] = $apiActor;
            $apiActor->addProgram($this);
        }

        return $this;
    }

    public function removeApiActor(ApiActor $apiActor): self
    {
        if ($this->apiActors->contains($apiActor)) {
            $this->apiActors->removeElement($apiActor);
            $apiActor->removeProgram($this);
        }

        return $this;
    }

    /**
     * @return Collection|ApiCategory[]
     */
    public function getApiCategories(): Collection
    {
        return $this->apiCategories;
    }

    public function addApiCategory(ApiCategory $apiCategory): self
    {
        if (!$this->apiCategories->contains($apiCategory)) {
            $this->apiCategories[] = $apiCategory;
            $apiCategory->addProgram($this);
        }

        return $this;
    }

    public function removeApiCategory(ApiCategory $apiCategory): self
    {
        if ($this->apiCategories->contains($apiCategory)) {
            $this->apiCategories->removeElement($apiCategory);
            $apiCategory->removeProgram($this);
        }

        return $this;
    }

    /**
     * @return Collection|ApiSeason[]
     */
    public function getApiSeasons(): Collection
    {
        return $this->apiSeasons;
    }

    public function addApiSeason(ApiSeason $apiSeason): self
    {
        if (!$this->apiSeasons->contains($apiSeason)) {
            $this->apiSeasons[] = $apiSeason;
            $apiSeason->setProgram($this);
        }

        return $this;
    }

    public function removeApiSeason(ApiSeason $apiSeason): self
    {
        if ($this->apiSeasons->contains($apiSeason)) {
            $this->apiSeasons->removeElement($apiSeason);
            // set the owning side to null (unless already changed)
            if ($apiSeason->getProgram() === $this) {
                $apiSeason->setProgram(null);
            }
        }

        return $this;
    }
}
