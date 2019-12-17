<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Video;

use App\Repository\VideoPageViewRepository;
use App\Repository\VideoRepository;
use App\Service\VideoImpressionManagerService;
use App\Service\VideoPermissionsService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
     * @param Video $video
     * @param VideoPageViewRepository $videoPageViewRepository
     * @param VideoPermissionsService $videoPermissions
     * @param VideoImpressionManagerService $videoImpressions
     * @param SessionInterface $session
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function view(
        Video $video,
        VideoPageViewRepository $videoPageViewRepository,
        VideoPermissionsService $videoPermissions,
        VideoImpressionManagerService $videoImpressions,
        SessionInterface $session
    ) {
        $user = $session->get('user');
        $pageViews = $videoPageViewRepository->findBy(['video' => $video]);
        $viewVideo = $videoPermissions->checkViewPermissions($pageViews);

        if($user) {
            $videoImpressions->addVideoImpression($user, $video);
        }

        return $this->render('video/view.html.twig', [
            'video' => $video,
            'view_video' => $viewVideo,
        ]);
    }
}
