<?php

namespace App\Controller;

use App\Service\SaborService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/sabor')]
#[OA\Tag(name: 'Flavor')]
class SaborController extends AbstractController
{
    private SaborService $saborService;

    public function __construct(SaborService $saborService)
    {
        $this->saborService = $saborService;
    }

    #[OA\Get(
        summary: 'Get all flavors by wine color',
        responses: [
            new OA\Response(response: 200, description: 'Successful response')
        ]
    )]
    #[Route('/{idColor}', name: 'app_sabor_color', methods: ['GET'])]
    public function showAllByColor(?int $idColor): JsonResponse
    {
        $sabores = $this->saborService->findAllSaboresByColor($idColor);
        return new JsonResponse($sabores, Response::HTTP_OK);
    }
}
