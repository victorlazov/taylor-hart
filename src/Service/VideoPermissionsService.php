<?php

namespace App\Service;

class VideoPermissionsService
{
    private $loginService;
    private $pageViewsRepository;

    private $maxViewCount;
    private $viewTimeLimit;
    private $adminName;

    private $pageViews;

    public function __construct($maxViewCount, $viewTimeLimit, $adminName)
    {
        $this->maxViewCount  = (int)$maxViewCount;
        $this->viewTimeLimit = $viewTimeLimit;
        $this->adminName     = $adminName;
    }

    public function init($repository, $courseId, LoginService $loginService)
    {
        $this->setRepository($repository);
        $this->useLoginService($loginService);

        if ($this->getLoginService()->checkAuth()) {
            $userId          = $this->getLoginService()->getSession()->get('uid');
            $this->pageViews = $this->pageViewsRepository->getCouserViewsById(
                $userId,
                $courseId,
                $this->maxViewCount
            );
        }
    }

    /**
     * @param $repository
     *
     * @return \App\Service\VideoPermissionsService
     */
    private function setRepository($repository): self
    {
        $this->pageViewsRepository = $repository;

        return $this;
    }

    /**
     * Setter for the login service.
     *
     * TODO: with the current architecture it's best to have once source for the user - loginService.
     *
     * @param \App\Service\LoginService $loginService
     *
     * @return \App\Service\VideoPermissionsService
     */
    private function useLoginService(LoginService $loginService): self
    {
        $this->loginService = $loginService;

        return $this;
    }

    /**
     * LoginService getter.
     *
     * @return \App\Service\LoginService
     */
    public function getLoginService(): LoginService
    {
        return $this->loginService;
    }

    /**
     * Checks whether or not the login service user has
     *
     * @return bool
     */
    public function checkViewPermissions(): bool
    {
        if ($this->getLoginService()->checkAuth()) {
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
        if ($this->getLoginService()->getSession()->get('username') === $this->adminName) {
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
     *
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