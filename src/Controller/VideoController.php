<?php

namespace App\Controller;

use App\Entity\CoursePageViews;
use App\Service\LoginService;
use App\Service\VideoGenerator;

use App\Service\VideoPermissionsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    /**
     * @Route("/video", name="video_index")
     */
    public function index(VideoGenerator $videoGenerator, LoginService $loginService)
    {
        return $this->render('video/index.html.twig',
            [
                'videos' => $videoGenerator->getVideos(),
            ]
        );
    }

    /**
     * @Route("/video/{id}", name="video")
     */
    public function view(
        $id,
        VideoGenerator $videoGenerator,
        LoginService $loginService,
        VideoPermissionsService $videoPermissions
    ) {
        $pageViewsRepository = $this->getDoctrine()->getRepository(CoursePageViews::class);
        $videoPermissions->init($pageViewsRepository, $id, $loginService);

        if ($viewVideo = $videoPermissions->checkViewPermissions()) {
            $entityManager = $this->getDoctrine()->getManager();

            $pageView = new CoursePageViews();
            $pageView->setUserId($loginService->getSession()->get('uid'));
            $pageView->setCourseId($id);
            $pageView->setTimestamp(time());

            $entityManager->persist($pageView);
            $entityManager->flush();
        }

        if ($video = $videoGenerator->getVideo($id)) {

            return $this->render('video/view.html.twig', [
                'video'      => $video,
                'video_id'   => $id,
                'view_video' => $viewVideo,
            ]);
        }

        return $this->redirectToRoute('video_index');
    }
}
