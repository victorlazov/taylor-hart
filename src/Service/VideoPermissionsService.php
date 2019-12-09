<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\VideoPageView;
use App\Repository\VideoPageViewRepository;
use App\Repository\UserRepository;

class VideoPermissionsService
{
    private $loginService;
    private $pageViewsRepository;
    private $userRepository;

    private $maxViewCount;
    private $viewTimeLimit;
    private $adminName;

    private $pageViews;

    public function __construct(
        VideoPageViewRepository $pageViewsRepository,
        UserRepository $userRepository,
        LoginService $loginService,
        int $maxViewCount,
        string $viewTimeLimit,
        string $adminName
    ) {
        $this->pageViewsRepository = $pageViewsRepository;
        $this->loginService = $loginService;
        $this->userRepository = $userRepository;

        $this->maxViewCount = $maxViewCount;
        $this->viewTimeLimit = $viewTimeLimit;
        $this->adminName = $adminName;
    }

    /**
     * Checks whether or not the login service user has
     *
     * @param VideoPageView[] $pageViews
     *
     * @return bool
     */
    public function checkViewPermissions(array $pageViews): bool
    {
        if ($this->loginService->checkAuth()) {
            if (
                $this->checkAdmin()
                || $this->checkPageViews($pageViews)
                || (!$this->checkPageViews($pageViews) && $this->checkLastView($pageViews))
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the user ia an admin ot not.
     *
     * @return bool
     */
    private function checkAdmin(): bool
    {
        return $this->loginService->getSession()->get('username') === $this->adminName;
    }

    /**
     * Check if the page view of the course are less than the allowed maximum.
     *
     * @var VideoPageView[] $pageViews
     *
     * @return bool
     */
    private function checkPageViews(array $pageViews): bool
    {
        return $pageViews && count($pageViews) < $this->maxViewCount;
    }

    /**
     * Checks whether or not the user has watched the video in the minimum time frame.
     *
     * @var VideoPageView[] $pageViews
     *
     * @return bool
     */
    private function checkLastView(array $pageViews): bool
    {
        return current($pageViews)->getTimestamp() < strtotime($this->viewTimeLimit);
    }
}