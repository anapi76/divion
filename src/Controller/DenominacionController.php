<?php

namespace App\Controller;

use App\Entity\Denominacion;
use App\Repository\DenominacionRepository;
use App\Repository\RegionRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DenominacionController extends AbstractController
{
    private DenominacionRepository $denominacionRepository;
    private RegionRepository $regionRepository;

    public function __construct(DenominacionRepository $denominacionRepository, RegionRepository $regionRepository)
    {
        $this->denominacionRepository = $denominacionRepository;
        $this->regionRepository = $regionRepository;
    }

    #[Route('/denominacion', name: 'app_denominacion_all', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $denominaciones = $this->denominacionRepository->denominacionesJSON();
        if (is_null($denominaciones)) {
            return new JsonResponse(['status' => 'No existen denominaciones en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($denominaciones, Response::HTTP_OK);
    }

    #[Route('/denominacion/{id}', name: 'app_denominacion', methods: ['GET'])]
    public function show(?Denominacion $denominacion = null): JsonResponse
    {
        if (is_null($denominacion)) {
            return new JsonResponse(['status' => 'La denominación de origen no existe en la bd'], Response::HTTP_NOT_FOUND);
        }
        $denominacionJson = $this->denominacionRepository->denominacionJSON($denominacion);
        return new JsonResponse($denominacionJson, Response::HTTP_OK);
    }

    #[Route('/denominacion', name: 'app_denominacion_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent());
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            if (!isset($data->nombre) || empty($data->nombre) || !isset($data->imagen) || empty($data->imagen) || !isset($data->historia) || empty($data->historia) || !isset($data->descripcion) || empty($data->descripcion) || !isset($data->tipo_vinos) || empty($data->tipo_vinos) || !isset($data->region) || empty($data->region)) {
                return new JsonResponse(['status' => 'Faltan parámetros'], Response::HTTP_BAD_REQUEST);
            }
            $denominacion = $this->denominacionRepository->findOneBy(['nombre' => $data->nombre]);
            if (!is_null($denominacion)) {
                return new JsonResponse(['status' => 'El nombre ya existe en la bd'], Response::HTTP_BAD_REQUEST);
            }
            $region = $this->regionRepository->findOneBy(['nombre' => $data->region]);
            $calificada = (isset($data->calificada) && !empty($data->calificada)) ? $data->calificada : false;
            $creacion = (isset($data->creacion) && !empty($data->creacion)) ? $data->creacion : null;
            $now = new DateTime('now');
            $year = (int) $now->format('Y');
            if ($creacion !== null && ($creacion < 1900 || $creacion > $year)) {
                return new JsonResponse(['status' => 'Año de creación incorrecto'], Response::HTTP_BAD_REQUEST);
            }
            $web = (isset($data->web) && !empty($data->web)) ? $data->web : null;
            $this->denominacionRepository->new($data->nombre, $calificada, $creacion, $web, $data->imagen, $data->historia, $data->descripcion, $data->tipo_vinos, $region, true);
            if ($this->denominacionRepository->testInsert($data->nombre)) {
                return new JsonResponse(['status' => 'Denominación insertada correctamente'], Response::HTTP_CREATED);
            } else {
                return new JsonResponse(['status' => 'La inserción de la denominación falló'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
