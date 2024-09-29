<?php

namespace App\Service;

use App\Entity\UvaDo;
use App\Entity\Uva;
use App\Entity\Denominacion;
use App\Repository\UvaDoRepository;
use App\Repository\UvaRepository;
use App\Exception\UvaNotFoundException;

class UvaDoService
{
    private UvaDoRepository $uvaDoRepository;
    private UvaRepository $uvaRepository;

    public function __construct(UvaDoRepository $uvaDoRepository, UvaRepository $uvaRepository)
    {
        $this->uvaDoRepository = $uvaDoRepository;
        $this->uvaRepository = $uvaRepository;
    }

    public function new(array $uvas, Denominacion $denominacion): void
    {
        foreach ($uvas as $uvaId) {
            $uva = $this->findUva($uvaId);
            $uvaDo = new UvaDo();
            $uvaDo->setDenominacion($denominacion);
            $uvaDo->setUva($uva);
            $denominacion->addUva($uvaDo);

            $this->uvaDoRepository->save($uvaDo);
        }
    }

    private function finduva(int $uvaId): Uva
    {
        $uva = $this->uvaRepository->find($uvaId);
        if (is_null($uva)) {
            throw new UvaNotFoundException("La uva no existe en la BD.");
        }
        return $uva;
    }
}
