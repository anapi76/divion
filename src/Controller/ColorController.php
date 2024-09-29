<?php

namespace App\Controller;

use App\Service\ColorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/color')]
#[OA\Tag(name: 'Color Wine')]
class ColorController extends AbstractController
{

    private ColorService $colorService;

    public function __construct(ColorService $colorService)
    {
        $this->colorService = $colorService;
    }

    #[Route('', name: 'app_color_all', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all wine colors',
        responses: [
            new OA\Response(response: 200, description: 'Successful response')
        ]
    )]
    public function showAll(): JsonResponse
    {
        $colores = $this->colorService->findAllColores();
        return new JsonResponse($colores, Response::HTTP_OK);
    }
}
