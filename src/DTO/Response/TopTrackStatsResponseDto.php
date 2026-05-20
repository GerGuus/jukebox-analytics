<?php

namespace App\DTO\Response;

class TopTrackStatsResponseDto
{
    public function __construct(
        public string $title,
        public int $playbacks_count,
    ) {
    }


    public static function fromRecord(array $row): self
    {
        return new self(
            $row['title'],
            (int) $row['playbacks_count'],
        );
    }

    public static function fromArray(array $rows): array
    {
        return array_map(
            static fn (array $row): self => self::fromRecord($row),
            $rows
        );
    }
}
