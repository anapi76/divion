<?php

namespace App\Controller;

use App\Entity\Maridaje;
use App\Repository\MaridajeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MaridajeController extends AbstractController
{

    private MaridajeRepository $maridajeRepository;

    public function __construct(MaridajeRepository $maridajeRepository){
        $this->maridajeRepository=$maridajeRepository;
    }

    #[Route('/maridaje/{id}', name: 'app_maridaje', methods: ['GET'])]
    public function show(?Maridaje $maridaje = null): JsonResponse
    {
        if (is_null($maridaje)) {
            return new JsonResponse(['status' => 'El maridaje no existe en la bd'], Response::HTTP_NOT_FOUND);
        }
        $maridajeJson = $this->maridajeRepository->findMaridaje($maridaje);
        return new JsonResponse($maridajeJson, Response::HTTP_OK);
    }

    #[Route('/maridaje/color/{idColor}', name: 'app_maridaje_color_vino', methods: ['GET'])]
    public function findAllMaridajesByColor(int $idColor): JsonResponse
    {
        $maridajesJson = $this->maridajeRepository->findAllMaridajesByColor($idColor);
        if (is_null($maridajesJson)) {
            return new JsonResponse(['status' => 'No existen maridajes en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($maridajesJson, Response::HTTP_OK);
    } 

    #[Route('/maridaje/espumoso/{idEspumoso}', name: 'app_maridaje_espumoso_vino', methods: ['GET'])]
    public function findAllMaridajesByEspumoso(int $idEspumoso): JsonResponse
    {
        $maridajesJson = $this->maridajeRepository->findAllMaridajesByEspumoso($idEspumoso);
        if (is_null($maridajesJson)) {
            return new JsonResponse(['status' => 'No existen maridajes en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($maridajesJson, Response::HTTP_OK);
    } 
}
