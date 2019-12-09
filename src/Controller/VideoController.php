<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\VideoPageView;

use App\Repository\UserRepository;
use App\Repository\VideoPageViewRepository;
use App\Repository\VideoRepository;
use App\Service\LoginService;
use App\Service\VideoGenerator;
use App\Service\VideoImpressionManagerService;
use App\Service\VideoPermissionsService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    /**
     * @Route("/video", name="video_index")
     *
     * @param VideoRepository $videoRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(VideoRepository $videoRepository)
    {
        $videos = $videoRepository->findAll();

        return $this->render('video/index.html.twig',
            ['videos' => $videos]
        );
    }

    /**
     * @Route("/video/{id}", name="video")
     *
     * @param string $id
     * @param VideoPageViewRepository $videoPageViewRepository
     * @param VideoRepository $videoRepository
     * @param UserRepository $userRepository
     * @param LoginService $loginService
     * @param VideoPermissionsService $videoPermissions
     * @param VideoImpressionManagerService $videoImpressions
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function view(
        int $id,
        VideoPageViewRepository $videoPageViewRepository,
        VideoRepository $videoRepository,
        UserRepository $userRepository,
        LoginService $loginService,
        VideoPermissionsService $videoPermissions,
        VideoImpressionManagerService $videoImpressions
    ) {
        $pageViews = $videoPageViewRepository->findAll();
        $viewVideo = $videoPermissions->checkViewPermissions($pageViews);
        $userId = $loginService->getSession()->get('uid');

        if($userId) {
            $user = $userRepository->find($userId);
            $videoImpressions->addVideoImpression($user, $id);
        }

        return $this->render('video/view.html.twig', [
            'video' => $videoRepository->find($id),
            'view_video' => $viewVideo,
        ]);
    }
}
