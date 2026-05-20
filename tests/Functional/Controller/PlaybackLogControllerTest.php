<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Track;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PlaybackLogControllerTest extends WebTestCase
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

    public function testCreatePlaybackLogSuccess(): void
    {
        $track = new Track('Test Track', 'Test Artist', '12.50');

        $this->entityManager->persist($track);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/api/logs',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'track_id' => $track->getId(),
                'amount_paid' => 12.50,
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertSame($track->getId(), $data['track_id']);
        $this->assertSame('12.5', (string) $data['amount_paid']);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('played_at', $data);
    }

    public function testCreatePlaybackLogReturnsValidationErrorsWhenPayloadInvalid(): void
    {
        $this->client->request(
            'POST',
            '/api/logs',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'track_id' => null,
                'amount_paid' => -10,
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCreatePlaybackLogReturns404WhenTrackNotFound(): void
    {
        $this->client->request(
            'POST',
            '/api/logs',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'track_id' => 999999,
                'amount_paid' => 15.00,
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertSame('Track not found', $data['message']);
    }

    private function truncateEntities(): void
    {
        $connection = $this->entityManager->getConnection();

        $connection->executeStatement('TRUNCATE TABLE playback_log RESTART IDENTITY CASCADE');
        $connection->executeStatement('TRUNCATE TABLE track RESTART IDENTITY CASCADE');
    }
}
