<?php

namespace App\Service;

use App\Entity\PlaybackLog;
use App\Repository\PlaybackLogRepository;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlaybackLogService
{
    public function __construct(
        private readonly TrackRepository $trackRepository,
        private readonly PlaybackLogRepository $playbackLogRepository,
    ) {
    }

    public function create(int $trackId, string $amountPaid): PlaybackLog
    {
        $track = $this->trackRepository->find($trackId);

        if (!$track) {
            throw new \RuntimeException('Track not found');
        }

        $log = new PlaybackLog($track, new \DateTimeImmutable(), $amountPaid);

        $this->playbackLogRepository->save($log);

        return $log;
    }
}
