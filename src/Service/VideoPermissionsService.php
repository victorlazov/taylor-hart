<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\VideoPageView;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class VideoPermissionsService
{
    private $loginService;
    private $session;

    private $maxViewCount;
    private $viewTimeLimit;
    private $adminName;

    public function __construct(
        LoginService $loginService,
        SessionInterface $session,
        int $maxViewCount,
        string $viewTimeLimit,
        string $adminName
    ) {
        $this->loginService = $loginService;
        $this->session = $session;

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
    public function checkViewPermissions(array $pageViews = []): bool
    {
        if (
            $this->loginService->checkAuth()
            && (
                $this->checkAdmin()
                || $this->checkPageViews($pageViews)
                || (!$this->checkPageViews($pageViews) && $this->checkLastView($pageViews))
            )
        ) {
            return true;
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
        /**
         * @var $user User
         */
        $user = $this->session->get('user');
        return $user->getUsername() === $this->adminName;
    }

    /**
     * Check if the page view of the course are less than the allowed maximum.
     *
     * @var VideoPageView[] $pageViews
     *
     * @return bool
     */
    private function checkPageViews(array $pageViews = []): bool
    {
        return count($pageViews) < $this->maxViewCount;
    }

    /**
     * Checks whether or not the user has watched the video in the minimum time frame.
     *
     * @var VideoPageView[] $pageViews
     *
     * @return bool
     */
    private function checkLastView(array $pageViews = []): bool
    {
        return count($pageViews) > 0 && current($pageViews)->getTimestamp() < strtotime($this->viewTimeLimit);
    }
}