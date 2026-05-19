<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Track;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TrackControllerTest extends WebTestCase
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

    public function testUpdatePriceSuccess(): void
    {
        $track = new Track();
        $track->setTitle('Track 1');
        $track->setArtist('Artist 1');
        $track->setPrice('10.00');

        $this->entityManager->persist($track);
        $this->entityManager->flush();

        $this->client->request(
            'PATCH',
            '/api/tracks/' . $track->getId() . '/price',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'new_price' => 15.99,
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertSame($track->getId(), $data['id']);
        $this->assertSame('Track 1', $data['title']);
        $this->assertSame('Artist 1', $data['artist']);
        $this->assertSame('15.99', (string) $data['price']);
    }

    public function testUpdatePriceReturnsValidationErrorsWhenPayloadInvalid(): void
    {
        $track = new Track();
        $track->setTitle('Track 1');
        $track->setArtist('Artist 1');
        $track->setPrice('10.00');

        $this->entityManager->persist($track);
        $this->entityManager->flush();

        $this->client->request(
            'PATCH',
            '/api/tracks/' . $track->getId() . '/price',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'new_price' => -1,
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdatePriceReturns404WhenTrackNotFound(): void
    {
        $this->client->request(
            'PATCH',
            '/api/tracks/999999/price',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'new_price' => 20.00,
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
