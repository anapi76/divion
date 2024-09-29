<?php

namespace App\Service;

use App\Entity\Color;
use App\Repository\ColorRepository;

class ColorService
{

    private ColorRepository $colorRepository;

    public function __construct(ColorRepository $colorRepository)
    {
        $this->colorRepository = $colorRepository;
    }

    public function findAllColores(): array
    {
        $colores = $this->colorRepository->findAll();
        return [
            'info' => [
                'count' => count($colores)
            ],
            'results' => array_map([$this, 'coloresJSON'], $colores)
        ];
    }

    private function coloresJSON(Color $color): array
    {
        return [
            'id' => $color->getId(),
            'nombre' => $color->getNombre()
        ];
    }
}
