<?php

namespace App\Controller;

use App\Repository\SaborRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SaborController extends AbstractController
{
    private SaborRepository $saborRepository;

    public function __construct(SaborRepository $saborRepository)
    {
        $this->saborRepository = $saborRepository;
    }

    #[Route('/sabor/{idColor}', name: 'app_sabor_color', methods: ['GET'])]
    public function showAllByColor(int $idColor): JsonResponse
    {
        $sabores = $this->saborRepository->findAllSaboresByColor($idColor);
        if (is_null($sabores)) {
            return new JsonResponse(['status' => 'No existen sabores en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($sabores, Response::HTTP_OK);
    }
}
