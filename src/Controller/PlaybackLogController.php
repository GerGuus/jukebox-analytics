<?php

namespace App\Controller;

use App\DTO\Request\CreatePlaybackLogRequest;
use App\DTO\Response\PlaybackLogResponseDto;
use App\Service\PlaybackLogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class PlaybackLogController extends AbstractController
{
    #[Route('/logs', name: 'api_v1_logs_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreatePlaybackLogRequest $dto,
        PlaybackLogService $playbackLogService,
    ): JsonResponse {
        try {
            $log = $playbackLogService->create(
                $dto->track_id,
                (string) $dto->amount_paid
            );
        } catch (\RuntimeException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            PlaybackLogResponseDto::fromEntity($log),
            Response::HTTP_CREATED
        );
    }
}
