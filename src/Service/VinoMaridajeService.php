<?php

namespace App\Service;

use App\Entity\VinoMaridaje;
use App\Entity\Maridaje;
use App\Entity\Vino;
use App\Repository\VinoMaridajeRepository;
use App\Repository\MaridajeRepository;
use App\Exception\MaridajeNotFoundException;


class VinoMaridajeService
{
    private MaridajeRepository $maridajeRepository;
    private VinoMaridajeRepository $vinoMaridajeRepository;

    public function __construct(MaridajeRepository $maridajeRepository, VinoMaridajeRepository $vinoMaridajeRepository)
    {
        $this->maridajeRepository = $maridajeRepository;
        $this->vinoMaridajeRepository = $vinoMaridajeRepository;
    }

    public function new(array $maridajes, Vino $vino): void
    {
        foreach ($maridajes as $maridajeId) {
            $maridaje = $this->findMaridaje($maridajeId);
            $vinoMaridaje = new VinoMaridaje();
            $vinoMaridaje->setVino($vino);
            $vinoMaridaje->setMaridaje($maridaje);
            $vino->addMaridaje($vinoMaridaje);

            $this->vinoMaridajeRepository->save($vinoMaridaje);
        }
    }

    private function findMaridaje(int $maridajeId): Maridaje
    {
        $maridaje = $this->maridajeRepository->find($maridajeId);
        if (is_null($maridaje)) {
            throw new MaridajeNotFoundException("El maridaje con ID $maridajeId no existe.");
        }
        return $maridaje;
    }
}
