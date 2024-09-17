<?php

namespace App\Controller;

use App\Service\ColorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ColorController extends AbstractController
{

    private ColorService $colorService;

    public function __construct(ColorService $colorService)
    {
        $this->colorService = $colorService;
    }

    #[Route('/color', name: 'app_color_all', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $colores = $this->colorService->findAllColores();
        return new JsonResponse($colores, Response::HTTP_OK);
    }
}
