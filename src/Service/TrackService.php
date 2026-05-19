<?php

namespace App\Service;

use App\Entity\Track;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;

class TrackService
{
    public function __construct(
        private readonly TrackRepository $trackRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function updatePrice(int $id, string $newPrice): Track
    {
        $track = $this->trackRepository->find($id);

        if (!$track) {
            throw new \RuntimeException('Track not found');
        }

        $track->setPrice($newPrice);

        $this->entityManager->flush();

        return $track;
    }
}
