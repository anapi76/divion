<?php

namespace App\Service;

use App\Repository\SaborRepository;
use App\Entity\Sabor;

class SaborService
{

    private SaborRepository $saborRepository;

    public function __construct(SaborRepository $saborRepository)
    {
        $this->saborRepository = $saborRepository;
    }

    public function findAllSaboresByColor(int $idColor): array
    {
        $sabores = $this->saborRepository->findAllSaboresByColor($idColor);
        return [
            'info' => ['count' => count($sabores)],
            'results' => array_map([$this, 'saboresJSON'], $sabores)
        ];
    }

    private function saboresJSON(Sabor $sabor): array
    {
        return [
            'id' => $sabor->getId(),
            'nombre' => $sabor->getNombre()
        ];
    }
}
