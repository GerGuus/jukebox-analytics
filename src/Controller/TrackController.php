<?php

namespace App\Controller;

use App\DTO\Request\UpdateTrackPriceRequest;
use App\DTO\Response\TrackResponseDto;
use App\Service\TrackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/tracks')]
class TrackController extends AbstractController
{
    #[Route('/{id}/price', name: 'api_v1_tracks_price_update', methods: ['PATCH'])]
    public function updatePrice(
        int $id,
        #[MapRequestPayload] UpdateTrackPriceRequest $dto,
        TrackService $trackService,
    ): JsonResponse {
        try {
            $track = $trackService->updatePrice($id, (string) $dto->new_price);
        } catch (\RuntimeException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return $this->json(TrackResponseDto::fromEntity($track));
    }
}
