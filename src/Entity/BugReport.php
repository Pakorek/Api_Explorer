<?php

namespace App\Entity;

use App\Repository\BugReportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BugReportRepository::class)
 */
class BugReport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=API::class, inversedBy="bugReports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $api;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFixed = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getApi(): ?API
    {
        return $this->api;
    }

    public function setApi(?API $api): self
    {
        $this->api = $api;

        return $this;
    }

    public function getIsFixed(): ?bool
    {
        return $this->isFixed;
    }

    public function setIsFixed(bool $isFixed): self
    {
        $this->isFixed = $isFixed;

        return $this;
    }
}
