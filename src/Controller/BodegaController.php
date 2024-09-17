<?php

namespace App\Controller;

use App\Entity\Bodega;
use App\Exception\DenominationNotFoundException;
use App\Exception\InvalidParamsException;
use App\Exception\NameAlreadyExistException;
use App\Exception\WineryDeletionException;
use App\Service\BodegaService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BodegaController extends AbstractController
{
    private BodegaService $bodegaService;

    public function __construct(BodegaService $bodegaService)
    {
        $this->bodegaService = $bodegaService;
    }

    #[Route('/bodega', name: 'app_bodega_all', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $bodegas = $this->bodegaService->findAllBodegas();
        return new JsonResponse($bodegas, Response::HTTP_OK);
    }

    #[Route('/bodega/{id}', name: 'app_bodega', methods: ['GET'])]
    public function show(?Bodega $bodega = null): JsonResponse
    {
        if (is_null($bodega)) {
            return new JsonResponse(['status' => 'La bodega no existe en la bd'], Response::HTTP_NOT_FOUND);
        }
        $bodegaJson = $this->bodegaService->findbodega($bodega);
        return new JsonResponse($bodegaJson, Response::HTTP_OK);
    }

    #[Route('/bodega', name: 'app_bodega_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            $this->bodegaService->new($data);
            if (!$this->bodegaService->testInsert($data['nombre'])) {
                return new JsonResponse(['status' => 'La inserción de la bodega falló'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return new JsonResponse(['status' => 'Bodega insertada correctamente'], Response::HTTP_CREATED);
        } catch (InvalidParamsException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (NameAlreadyExistException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (DenominationNotFoundException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/bodega/{id}', name: 'app_bodega_update', methods: ['PUT'])]
    public function update(Request $request, ?Bodega $bodega = null): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            if (is_null($bodega)) {
                return new JsonResponse(['status' => 'La bodega no existe en la bd'], Response::HTTP_BAD_REQUEST);
            }
            $this->bodegaService->update($data, $bodega);
            return new JsonResponse(['status' => 'Bodega actualizada correctamente'], Response::HTTP_OK);
        } catch (InvalidParamsException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/bodega/{id}', name: 'app_bodega_delete', methods: ['DELETE'])]
    public function delete(?Bodega $bodega = null): JsonResponse
    {
        try {
            if (is_null($bodega)) {
                return new JsonResponse(['status' => 'La bodega no existe en la bd'], Response::HTTP_NOT_FOUND);
            }
            $this->bodegaService->delete($bodega, true);
            if ($this->bodegaService->testDelete($bodega->getNombre())) {
                return new JsonResponse('La bodega ha sido borrada correctamente', Response::HTTP_OK);
            } else {
                return new JsonResponse(['status' => 'La eliminación de la bodega falló'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (WineryDeletionException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
