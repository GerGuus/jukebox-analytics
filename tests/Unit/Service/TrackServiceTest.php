<?php

namespace App\Tests\Unit\Service;

use App\Entity\Track;
use App\Repository\TrackRepository;
use App\Service\TrackService;
use PHPUnit\Framework\TestCase;

class TrackServiceTest extends TestCase
{
    public function testUpdatePriceUpdatesTrackAndSaves(): void
    {
        $trackId = 1;
        $newPrice = '15.99';

        $track = new Track('Track 1', 'Artist 1', '10.00');

        $trackRepository = $this->createMock(TrackRepository::class);

        $trackRepository
            ->expects($this->once())
            ->method('find')
            ->with($trackId)
            ->willReturn($track);

        $trackRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->identicalTo($track), true);

        $service = new TrackService($trackRepository);

        $result = $service->updatePrice($trackId, $newPrice);

        $this->assertSame($track, $result);
        $this->assertSame($newPrice, $result->getPrice());
    }

    public function testUpdatePriceThrowsExceptionWhenTrackNotFound(): void
    {
        $trackRepository = $this->createMock(TrackRepository::class);

        $trackRepository
            ->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $trackRepository
            ->expects($this->never())
            ->method('save');

        $service = new TrackService($trackRepository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Track not found');

        $service->updatePrice(999, '20.00');
    }
}
