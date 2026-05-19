<?php

namespace App\Tests\Unit\Service;

use App\Entity\PlaybackLog;
use App\Entity\Track;
use App\Repository\PlaybackLogRepository;
use App\Repository\TrackRepository;
use App\Service\PlaybackLogService;
use PHPUnit\Framework\TestCase;

class PlaybackLogServiceTest extends TestCase
{
    public function testCreateCreatesPlaybackLogAndSavesIt(): void
    {
        $trackId = 1;
        $amountPaid = '12.50';

        $track = new Track();
        $track->setTitle('Test Track');
        $track->setArtist('Test Artist');
        $track->setPrice('10.00');

        $trackRepository = $this->createMock(TrackRepository::class);
        $playbackLogRepository = $this->createMock(PlaybackLogRepository::class);

        $trackRepository
            ->expects($this->once())
            ->method('find')
            ->with($trackId)
            ->willReturn($track);

        $playbackLogRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (PlaybackLog $log) use ($track, $amountPaid) {
                $this->assertSame($track, $log->getTrack());
                $this->assertSame($amountPaid, $log->getAmountPaid());
                $this->assertInstanceOf(\DateTimeImmutable::class, $log->getPlayedAt());

                return true;
            }));

        $service = new PlaybackLogService($trackRepository, $playbackLogRepository);

        $result = $service->create($trackId, $amountPaid);

        $this->assertInstanceOf(PlaybackLog::class, $result);
        $this->assertSame($track, $result->getTrack());
        $this->assertSame($amountPaid, $result->getAmountPaid());
        $this->assertInstanceOf(\DateTimeImmutable::class, $result->getPlayedAt());
    }

    public function testCreateThrowsExceptionWhenTrackNotFound(): void
    {
        $trackRepository = $this->createMock(TrackRepository::class);
        $playbackLogRepository = $this->createMock(PlaybackLogRepository::class);

        $trackRepository
            ->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $playbackLogRepository
            ->expects($this->never())
            ->method('save');

        $service = new PlaybackLogService($trackRepository, $playbackLogRepository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Track not found');

        $service->create(999, '15.00');
    }
}
