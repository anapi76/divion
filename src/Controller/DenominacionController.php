<?php

namespace App\Controller;

use App\Entity\Denominacion;
use App\Repository\DenominacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DenominacionController extends AbstractController
{
    private DenominacionRepository $denominacionRepository;

    public function __construct(DenominacionRepository $denominacionRepository){
        $this->denominacionRepository=$denominacionRepository;
    }

    #[Route('/denominacion', name: 'app_denominacion_all', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $denominaciones=$this->denominacionRepository->denominacionesJSON();
        if(is_null($denominaciones)){
            return new JsonResponse(['status' => 'No existen denominaciones en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($denominaciones,Response::HTTP_OK);
    }

    #[Route('/denominacion/{id}', name: 'app_denominacion_id', methods: ['GET'])]
    public function show(Denominacion $denominacion): JsonResponse
    {
        $denominacionJson=$this->denominacionRepository->denominacionJSON($denominacion);
        return new JsonResponse($denominacionJson,Response::HTTP_OK);
    }
}
