<?php

namespace App\Controller;

use App\Service\EspumosoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/espumoso')]
#[OA\Tag(name: 'Sparkling Wine')]
class EspumosoController extends AbstractController
{
    private EspumosoService $espumosoService;

    public function __construct(EspumosoService $espumosoService)
    {
        $this->espumosoService = $espumosoService;
    }

    #[Route('', name: 'app_espumoso', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all sparkling wines',
        responses: [
            new OA\Response(response: 200, description: 'Successful response')
        ]
    )]
    public function showAll(): JsonResponse
    {
        $espumosos = $this->espumosoService->findAllEspumosos();
        return new JsonResponse($espumosos, Response::HTTP_OK);
    }
}
