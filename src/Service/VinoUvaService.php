<?php

namespace App\Service;

use App\Entity\VinoUva;
use App\Entity\Vino;
use App\Repository\VinoUvaRepository;
use App\Repository\UvaRepository;
use App\Exception\UvaNotFoundException;

class VinoUvaService{

private VinoUvaRepository $vinoUvaRepository;
private UvaRepository $uvaRepository;

    public function __construct(VinoUvaRepository $vinoUvaRepository,UvaRepository $uvaRepository){
        $this->vinoUvaRepository=$vinoUvaRepository;
        $this->uvaRepository=$uvaRepository;
    }

    public function new(array $uvas, Vino $vino): void
    {
        foreach ($uvas as $uvaId) {
            $uva = $this->uvaRepository->find($uvaId);
            if(is_null($uva)){
                throw new UvaNotFoundException("La uva con ID $uvaId no existe.");
            }
            $vinoUva = new VinoUva();
            $vinoUva->setVino($vino);
            $vinoUva->setUva($uva);
            $vino->addUva($vinoUva);

            $this->vinoUvaRepository->save($vinoUva);
        }
    }
}