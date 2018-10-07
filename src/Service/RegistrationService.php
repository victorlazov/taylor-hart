<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class RegistrationService
{
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persistData($userData)
    {
        $this->entityManager->persist($userData);
        $this->entityManager->flush();
    }
}