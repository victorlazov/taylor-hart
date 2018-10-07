<?php

namespace App\Service;

use App\Entity\CoursePageViews;

use Doctrine\Common\Persistence\ObjectManager;

class VideoImpressionService
{
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Persists course pagve video impression to the database.
     *
     * @param $userId
     * @param $courseId
     *
     * @return \App\Service\VideoImpressionService
     */
    public function persistVideoImpression($userId, $courseId): self
    {
        $pageView = new CoursePageViews();
        $pageView->setUserId($userId);
        $pageView->setCourseId($courseId);
        $pageView->setTimestamp(time());

        $this->entityManager->persist($pageView);
        $this->entityManager->flush();

        return $this;
    }
}