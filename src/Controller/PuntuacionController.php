<?php

namespace App\Controller;

use App\Repository\PuntuacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PuntuacionController extends AbstractController
{

    private PuntuacionRepository $puntuacionRepository;

    public function __construct(PuntuacionRepository $puntuacionRepository)
    {
        $this->puntuacionRepository = $puntuacionRepository;
    }

    #[Route('/puntuacion', name: 'app_puntuacion', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $puntuaciones = $this->puntuacionRepository->findAllPuntuaciones();
        if (is_null($puntuaciones)) {
            return new JsonResponse(['status' => 'No existen puntuaciones en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($puntuaciones, Response::HTTP_OK);
    }
}
