<?php

namespace App\Service;

use App\Entity\Espumoso;
use App\Repository\EspumosoRepository;

class EspumosoService {

    private EspumosoRepository $espumosoRepository;

    public function __construct(EspumosoRepository $espumosoRepository)
    {
        $this->espumosoRepository = $espumosoRepository;
    }

    public function findAllEspumosos(): array
    {
        $espumosos = $this->espumosoRepository->findAll();
        return [
            'info' => [
                'count' => count($espumosos)
            ],
            'results' => array_map([$this, 'espumososJSON'], $espumosos)
        ];
    }

    private function espumososJSON(Espumoso $espumoso): array
    {
        return [
            'id' => $espumoso->getId(),
            'nombre' => $espumoso->getNombre()
        ];
    }
}