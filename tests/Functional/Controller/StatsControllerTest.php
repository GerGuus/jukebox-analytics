<?php

namespace App\Tests\Functional\Controller;

use App\Entity\PlaybackLog;
use App\Entity\Track;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class StatsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();

        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $this->truncateEntities();
    }

    public function testTopReturnsTracksOrderedByPlaybackCount(): void
    {
        $track1 = new Track('Track A', 'Test A', '10.00');

        $track2 = new Track('Track B', 'Test B', '20.00');

        $track3 = new Track('Track C', 'Test C', '30.0');

        $this->entityManager->persist($track1);
        $this->entityManager->persist($track2);
        $this->entityManager->persist($track3);
        $this->entityManager->flush();

        $this->persistPlaybackLog($track1, '10.00');
        $this->persistPlaybackLog($track1, '10.00');
        $this->persistPlaybackLog($track1, '10.00');

        $this->persistPlaybackLog($track2, '20.00');
        $this->persistPlaybackLog($track2, '20.00');

        $this->persistPlaybackLog($track3, '30.00');

        $this->client->request('GET', '/api/stats/top');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(3, $data);

        $this->assertSame('Track A', $data[0]['title']);
        $this->assertSame(3, (int) $data[0]['playbacks_count']);

        $this->assertSame('Track B', $data[1]['title']);
        $this->assertSame(2, (int) $data[1]['playbacks_count']);

        $this->assertSame('Track C', $data[2]['title']);
        $this->assertSame(1, (int) $data[2]['playbacks_count']);
    }

    private function persistPlaybackLog(Track $track, string $amountPaid): void
    {
        $log = new PlaybackLog($track, new \DateTimeImmutable(), $amountPaid);

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    private function truncateEntities(): void
    {
        $connection = $this->entityManager->getConnection();

        $connection->executeStatement('TRUNCATE TABLE playback_log RESTART IDENTITY CASCADE');
        $connection->executeStatement('TRUNCATE TABLE track RESTART IDENTITY CASCADE');
    }
}
