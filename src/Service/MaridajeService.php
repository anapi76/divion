<?php

namespace App\Service;

use App\Entity\Maridaje;
use App\Repository\MaridajeRepository;

class MaridajeService
{
    private MaridajeRepository $maridajeRepository;

    public function __construct(MaridajeRepository $maridajeRepository)
    {
        $this->maridajeRepository = $maridajeRepository;
    }

    public function findMaridaje(Maridaje $maridaje): array
    {
        $json['results'][] = $this->maridajesJSON($maridaje);
        return $json;
    }

    public function findAllMaridajesByColor(int $idColor): array
    {
        $maridajes = $this->maridajeRepository->findAllMaridajesByColor($idColor);
        return [
            'info' => [
                'count' => count($maridajes)
            ],
            'results' => array_map([$this, 'maridajesJSON'], $maridajes)
        ];
    }

    public function findAllMaridajesByEspumoso(int $idEspumoso): array
    {
        $maridajes = $this->maridajeRepository->findAllMaridajesByEspumoso($idEspumoso);
        return [
            'info' => [
                'count' => count($maridajes)
            ],
            'results' => array_map([$this, 'maridajesJSON'], $maridajes)
        ];
    }

    private function maridajesJSON(Maridaje $maridaje): array
    {
        return [
            'id' => $maridaje->getId(),
            'nombre' => $maridaje->getNombre()
        ];
    }
}
