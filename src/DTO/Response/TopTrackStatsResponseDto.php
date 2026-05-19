<?php

namespace App\DTO\Response;

class TopTrackStatsResponseDto
{
    public function __construct(
        public string $title,
        public int $playbacks_count,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'],
            (int) $data['playbacks_count'],
        );
    }
}
