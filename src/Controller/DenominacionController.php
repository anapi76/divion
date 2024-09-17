<?php

namespace App\Controller;

use App\Entity\Denominacion;
use App\Service\DenominacionService;
use App\Exception\RegionNotFoundException;
use App\Exception\InvalidParamsException;
use App\Exception\InvalidYearException;
use App\Exception\NameAlreadyExistException;
use App\Exception\DenominationDeletionException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DenominacionController extends AbstractController
{
    private DenominacionService $denominacionService;

    public function __construct(DenominacionService $denominacionService)
    {
        $this->denominacionService = $denominacionService;
    }

    #[Route('/denominacion', name: 'app_denominacion_all', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $denominaciones = $this->denominacionService->findAllDenominaciones();
        return new JsonResponse($denominaciones, Response::HTTP_OK);
    }

    #[Route('/denominacion/{id}', name: 'app_denominacion', methods: ['GET'])]
    public function show(?Denominacion $denominacion = null): JsonResponse
    {
        if (is_null($denominacion)) {
            return new JsonResponse(['status' => 'La denominación de origen no existe en la bd'], Response::HTTP_NOT_FOUND);
        }
        $denominacionJson = $this->denominacionService->findDenominacion($denominacion);
        return new JsonResponse($denominacionJson, Response::HTTP_OK);
    }

    #[Route('/denominacion', name: 'app_denominacion_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            $this->denominacionService->new($data);
            if (!$this->denominacionService->testInsert($data['nombre'])) {
                return new JsonResponse(['status' => 'La inserción de la denominación de origen falló'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return new JsonResponse(['status' => 'Denominación de origen insertada correctamente'], Response::HTTP_CREATED);
        } catch (InvalidParamsException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (NameAlreadyExistException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (RegionNotFoundException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/denominacion/{id}', name: 'app_denominacion_update', methods: ['PUT'])]
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
        } catch (InvalidParamsException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (InvalidYearException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/denominacion/{id}', name: 'app_denominacion_delete', methods: ['DELETE'])]
    public function delete(?Denominacion $denominacion = null): JsonResponse
    {
        try {
            if (is_null($denominacion)) {
                return new JsonResponse(['status' => 'La denominación de origen no existe en la bd'], Response::HTTP_NOT_FOUND);
            }
            $this->denominacionService->delete($denominacion, true);
            if ($this->denominacionService->testDelete($denominacion->getNombre())) {
                return new JsonResponse('La denominación de origen ha sido borrada correctamente', Response::HTTP_OK);
            } else {
                return new JsonResponse(['status' => 'La eliminación de la denominación de origen falló'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (DenominationDeletionException $e) {
            return new JsonResponse(['status' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
