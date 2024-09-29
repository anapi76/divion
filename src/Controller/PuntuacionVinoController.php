<?php

namespace App\Controller;

use App\Entity\PuntuacionVino;
use App\Service\PuntuacionVinoService;
use Exception;
use App\Exception\InvalidFieldException;
use App\Exception\VinoNotFoundException;
use App\Exception\PuntuacionNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/valoracion')]
#[OA\Tag(name: 'Rating')]
class PuntuacionVinoController extends AbstractController
{
    private PuntuacionVinoService $puntuacionVinoService;

    public function __construct(PuntuacionVinoService $puntuacionVinoService)
    {
        $this->puntuacionVinoService = $puntuacionVinoService;
    }

    #[Route('', name: 'app_puntuacion_all', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all ratings',
        responses: [
            new OA\Response(response: 200, description: 'Successful response')
        ]
    )]
    public function showAll(): JsonResponse
    {
        $valoraciones = $this->puntuacionVinoService->findAllOrderedByValoraciones();
        return new JsonResponse($valoraciones, Response::HTTP_OK);
    }

    #[Route('/vino/{idVino}', name: 'app_puntuacion_vino', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get a wine rating',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 400, description: 'Bad request')
        ]
    )]
    public function show(?int $idVino = null): JsonResponse
    {
        try {
            if ($idVino === null) {
                return new JsonResponse(['status' => 'Introduce el id del vino'], Response::HTTP_BAD_REQUEST);
            }
            $puntuaciones = $this->puntuacionVinoService->findAllByVino($idVino);
            return new JsonResponse($puntuaciones, Response::HTTP_OK);
        } catch (VinoNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    #[Route('', name: 'app_puntuacion_new', methods: ['POST'])]
    #[OA\Post(
        summary: 'Create a wine rating',
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
            $this->puntuacionVinoService->new($data);
            return new JsonResponse(['status' => 'Puntuación insertada correctamente'], Response::HTTP_CREATED);
        } catch (InvalidFieldException $e) {
            return new JsonResponse(['status' => $e->getMessage(),'errors' => $e->getErrors()], Response::HTTP_BAD_REQUEST);
        } catch (VinoNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch (PuntuacionNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'app_puntuacion_delete', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Delete a rating',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 500, description: 'Internal server error')
        ]
    )]
    public function delete(?PuntuacionVino $puntuacionVino = null): JsonResponse
    {
        try {
            if (is_null($puntuacionVino)) {
                return new JsonResponse(['status' => 'La puntuación no existe en la bd'], Response::HTTP_NOT_FOUND);
            }
            $this->puntuacionVinoService->delete($puntuacionVino);
            return new JsonResponse('La puntuación ha sido borrada', Response::HTTP_OK);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
