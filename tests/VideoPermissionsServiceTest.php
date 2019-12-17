<?php
declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use App\Entity\Video;
use App\Entity\VideoPageView;
use App\Service\LoginService;
use App\Service\VideoPermissionsService;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class VideoPermissionsServiceTest extends TestCase
{
    /**
     * @var LoginService|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loginService;
    /**
     * @var SessionInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $session;

    protected function setUp()
    {
        $this->loginService = $this->createMock(LoginService::class);
        $this->session = $this->createMock(Session::class);
    }

    public function testCheckViewPermissionsSuccess()
    {
        $user = $this->constructUser('username');

        $this->loginService->expects($this->any())
            ->method('checkAuth')
            ->willReturn(true);

        $this->session->expects($this->any())
            ->method('get')
            ->willReturn($user);

        $pageViews = $this->constructPageViews($user, 9);

        $videoPermissionsService = new VideoPermissionsService(
            $this->loginService,
            $this->session,
            10,
            '-1 day',
            'admin'
        );
        $viewVideo = $videoPermissionsService->checkViewPermissions($pageViews);

        $this->assertTrue($viewVideo);
    }

    public function testCheckViewPermissionsSuccessTimeFramePassed()
    {
        $user = $this->constructUser('username');

        $this->loginService->expects($this->any())
            ->method('checkAuth')
            ->willReturn(true);

        $this->session->expects($this->any())
            ->method('get')
            ->willReturn($user);

        $pageViews = $this->constructPageViews($user, 10);

        $videoPermissionsService = new VideoPermissionsService(
            $this->loginService,
            $this->session,
            10,
            '-5 seconds',
            'admin'
        );
        $viewVideo = $videoPermissionsService->checkViewPermissions($pageViews);

        $this->assertTrue($viewVideo);
    }

    public function testCheckViewPermissionsFailPageViews()
    {
        $user = $this->constructUser('username');

        $this->loginService->expects($this->any())
            ->method('checkAuth')
            ->willReturn(true);

        $this->session->expects($this->any())
            ->method('get')
            ->willReturn($user);

        $pageViews = $this->constructPageViews($user, 15);

        $videoPermissionsService = new VideoPermissionsService(
            $this->loginService,
            $this->session,
            10,
            '-1 day',
            'admin'
        );
        $viewVideo = $videoPermissionsService->checkViewPermissions($pageViews);

        $this->assertFalse($viewVideo);
    }

    public function testCheckViewPermissionsFailTimeFrameNotPassed()
    {
        $user = $this->constructUser('username');

        $this->loginService->expects($this->any())
            ->method('checkAuth')
            ->willReturn(true);

        $this->session->expects($this->any())
            ->method('get')
            ->willReturn($user);

        $pageViews = $this->constructPageViews($user, 10);

        $videoPermissionsService = new VideoPermissionsService(
            $this->loginService,
            $this->session,
            10,
            '-15 seconds',
            'admin'
        );
        $viewVideo = $videoPermissionsService->checkViewPermissions($pageViews);

        $this->assertFalse($viewVideo);
    }

    public function testCheckViewPermissionsFailNotAuthenticated()
    {
        $this->loginService->expects($this->any())
            ->method('checkAuth')
            ->willReturn(false);

        $pageViews = [];

        $videoPermissionsService = new VideoPermissionsService(
            $this->loginService,
            $this->session,
            10,
            '-1 day',
            'admin'
        );
        $viewVideo = $videoPermissionsService->checkViewPermissions($pageViews);

        $this->assertFalse($viewVideo);
    }

    public function testAdminCheckViewPermissions()
    {
        $user = $this->constructUser('admin');

        $this->loginService->expects($this->any())
            ->method('checkAuth')
            ->willReturn(true);

        $this->session->expects($this->any())
            ->method('get')
            ->willReturn($user);

        $pageViews = $this->constructPageViews($user, 12);

        $videoPermissionsService = new VideoPermissionsService(
            $this->loginService,
            $this->session,
            10,
            '-1 day',
            'admin'
        );
        $viewVideo = $videoPermissionsService->checkViewPermissions($pageViews);

        $this->assertTrue($viewVideo);
    }

    private function constructUser(string $username)
    {
        return (new User())
            ->setId(1)
            ->setUsername($username)
            ->setEmail('me@admin.com')
            ->setPassword('password');
    }

    /**
     * @param User $user
     * @param int $number
     *
     * @return VideoPageView[]
     */
    private function constructPageViews(User $user, int $number): array
    {
        $video = (new Video())
            ->setId(1)
            ->setUrl('http://example.com')
            ->setName('Example video');

        $videoPageView = (new VideoPageView())
            ->setVideo($video)
            ->setUser($user);

        $pageViews = [];

        for ($i = 0; $i < $number; $i++) {
            $pageViews[] = (clone $videoPageView)->setTimestamp(time() - $i);
        }

        return $pageViews;
    }
}