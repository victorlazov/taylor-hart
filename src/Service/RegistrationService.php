<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class RegistrationService
{
    private $entityManager;
    private $userData;

    public function setEntityManager(ObjectManager $entityManager): self
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    public function getEntityManager(): ObjectManager
    {
        return $this->entityManager;
    }

    public function setUserData($userData): self
    {
        $this->userData = $userData;

        return $this;
    }

    public function persistData()
    {
        $this->getEntityManager()->persist($this->userData);
        $this->getEntityManager()->flush();
    }
}