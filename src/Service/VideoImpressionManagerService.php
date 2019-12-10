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
     * Persists course page video impression to the database.
     *
     * @param User $user
     * @param Video $video
     */
    public function addVideoImpression(User $user, Video $video)
    {
        $pageView = new VideoPageView();
        $pageView->setUser($user);
        $pageView->setVideo($video);
        $pageView->setTimestamp(time());

        $this->entityManager->merge($pageView);
        $this->entityManager->flush();
    }
}