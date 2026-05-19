<?php

namespace App\Tests\Unit\Service;

use App\Repository\PlaybackLogRepository;
use App\Service\StatsService;
use PHPUnit\Framework\TestCase;

class StatsServiceTest extends TestCase
{
    public function testGetTopTracksReturnsTopThreeTracks(): void
    {
        $expected = [
            ['title' => 'Track A', 'playbacks_count' => 15],
            ['title' => 'Track B', 'playbacks_count' => 10],
            ['title' => 'Track C', 'playbacks_count' => 7],
        ];

        $repository = $this->createMock(PlaybackLogRepository::class);
        $repository
            ->expects($this->once())
            ->method('findTopTracks')
            ->with(3)
            ->willReturn($expected);

        $service = new StatsService($repository);

        $result = $service->getTopTracks();

        $this->assertSame($expected, $result);
        $this->assertCount(3, $result);
        $this->assertSame('Track A', $result[0]['title']);
        $this->assertSame(15, $result[0]['playbacks_count']);
    }

    public function testGetTopTracksCanReturnLessThanThreeTracks(): void
    {
        $expected = [
            ['title' => 'Only Track', 'playbacks_count' => 2],
        ];

        $repository = $this->createMock(PlaybackLogRepository::class);
        $repository
            ->expects($this->once())
            ->method('findTopTracks')
            ->with(3)
            ->willReturn($expected);

        $service = new StatsService($repository);

        $result = $service->getTopTracks();

        $this->assertSame($expected, $result);
        $this->assertCount(1, $result);
    }
}
