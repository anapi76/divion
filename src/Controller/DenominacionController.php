<?php

namespace App\Controller;

use App\Entity\Denominacion;
use App\Service\DenominacionService;
use App\Exception\RegionNotFoundException;
use App\Exception\InvalidYearException;
use App\Exception\InvalidFieldException;
use App\Exception\NameAlreadyExistException;
use App\Exception\DenominacionDeletionException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/denominacion')]
#[OA\Tag(name: 'Protected Designation of Origin')]
class DenominacionController extends AbstractController
{
    private DenominacionService $denominacionService;

    public function __construct(DenominacionService $denominacionService)
    {
        $this->denominacionService = $denominacionService;
    }

    #[Route('', name: 'app_denominacion_all', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get all denominations',
        responses: [
            new OA\Response(response: 200, description: 'Successful response')
        ]
    )]
    public function showAll(): JsonResponse
    {
        $denominaciones = $this->denominacionService->findAllDenominaciones();
        return new JsonResponse($denominaciones, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_denominacion', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get a denomination',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function show(?Denominacion $denominacion = null): JsonResponse
    {
        if (is_null($denominacion)) {
            return new JsonResponse(['status' => 'La denominación de origen no existe en la bd'], Response::HTTP_NOT_FOUND);
        }
        $denominacionJson = $this->denominacionService->findDenominacion($denominacion);
        return new JsonResponse($denominacionJson, Response::HTTP_OK);
    }

    #[Route('', name: 'app_denominacion_new', methods: ['POST'])]
    #[OA\Post(
        summary: 'Create a denomination',
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
            $this->denominacionService->new($data);
            return new JsonResponse(['status' => 'Denominación de origen insertada correctamente'], Response::HTTP_CREATED);
        } catch (InvalidFieldException $e) {
            return new JsonResponse(['status' => $e->getMessage(), 'errors' => $e->getErrors()], Response::HTTP_BAD_REQUEST);
        } catch (InvalidYearException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (NameAlreadyExistException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (RegionNotFoundException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'app_denominacion_update', methods: ['PUT'])]
    #[OA\Put(
        summary: 'Update a denomination',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 500, description: 'Internal server error')
        ]
    )]
    public function update(Request $request, ?Denominacion $denominacion = null): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            if (is_null($denominacion)) {
                return new JsonResponse(['status' => "La denominación de origen no existe en la bd"], Response::HTTP_NOT_FOUND);
            }
            $this->denominacionService->update($data, $denominacion);
            return new JsonResponse(['status' => 'Denominación de origen actualizada correctamente'], Response::HTTP_OK);
        } catch (InvalidFieldException $e) {
            return new JsonResponse(['status' => $e->getMessage(), 'errors' => $e->getErrors()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'app_denominacion_delete', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Delete a denomination',
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 500, description: 'Internal server error')
        ]
    )]
    public function delete(?Denominacion $denominacion = null): JsonResponse
    {
        try {
            if (is_null($denominacion)) {
                return new JsonResponse(['status' => 'La denominación de origen no existe en la bd'], Response::HTTP_NOT_FOUND);
            }
            $this->denominacionService->delete($denominacion, true);
            return new JsonResponse('La denominación de origen ha sido borrada correctamente', Response::HTTP_OK);
        } catch (DenominacionDeletionException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
