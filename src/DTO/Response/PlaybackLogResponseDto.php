<?php

namespace App\DTO\Response;

use App\Entity\PlaybackLog;

class PlaybackLogResponseDto
{
    public function __construct(
        public int $id,
        public int $track_id,
        public string $amount_paid,
        public string $played_at,
    ) {
    }

    public static function fromEntity(PlaybackLog $log): self
    {
        return new self(
            $log->getId(),
            $log->getTrack()->getId(),
            $log->getAmountPaid(),
            $log->getPlayedAt()->format(\DATE_ATOM),
        );
    }
}
