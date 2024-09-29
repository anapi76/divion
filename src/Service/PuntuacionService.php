<?php

namespace App\Service;

use App\Entity\Puntuacion;
use App\Repository\PuntuacionRepository;

class PuntuacionService
{

    private PuntuacionRepository $puntuacionRepository;

    public function __construct(PuntuacionRepository $puntuacionRepository)
    {
        $this->puntuacionRepository = $puntuacionRepository;
    }


    public function findAllPuntuaciones(): array
    {
        $puntuaciones = $this->puntuacionRepository->findAll();
        return [
            'info' => [
                'count' => count($puntuaciones)
            ],
            'results' => array_map([$this, 'puntuacionesJSON'], $puntuaciones)
        ];
    }

    private function puntuacionesJSON(Puntuacion $puntuacion): mixed
    {
        return [
            'id' => $puntuacion->getId(),
            'puntos' => $puntuacion->getPuntos(),
            'descripcion' => $puntuacion->getDescripcion()
        ];
    }
}
