<?php

namespace App\Controller;

use App\Service\PuntuacionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/puntuacion')]
#[OA\Tag(name: 'Score')]
class PuntuacionController extends AbstractController
{
    private PuntuacionService $puntuacionService;

    public function __construct(PuntuacionService $puntuacionService)
    {
        $this->puntuacionService = $puntuacionService;
    }

    #[Route('', name: 'app_puntuacion', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all wine scores',
        responses: [
            new OA\Response(response: 200, description: 'Successful response')
        ]
    )]
    public function showAll(): JsonResponse
    {
        $puntuaciones = $this->puntuacionService->findAllPuntuaciones();
        return new JsonResponse($puntuaciones, Response::HTTP_OK);
    }
}
