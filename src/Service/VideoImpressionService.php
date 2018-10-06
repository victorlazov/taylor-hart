<?php

namespace App\Service;

use App\Entity\CoursePageViews;

use Doctrine\Common\Persistence\ObjectManager;

class VideoImpressionService
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

    public function persistVideoImpression($userId, $courseId): self
    {
        $pageView = new CoursePageViews();
        $pageView->setUserId($userId);
        $pageView->setCourseId($courseId);
        $pageView->setTimestamp(time());

        $this->getEntityManager()->persist($pageView);
        $this->getEntityManager()->flush();

        return $this;
    }
}