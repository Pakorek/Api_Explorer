<?php

namespace App\Entity\ApiMedic;

use App\Repository\ApiMedic\SymptomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SymptomRepository::class)
 */
class Symptom
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
     * @ORM\ManyToMany(targetEntity=BodySublocation::class, inversedBy="symptoms")
     */
    private $sublocations;

    /**
     * @ORM\Column(type="integer")
     */
    private $api_id;

    public function __construct()
    {
        $this->sublocations = new ArrayCollection();
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

    /**
     * @return Collection|BodySublocation[]
     */
    public function getSublocations(): Collection
    {
        return $this->sublocations;
    }

    public function addSublocation(BodySublocation $sublocation): self
    {
        if (!$this->sublocations->contains($sublocation)) {
            $this->sublocations[] = $sublocation;
        }

        return $this;
    }

    public function removeSublocation(BodySublocation $sublocation): self
    {
        if ($this->sublocations->contains($sublocation)) {
            $this->sublocations->removeElement($sublocation);
        }

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
}
