<?php

namespace App\Service;

use App\DTO\Response\TopTrackStatsResponseDto;
use App\Repository\PlaybackLogRepository;

class StatsService
{
    public function __construct(
        private readonly PlaybackLogRepository $playbackLogRepository,
    ) {
    }

    public function getTopTracks(int $limit = 3): array
    {
        $rows = $this->playbackLogRepository->findTopTracks($limit);

        return TopTrackStatsResponseDto::fromArray($rows);
    }
}
