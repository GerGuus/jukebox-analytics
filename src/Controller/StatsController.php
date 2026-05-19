<?php

namespace App\Controller;

use App\Service\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class StatsController extends AbstractController
{
    #[Route('/stats/top', name: 'api_v1_stats_top', methods: ['GET'])]
    public function top(StatsService $statsService): JsonResponse
    {
        return $this->json($statsService->getTopTracks());
    }
}
