<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

abstract class abstractManager
{
    private $em;

    private $doctrine;

    public function __construct(EntityManagerInterface $em, ManagerRegistry $doctrine)
    {
        $this->setEm($em);
        $this->setDoctrine($doctrine);
    }

    /**
     * @param mixed $em
     */
    public function setEm($em): void
    {
        $this->em = $em;
    }

    /**
     * @param mixed $doctrine
     */
    public function setDoctrine($doctrine): void
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return mixed
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @return mixed
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    public function cleanInput(string $input): string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }
}