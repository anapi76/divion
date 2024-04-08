<?php

namespace App\Controller;

use App\Entity\Bodega;
use App\Repository\BodegaRepository;
use App\Repository\DenominacionRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BodegaController extends AbstractController
{

    private BodegaRepository $bodegaRepository;
    private DenominacionRepository $denominacionRepository;

    public function __construct(BodegaRepository $bodegaRepository,DenominacionRepository $denominacionRepository)
    {
        $this->bodegaRepository = $bodegaRepository;
        $this->denominacionRepository=$denominacionRepository;
    }

    #[Route('/bodega', name: 'app_bodega_all', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $bodegas = $this->bodegaRepository->findAllBodegas();
        if (is_null($bodegas)) {
            return new JsonResponse(['status' => 'No existen bodegas en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($bodegas, Response::HTTP_OK);
    }

    #[Route('/bodega/{id}', name: 'app_bodega', methods: ['GET'])]
    public function show(?Bodega $bodega = null): JsonResponse
    {
        if (is_null($bodega)) {
            return new JsonResponse(['status' => 'La bodega no existe en la bd'], Response::HTTP_NOT_FOUND);
        }
        $bodegaJson = $this->bodegaRepository->findbodega($bodega);
        return new JsonResponse($bodegaJson, Response::HTTP_OK);
    }

    #[Route('/bodega', name: 'app_bodega_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent());
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            if (!isset($data->nombre) || empty($data->nombre) ||!isset($data->direccion) || empty($data->direccion) || !isset($data->provincia) || empty($data->provincia) || !isset($data->denominacion) || empty($data->denominacion)) {
                return new JsonResponse(['status' => 'Faltan parámetros'], Response::HTTP_BAD_REQUEST);
            }
            $bodega = $this->bodegaRepository->findOneBy(['nombre' => $data->nombre]);
            if (!is_null($bodega)) {
                return new JsonResponse(['status' => 'El nombre ya existe en la bd'], Response::HTTP_BAD_REQUEST);
            }
            $denominacion = $this->denominacionRepository->findOneBy(['nombre' => $data->denominacion]);
            if (is_null($denominacion)) {
                return new JsonResponse(['status' => 'La denominación de origen no existe existe en la bd o el nombre es incorrecto'], Response::HTTP_BAD_REQUEST);
            }
            $poblacion = (isset($data->poblacion) && !empty($data->poblacion)) ? $data->poblacion : null;
            $codPostal = (isset($data->cod_postal) && !empty($data->cod_postal)) ? $data->cod_postal : null;
            $email = (isset($data->email) && !empty($data->email)) ? $data->email : null;
            $telefono = (isset($data->telefono) && !empty($data->telefono)) ? $data->telefono : null;
            $web = (isset($data->web) && !empty($data->web)) ? $data->web : null;

            $this->bodegaRepository->new($data->nombre, $data->direccion,$poblacion, $data->provincia, $codPostal, $email, $telefono, $web, $denominacion, true);
            if (!$this->bodegaRepository->testInsert($data->nombre)) {
                return new JsonResponse(['status' => 'La inserción de la bodega falló'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return new JsonResponse(['status' => 'Bodega insertada correctamente'], Response::HTTP_CREATED);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/bodega/{id}', name: 'app_bodega_update', methods: ['PUT'])]
    public function update(Request $request, ?Bodega $bodega = null): JsonResponse
    {
        try {
            $data = json_decode($request->getContent());
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            if (is_null($bodega)) {
                return new JsonResponse(['status' => "La bodega no existe en la bd"], Response::HTTP_NOT_FOUND);
            }
            $direccion = (isset($data->direccion) && !empty($data->direccion)) ? $data->direccion : null;
            $poblacion = (isset($data->poblacion) && !empty($data->poblacion)) ? $data->poblacion : null;
            $provincia = (isset($data->provincia) && !empty($data->provincia)) ? $data->provincia : null;
            $codPostal = (isset($data->cod_postal) && !empty($data->cod_postal)) ? $data->cod_postal : null;
            $email = (isset($data->email) && !empty($data->email)) ? $data->email : null;
            $telefono = (isset($data->telefono) && !empty($data->telefono)) ? $data->telefono : null;
            $web = (isset($data->web) && !empty($data->web)) ? $data->web : null;
            if(!$this->bodegaRepository->update($bodega, $direccion, $poblacion, $provincia, $codPostal, $email, $telefono, $web, true)){
                return new JsonResponse(['status' => 'La bodega no se ha actualizado'], Response::HTTP_BAD_REQUEST);
            }
            return new JsonResponse(['status' => 'Bodega actualizada correctamente'], Response::HTTP_OK);
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
            if (count($bodega->getVinos()) > 0) {
                return new JsonResponse(['status' => 'La bodega no puede ser borrada, tiene vinos asociados'], Response::HTTP_BAD_REQUEST);
            }
            $this->bodegaRepository->remove($bodega, true);
            if ($this->bodegaRepository->testDelete($bodega->getNombre())) {
                return new JsonResponse('La bodega ha sido borrada', Response::HTTP_OK);
            } else {
                return new JsonResponse(['status' => 'La eliminación de la bodega falló'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
 
}
