<?php

namespace App\Service;

use Nelmio\Alice\Loader\NativeLoader;

class VideoGenerator
{
    protected $loader;
    protected $videos;

    /**
     * VideoGenerator constructor.
     *
     * Creates and sets NativeLoader object for loading fixture data. Loads video data.
     */
    public function __construct()
    {
        $this->loader = new NativeLoader();
        $this->videos = $this->loader->loadData([
            \App\Entity\Video::class => [
                'video{1..10}' => [
                    'name' => '<name()>',
                    'url'  => '<url()>',
                ],
            ],
        ]);
    }

    /**
     * Loads all videos from a fixture.
     *
     * @return array
     */
    public function getVideos()
    {
        return $this->videos->getObjects();
    }

    /**
     * Loads single video from the fixture.
     *
     * @param $id
     *
     * @return mixed|null
     */
    public function getVideo($id)
    {
        if ( ! empty($video = $this->getVideos()[$id])) {
            return $video;
        }

        return null;
    }
}