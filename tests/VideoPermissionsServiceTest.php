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
     * @var LoginService
     */
    private $loginService;
    /**
     * @var SessionInterface
     */
    private $session;

    protected function setUp()
    {
        $this->loginService = $this->createMock(LoginService::class);
        $this->session = new Session(new MockArraySessionStorage());
    }

    /**
     * @dataProvider pageViewsDataProvider
     * @param array $pageViews
     */
    public function testCheckViewPermissions(array $pageViews)
    {
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

    public function pageViewsDataProvider(): array
    {
        $video = (new Video())
            ->setId(1)
            ->setUrl('http://example.com')
            ->setName('Example video');
        $user = (new User())
            ->setId(1)
            ->setUsername('username')
            ->setEmail('me@example.com')
            ->setPassword('password');

        $videoPageView = (new VideoPageView())
            ->setVideo($video)
            ->setUser($user);

        return [
            'Access forbidden' =>
            [
                (clone $videoPageView)->setTimestamp(time() + 1),
                (clone $videoPageView)->setTimestamp(time() + 2),
                (clone $videoPageView)->setTimestamp(time() + 3),
                (clone $videoPageView)->setTimestamp(time() + 4),
                (clone $videoPageView)->setTimestamp(time() + 5),
                (clone $videoPageView)->setTimestamp(time() + 6),
                (clone $videoPageView)->setTimestamp(time() + 7),
                (clone $videoPageView)->setTimestamp(time() + 8),
                (clone $videoPageView)->setTimestamp(time() + 9),
                (clone $videoPageView)->setTimestamp(time() + 10),
            ]
        ];
    }
}