<?php

namespace App\Entity\ApiMedic;

use App\Repository\ApiMedic\BodyLocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BodyLocationRepository::class)
 */
class BodyLocation
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
     * @ORM\OneToMany(targetEntity=BodySublocation::class, mappedBy="bodyLocation")
     */
    private $bodySublocations;

    public function __construct()
    {
        $this->bodySublocations = new ArrayCollection();
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
     * @return Collection|BodySublocation[]
     */
    public function getBodySublocations(): Collection
    {
        return $this->bodySublocations;
    }

    public function addBodySublocation(BodySublocation $bodySublocation): self
    {
        if (!$this->bodySublocations->contains($bodySublocation)) {
            $this->bodySublocations[] = $bodySublocation;
            $bodySublocation->setBodyLocation($this);
        }

        return $this;
    }

    public function removeBodySublocation(BodySublocation $bodySublocation): self
    {
        if ($this->bodySublocations->contains($bodySublocation)) {
            $this->bodySublocations->removeElement($bodySublocation);
            // set the owning side to null (unless already changed)
            if ($bodySublocation->getBodyLocation() === $this) {
                $bodySublocation->setBodyLocation(null);
            }
        }

        return $this;
    }
}
