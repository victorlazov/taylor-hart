<?php

namespace App\Service;

use App\Repository\CoursePageViewsRepository;

class VideoPermissionsService
{
    private $loginService;
    private $pageViewsRepository;

    private $maxViewCount;
    private $viewTimeLimit;
    private $adminName;

    private $pageViews;

    public function __construct(
        CoursePageViewsRepository $pageViewsRepository,
        LoginService $loginService,
        $maxViewCount,
        $viewTimeLimit,
        $adminName
    ) {
        $this->pageViewsRepository = $pageViewsRepository;
        $this->loginService        = $loginService;

        $this->maxViewCount  = (int)$maxViewCount;
        $this->viewTimeLimit = $viewTimeLimit;
        $this->adminName     = $adminName;
    }

    /**
     * Page views setter.
     *
     * @param $courseId
     *
     * @return \App\Service\VideoPermissionsService
     */
    public function setCoursePageViews($courseId): self
    {
        if ($this->loginService->checkAuth()) {
            $userId          = $this->loginService->getSession()->get('uid');
            $this->pageViews = $this->pageViewsRepository->getCourseViewsById(
                $userId,
                $courseId,
                $this->maxViewCount
            );
        }

        return $this;
    }

    /**
     * Checks whether or not the login service user has
     *
     * @return bool
     */
    public function checkViewPermissions(): bool
    {
        if ($this->loginService->checkAuth()) {
            if ($this->checkAdmin() || $this->checkPageViews() || ( ! $this->checkPageViews() && $this->checkLastView())) {
                return true;
            } else {
                return false;
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
        if ($this->loginService->getSession()->get('username') === $this->adminName) {
            return true;
        }

        return false;
    }

    /**
     * Check if the page view of the course are less than the allowed maximum.
     *
     * @return bool
     */
    private function checkPageViews(): bool
    {
        if ($this->pageViews && count($this->pageViews) < $this->maxViewCount) {
            return true;
        }

        return false;
    }

    /**
     * Checks whether or not the user has watched the video in the minimum time frame.
     *
     * @return bool
     */
    private function checkLastView(): bool
    {
        $lastView = current($this->pageViews);

        if ($lastView['timestamp'] < strtotime($this->viewTimeLimit)) {
            return true;
        }

        return false;
    }
}