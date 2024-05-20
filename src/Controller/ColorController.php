<?php

namespace App\Controller;

use App\Repository\ColorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ColorController extends AbstractController
{

    private ColorRepository $colorRepository;

    public function __construct(ColorRepository $colorRepository)
    {
        $this->colorRepository = $colorRepository;
    }

    #[Route('/color', name: 'app_color_all', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $colores = $this->colorRepository->findAllColores();
        if (is_null($colores)) {
            return new JsonResponse(['status' => 'No existen colores en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($colores, Response::HTTP_OK);
    }
}
