<?php

namespace App\Entity\ApiMedic;

use App\Repository\ApiMedic\BodySublocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BodySublocationRepository::class)
 */
class BodySublocation
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
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $api_id;

    /**
     * @ORM\ManyToMany(targetEntity=Symptom::class, mappedBy="sublocations")
     */
    private $symptoms;

    /**
     * @ORM\ManyToOne(targetEntity=BodyLocation::class, inversedBy="bodySublocations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bodyLocation;

    public function __construct()
    {
        $this->symptoms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getApiId(): ?int
    {
        return $this->api_id;
    }

    public function setApiId(int $api_id): self
    {
        $this->api_id = $api_id;

        return $this;
    }

    /**
     * @return Collection|Symptom[]
     */
    public function getSymptoms(): Collection
    {
        return $this->symptoms;
    }

    public function addSymptom(Symptom $symptom): self
    {
        if (!$this->symptoms->contains($symptom)) {
            $this->symptoms[] = $symptom;
            $symptom->addSublocation($this);
        }

        return $this;
    }

    public function removeSymptom(Symptom $symptom): self
    {
        if ($this->symptoms->contains($symptom)) {
            $this->symptoms->removeElement($symptom);
            $symptom->removeSublocation($this);
        }

        return $this;
    }

    public function getBodyLocation(): ?BodyLocation
    {
        return $this->bodyLocation;
    }

    public function setBodyLocation(?BodyLocation $bodyLocation): self
    {
        $this->bodyLocation = $bodyLocation;

        return $this;
    }
}
