<?php

namespace App\Service;

use App\Entity\Bodega;
use App\Entity\Denominacion;
use App\Repository\BodegaRepository;
use App\Repository\DenominacionRepository;
use App\Exception\InvalidFieldException;
use App\Exception\NameAlreadyExistException;
use App\Exception\DenominacionNotFoundException;
use App\Exception\BodegaDeletionException;
use Doctrine\Common\Collections\Collection;


class BodegaService
{
    private BodegaRepository $bodegaRepository;
    private DenominacionRepository $denominacionRepository;

    public function __construct(BodegaRepository $bodegaRepository, DenominacionRepository $denominacionRepository)
    {
        $this->bodegaRepository = $bodegaRepository;
        $this->denominacionRepository = $denominacionRepository;
    }

    public function findAllBodegas(): array
    {
        $bodegas = $this->bodegaRepository->findAll();
        return [
            'info' => [
                'count' => count($bodegas)
            ],
            'results' => array_map([$this, 'bodegasJSON'], $bodegas)
        ];
    }

    public function findBodega(Bodega $bodega): array
    {
        $json['results'][] = $this->bodegasJSON($bodega);
        return $json;
    }

    public function new(array $data): void
    {
        $errors = $this->requiredFieldsCreate($data);
        if (!empty($errors)) {
            throw new InvalidFieldException($errors);
        }
        $bodega = $this->bodegaRepository->findOneBy(['nombre' => $data['nombre']]);
        if (!is_null($bodega)) {
            throw new NameAlreadyExistException('El nombre ya existe en la bd');
        }
        $denominacion = $this->findDenominacion($data['denominacion']);
        $poblacion = (!isset($data['poblacion']) || empty($data['poblacion'])) ? null : $data['poblacion'];
        $codPostal = (!isset($data['cod_postal']) || empty($data['cod_postal'])) ? null : $data['cod_postal'];
        $email = (!isset($data['email']) || empty($data['email'])) ? null : $data['email'];
        $telefono = (!isset($data['telefono']) || empty($data['telefono'])) ? null : $data['telefono'];
        $web = (!isset($data['web']) || empty($data['web'])) ? null : $data['web'];
        $url = (!isset($data['url']) || empty($data['url'])) ? null : $data['url'];

        $bodega = new Bodega();
        $bodega->setNombre($data['nombre']);
        $bodega->setDireccion($data['direccion']);
        $bodega->setProvincia($data['provincia']);
        $bodega->setPoblacion($poblacion);
        $bodega->setCodPostal($codPostal);
        $bodega->setEmail($email);
        $bodega->setTelefono($telefono);
        $bodega->setWeb($web);
        $bodega->setUrl($url);
        $bodega->setDenominacion($denominacion);
        $denominacion->addBodega($bodega);

        $this->bodegaRepository->save($bodega, true);
    }

    public function update(array $data, Bodega $bodega): void
    {
        $errors = $this->requiredFieldsUpdate($data);
        if (!empty($errors)) {
            throw new InvalidFieldException($errors);
        }
        $bodega->setDireccion($data['direccion']);
        $bodega->setPoblacion($data['poblacion']);
        $bodega->setProvincia($data['provincia']);
        $bodega->setCodPostal($data['cod_postal']);
        $bodega->setEmail($data['email']);
        $bodega->setTelefono($data['telefono']);
        $bodega->setWeb($data['web']);
        $bodega->setUrl($data['url']);

        $this->bodegaRepository->save($bodega, true);
    }

    public function delete(Bodega $bodega): void
    {
        if (count($bodega->getVinos()) > 0) {
            throw new BodegaDeletionException('La bodega no puede ser borrada, tiene vinos asociados');
        }
        $this->bodegaRepository->remove($bodega, true);
    }

    private function findDenominacion(int $idDenominacion): Denominacion
    {
        $denominacion = $this->denominacionRepository->find($idDenominacion);
        if (is_null($denominacion)) {
            throw new DenominacionNotFoundException('La denominación de origen no existe existe en la bd');
        }
        return $denominacion;
    }

    private function bodegasJSON(Bodega $bodega): array
    {
        return [
            'id' => $bodega->getId(),
            'nombre' => $bodega->getNombre(),
            'direccion' => $bodega->getDireccion(),
            'poblacion' => $bodega->getPoblacion(),
            'provincia' => $bodega->getProvincia(),
            'cod_postal' => $bodega->getCodPostal(),
            'email' => $bodega->getEmail(),
            'telefono' => $bodega->getTelefono(),
            'web' => $bodega->getWeb(),
            'url' => $bodega->getUrl(),
            'denominacion' => $bodega->getDenominacion()->getNombre(),
            'vinos' => $this->vinosJSON($bodega->getVinos())
        ];
    }

    private function vinosJSON(Collection $vinos): array
    {
        $json = [];
        foreach ($vinos as $vino) {
            $json[] = array('nombre' => $vino->getNombre(), 'url' => $vino->getUrl());
        }
        return $json;
    }

    private function requiredFieldsCreate(array $data): array
    {
        $errors = [];
        if (!isset($data['nombre']) || empty($data['nombre'])) {
            $errors[] = "El campo 'nombre' es obligatorio.";
        }
        if (!isset($data['direccion']) || empty($data['direccion'])) {
            $errors[] = "El campo 'direccion' es obligatorio.";
        }
        if (!isset($data['provincia']) || empty($data['provincia'])) {
            $errors[] = "El campo 'provincia' es obligatorio.";
        }
        if (!isset($data['url']) || empty($data['url'])) {
            $errors[] = "El campo 'url' es obligatorio.";
        }
        if (!isset($data['denominacion']) || empty($data['denominacion'])) {
            $errors[] = "El campo 'denominacion' es obligatorio.";
        }
        return $errors;
    }

    private function requiredFieldsUpdate(array $data): array
    {
        $errors = [];
        if (!isset($data['direccion']) || empty($data['direccion'])) {
            $errors[] = "El campo 'direccion' es obligatorio.";
        }
        if (!isset($data['poblacion']) || empty($data['poblacion'])) {
            $errors[] = "El campo 'poblacion¡ es obligatorio.";
        }
        if (!isset($data['provincia']) || empty($data['provincia'])) {
            $errors[] = "El campo 'provincia' es obligatorio.";
        }
        if (!isset($data['cod_postal']) || empty($data['cod_postal'])) {
            $errors[] = "El campo 'cod_postal' es obligatorio.";
        }
        if (!isset($data['email']) || empty($data['email'])) {
            $errors[] = "El campo 'email' es obligatorio.";
        }
        if (!isset($data['telefono']) || empty($data['telefono'])) {
            $errors[] = "El campo 'telefono'es obligatorio.";
        }
        if (!isset($data['web']) || empty($data['web'])) {
            $errors[] = "El campo 'web' es obligatorio.";
        }
        if (!isset($data['url']) || empty($data['url'])) {
            $errors[] = "El campo 'url' es obligatorio.";
        }
        return $errors;
    }
}
