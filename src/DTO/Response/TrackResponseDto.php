<?php

namespace App\DTO\Response;

use App\Entity\Track;

class TrackResponseDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $artist,
        public string $price,
    ) {
    }

    public static function fromEntity(Track $track): self
    {
        return new self(
            $track->getId(),
            $track->getTitle(),
            $track->getArtist(),
            $track->getPrice(),
        );
    }
}
