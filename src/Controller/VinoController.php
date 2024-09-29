<?php

namespace App\Controller;

use App\Entity\Vino;
use App\Service\VinoService;
use Exception;
use App\Exception\ColorNotFoundException;
use App\Exception\EspumosoNotFoundException;
use App\Exception\InvalidFieldException;
use App\Exception\BodegaNotFoundException;
use App\Exception\NameAlreadyExistException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/vino')]
#[OA\Tag(name: 'Wine')]
class VinoController extends AbstractController
{
    private VinoService $vinoService;

    public function __construct(VinoService $vinoService)
    {
        $this->vinoService = $vinoService;
    }

    #[Route('', name: 'app_vino_all', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all wines',
        responses: [
            new OA\Response(response: 200, description: 'Successful response')
        ]
    )]
    public function showAll(): JsonResponse
    {
        $vinos = $this->vinoService->findAllVinos();
        return new JsonResponse($vinos, Response::HTTP_OK);
    }

    #[Route('/ranking', name: 'app_vino_ranking', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all wines ranked by score',
        responses: [
            new OA\Response(response: 200, description: 'Successful response')
        ]
    )]
    public function showRanking(): JsonResponse
    {
        $vinos = $this->vinoService->findRanking();
        return new JsonResponse($vinos, Response::HTTP_OK);
    }

    #[Route('/color/{colorId}', name: 'app_vino_all_by_color', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all wines by color',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function showAllByColor(int $colorId): JsonResponse
    {
        try {
            $vinos = $this->vinoService->findAllVinosByColor($colorId);
            return new JsonResponse($vinos, Response::HTTP_OK);
        } catch (ColorNotFoundException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/espumoso/{espumosoId}', name: 'app_vino_all_by_espumoso', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all wines by sparkling',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function showAllByEspumoso(int $espumosoId): JsonResponse
    {
        try {
            $vinos = $this->vinoService->findAllVinosByEspumoso($espumosoId);
            return new JsonResponse($vinos, Response::HTTP_OK);
        } catch (EspumosoNotFoundException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/{id}', name: 'app_vino', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get a wine',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function show(?Vino $vino = null): JsonResponse
    {
        if (is_null($vino)) {
            return new JsonResponse(['status' => 'El vino no existe en la bd'], Response::HTTP_NOT_FOUND);
        }
        $vinoJson = $this->vinoService->findvino($vino);
        return new JsonResponse($vinoJson, Response::HTTP_OK);
    }

    #[Route('', name: 'app_vino_new', methods: ['POST'])]
    #[OA\Post(
        summary: 'Create a wine',
        responses: [
            new OA\Response(response: 201, description: 'Resource created successfully'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 500, description: 'Internal server error')
            ]
    )]
    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            $this->vinoService->new($data);
            return new JsonResponse(['status' => 'Vino insertado correctamente'], Response::HTTP_CREATED);
        } catch (NameAlreadyExistException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (InvalidFieldException $e) {
            return new JsonResponse(['status' => $e->getMessage(), 'errors' => $e->getErrors()], Response::HTTP_BAD_REQUEST);
        } catch (ColorNotFoundException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (BodegaNotFoundException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'app_vino_update', methods: ['PUT'])]
    #[OA\Put(
        summary: 'Update a wine',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 500, description: 'Internal server error')
        ]
    )]
    public function update(Request $request, ?Vino $vino = null): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            if (is_null($vino)) {
                return new JsonResponse(['status' => "El vino no existe en la bd"], Response::HTTP_NOT_FOUND);
            }
            $this->vinoService->update($data, $vino);
            return new JsonResponse(['status' => 'Vino actualizado correctamente'], Response::HTTP_OK);
        } catch (InvalidFieldException $e) {
            return new JsonResponse(['status' => $e->getMessage(), 'errors' => $e->getErrors()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'app_vino_delete', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Delete a wine',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 500, description: 'Internal server error')
        ]
    )]
    public function delete(?Vino $vino = null): JsonResponse
    {
        try {
            if (is_null($vino)) {
                return new JsonResponse(['status' => 'El vino no existe en la bd'], Response::HTTP_NOT_FOUND);
            }
            $this->vinoService->delete($vino);
            return new JsonResponse('El vino ha sido borrado', Response::HTTP_OK);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
