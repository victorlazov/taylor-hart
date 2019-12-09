<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class VideoFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $generatedDummies = (new NativeLoader())->loadData([
            Video::class => [
                'video{1..10}' => [
                    'name' => '<name()>',
                    'url' => '<url()>',
                ],
            ],
        ]);

        foreach ($generatedDummies->getObjects() as $video) {
            $manager->persist($video);
        }

        $manager->flush();
    }
}