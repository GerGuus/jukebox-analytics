<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Track;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TrackFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tracks = [
            [
                'artist' => 'Oomph',
                'title' => 'Labyrinth',
                'price' => '1.50',
            ],
            [
                'artist' => 'Oomph',
                'title' => 'Beim erster Mal tuts immer weh',
                'price' => '1.20',
            ],
            [
                'artist' => 'ASP',
                'title' => 'Coming home',
                'price' => '2.00',
            ],
            [
                'artist' => 'ASP',
                'title' => 'Wer sonst',
                'price' => '1.70',
            ],
            [
                'artist' => 'Eisbracher',
                'title' => 'Mein blut',
                'price' => '1.70',
            ],
            [
                'artist' => 'Eisbracher',
                'title' => 'Komm susser Tod',
                'price' => '1.30',
            ],
        ];

        foreach ($tracks as $item) {
            $track = new Track(
                $item['title'],
                $item['artist'],
                $item['price']
            );

            $manager->persist($track);
        }

        $manager->flush();
    }
}
