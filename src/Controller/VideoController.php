<?php

namespace App\Controller;

use App\Service\VideoGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    /**
     * @Route("/video", name="video_index")
     */
    public function index(VideoGenerator $videoGenerator)
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
    public function view($id, VideoGenerator $videoGenerator)
    {
        if ($video = $videoGenerator->getVideo($id)) {

            return $this->render('video/view.html.twig', [
                'video'    => $video,
                'video_id' => $id,
            ]);
        }

        return $this->redirectToRoute('video_index');
    }
}
