<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\Video;
use App\Entity\VideoPageView;
use Doctrine\ORM\EntityManagerInterface;

class VideoImpressionManagerService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Persists course pagve video impression to the database.
     *
     * @param $userId
     * @param $courseId
     *
     * @return \App\Service\VideoImpressionManagerService
     */
    public function addVideoImpression(User $user, int $video): self
    {
        $pageView = new VideoPageView();
        $pageView->setUser($user);
        $pageView->setCourseId($video);
        $pageView->setTimestamp(time());

        $this->entityManager->persist($pageView);
        $this->entityManager->flush();

        return $this;
    }
}