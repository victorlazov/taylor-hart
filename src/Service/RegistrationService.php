<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class RegistrationService
{
    private $entityManager;
    private $userData;

    public function setEntityManager(ObjectManager $entityManager): RegistrationService
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    public function getEntityManager(): ObjectManager
    {
        return $this->entityManager;
    }

    public function setUserData(array $userData): RegistrationService
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