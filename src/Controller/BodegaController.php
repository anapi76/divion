<?php

namespace App\Controller;

use App\Entity\Bodega;
use App\Exception\DenominacionNotFoundException;
use App\Exception\InvalidFieldException;
use App\Exception\NameAlreadyExistException;
use App\Exception\BodegaDeletionException;
use App\Service\BodegaService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/bodega')]
#[OA\Tag(name: 'Winery')]
class BodegaController extends AbstractController implements IBodegaController
{
    private BodegaService $bodegaService;

    public function __construct(BodegaService $bodegaService)
    {
        $this->bodegaService = $bodegaService;
    }

    #[Route('', name: 'app_bodega_all', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all wineries',
        responses: [
            new OA\Response(response: 200, description: 'Successful response')
        ]
    )]
    public function showAll(): JsonResponse
    {
        $bodegas = $this->bodegaService->findAllBodegas();
        return new JsonResponse($bodegas, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_bodega', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get a winery',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function show(?Bodega $bodega = null): JsonResponse
    {
        if (is_null($bodega)) {
            return new JsonResponse(['status' => 'La bodega no existe en la bd'], Response::HTTP_NOT_FOUND);
        }
        $bodegaJson = $this->bodegaService->findbodega($bodega);
        return new JsonResponse($bodegaJson, Response::HTTP_OK);
    }

    #[Route('', name: 'app_bodega_new', methods: ['POST'])]
    #[OA\Post(
        summary: 'Create a winery',
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
            $this->bodegaService->new($data);
            return new JsonResponse(['status' => 'Bodega insertada correctamente'], Response::HTTP_CREATED);
        } catch (InvalidFieldException $e) {
            return new JsonResponse(['status' => $e->getMessage(), 'errors' => $e->getErrors()], Response::HTTP_BAD_REQUEST);
        } catch (NameAlreadyExistException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (DenominacionNotFoundException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'app_bodega_update', methods: ['PUT'])]
    #[OA\Put(
        summary: 'Update a winery',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 500, description: 'Internal server error')
        ]
    )]
    public function update(Request $request, ?Bodega $bodega = null): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            if (is_null($bodega)) {
                return new JsonResponse(['status' => 'La bodega no existe en la bd'], Response::HTTP_NOT_FOUND);
            }
            $this->bodegaService->update($data, $bodega);
            return new JsonResponse(['status' => 'Bodega actualizada correctamente'], Response::HTTP_OK);
        } catch (InvalidFieldException $e) {
            return new JsonResponse(['status' => $e->getMessage(), 'errors' => $e->getErrors()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'app_bodega_delete', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Delete a winery',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 500, description: 'Internal server error')
        ]
    )]
    public function delete(?Bodega $bodega = null): JsonResponse
    {
        try {
            if (is_null($bodega)) {
                return new JsonResponse(['status' => 'La bodega no existe en la bd'], Response::HTTP_NOT_FOUND);
            }
            $this->bodegaService->delete($bodega, true);
            return new JsonResponse('La bodega ha sido borrada correctamente', Response::HTTP_OK);
        } catch (BodegaDeletionException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
