<?php

namespace App\Controller;

use App\Entity\Maridaje;
use App\Service\MaridajeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/maridaje')]
#[OA\Tag(name: 'Pairing')]
class MaridajeController extends AbstractController
{

    private MaridajeService $maridajeService;

    public function __construct(MaridajeService $maridajeService)
    {
        $this->maridajeService = $maridajeService;
    }

    #[Route('/{id}', name: 'app_maridaje', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get a pairing',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function show(?Maridaje $maridaje = null): JsonResponse
    {
        if (is_null($maridaje)) {
            return new JsonResponse(['status' => 'El maridaje no existe en la bd'], Response::HTTP_NOT_FOUND);
        }
        $maridajeJson = $this->maridajeService->findMaridaje($maridaje);
        return new JsonResponse($maridajeJson, Response::HTTP_OK);
    }

    #[Route('/color/{idColor}', name: 'app_maridaje_color_vino', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all pairings by wine color',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 400, description: 'Bad request')
        ]
    )]
    public function findAllMaridajesByColor(?int $idColor=null): JsonResponse
    {
        if ($idColor === null) {
            return new JsonResponse(['status' => 'Introduce el id del color'], Response::HTTP_BAD_REQUEST);
        }
        $maridajesJson = $this->maridajeService->findAllMaridajesByColor($idColor);
        return new JsonResponse($maridajesJson, Response::HTTP_OK);
    }

    #[Route('/espumoso/{idEspumoso}', name: 'app_maridaje_espumoso_vino', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all pairings by sparkling wine',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 400, description: 'Bad request')
        ]
    )]
    public function findAllMaridajesByEspumoso(?int $idEspumoso=null): JsonResponse
    {
        if ($idEspumoso === null) {
            return new JsonResponse(['status' => 'Introduce el id del espumoso'], Response::HTTP_BAD_REQUEST);
        }
        $maridajesJson = $this->maridajeService->findAllMaridajesByEspumoso($idEspumoso);
        return new JsonResponse($maridajesJson, Response::HTTP_OK);
    }
}
