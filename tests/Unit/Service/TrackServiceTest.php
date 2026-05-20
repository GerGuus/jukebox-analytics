<?php

namespace App\Tests\Unit\Service;

use App\Entity\Track;
use App\Repository\TrackRepository;
use App\Service\TrackService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class TrackServiceTest extends TestCase
{
    public function testUpdatePriceUpdatesTrackAndFlushes(): void
    {
        $trackId = 1;
        $newPrice = '15.99';

        $track = new Track('Track 1', 'Artist 1', '10.00');

        $trackRepository = $this->createMock(TrackRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $trackRepository
            ->expects($this->once())
            ->method('find')
            ->with($trackId)
            ->willReturn($track);

        $entityManager
            ->expects($this->once())
            ->method('flush');

        $service = new TrackService($trackRepository, $entityManager);

        $result = $service->updatePrice($trackId, $newPrice);

        $this->assertSame($track, $result);
        $this->assertSame($newPrice, $result->getPrice());
    }

    public function testUpdatePriceThrowsExceptionWhenTrackNotFound(): void
    {
        $trackRepository = $this->createMock(TrackRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $trackRepository
            ->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $entityManager
            ->expects($this->never())
            ->method('flush');

        $service = new TrackService($trackRepository, $entityManager);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Track not found');

        $service->updatePrice(999, '20.00');
    }
}
