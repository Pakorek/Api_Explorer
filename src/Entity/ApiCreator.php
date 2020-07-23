<?php

namespace App\Entity;

use App\Repository\ApiCreatorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiCreatorRepository::class)
 */
class ApiCreator
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
    private $full_name;

    /**
     * @ORM\ManyToMany(targetEntity=ApiProgram::class, inversedBy="apiCreators")
     */
    private $programs;

    public function __construct()
    {
        $this->programs = new ArrayCollection();
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

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): self
    {
        $this->full_name = $full_name;

        return $this;
    }

    /**
     * @return Collection|ApiProgram[]
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(ApiProgram $program): self
    {
        if (!$this->programs->contains($program)) {
            $this->programs[] = $program;
        }

        return $this;
    }

    public function removeProgram(ApiProgram $program): self
    {
        if ($this->programs->contains($program)) {
            $this->programs->removeElement($program);
        }

        return $this;
    }
}
