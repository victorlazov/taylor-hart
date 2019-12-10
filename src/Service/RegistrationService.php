<?php
declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class RegistrationService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persistData($userData)
    {
        var_dump($userData);
        $this->entityManager->persist($userData);
        $this->entityManager->flush();
    }
}