<?php

namespace App\Service;

use App\Repository\PlaybackLogRepository;

class StatsService
{
    public function __construct(
        private readonly PlaybackLogRepository $playbackLogRepository,
    ) {
    }

    public function getTopTracks(int $limit = 3): array
    {
        return $this->playbackLogRepository->findTopTracks($limit);
    }
}
