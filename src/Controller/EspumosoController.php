<?php

namespace App\Controller;

use App\Repository\EspumosoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EspumosoController extends AbstractController
{
    private EspumosoRepository $espumosoRepository;

    public function __construct(EspumosoRepository $espumosoRepository)
    {
        $this->espumosoRepository = $espumosoRepository;
    }


    #[Route('/espumoso', name: 'app_espumoso', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $espumosos = $this->espumosoRepository->findAllEspumosos();
        if (is_null($espumosos)) {
            return new JsonResponse(['status' => 'No existen espumososos en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($espumosos, Response::HTTP_OK);
    }
}
