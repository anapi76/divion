<?php

namespace App\Service;

use App\Entity\Bodega;
use App\Repository\BodegaRepository;
use App\Repository\DenominacionRepository;
use App\Exception\InvalidParamsException;
use App\Exception\NameAlreadyExistException;
use App\Exception\DenominationNotFoundException;
use App\Exception\WineryCantDeleteException;
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
        try {
            if (!$this->requiredFields($data)) {
                throw new InvalidParamsException('Faltan parámetros');
            }
            $bodega = $this->bodegaRepository->findOneBy(['nombre' => $data['nombre']]);
            if (!is_null($bodega)) {
                throw new NameAlreadyExistException('El nombre ya existe en la bd');
            }
            $denominacion = $this->denominacionRepository->find($data['denominacion']);
            if (is_null($denominacion)) {
                throw new DenominationNotFoundException('La denominación de origen no existe existe en la bd');
            }
            $poblacion = (!isset($data['poblacion']) || empty($data['poblacion'])) ? null : $data['poblacion'];
            $codPostal = (!isset($data['cod_postal']) || empty($data['cod_postal'])) ? null : $data['cod_postal'];
            $email = (!isset($data['email']) || empty($data['email'])) ? null : $data['email'];
            $telefono = (!isset($data['telefono']) || empty($data['telefono'])) ? null : $data['telefono'];
            $web = (!isset($data['web']) || empty($data['web'])) ? null : $data['web'];
            $url = (!isset($data['url']) || empty($data['url'])) ? null : $data['url'];

            $bodega = new Bodega();
            $bodega->setNombre($data['nombre']);
            $bodega->setDireccion($data['direccion']);
            $bodega->setPoblacion($poblacion);
            $bodega->setProvincia($data['provincia']);
            $bodega->setCodPostal($codPostal);
            $bodega->setEmail($email);
            $bodega->setTelefono($telefono);
            $bodega->setWeb($web);
            $bodega->setUrl($url);
            $bodega->setDenominacion($denominacion);
            $denominacion->addBodega($bodega);
            $this->bodegaRepository->save($bodega, true);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(array $data, Bodega $bodega): void
    {
        try {
            if (!$this->requiredAllFields($data)) {
                throw new InvalidParamsException('Faltan parámetros');
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
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete(Bodega $bodega): void
    {
        if (count($bodega->getVinos()) > 0) {
            throw new WineryCantDeleteException('La bodega no puede ser borrada, tiene vinos asociados');
        } 
        $this->bodegaRepository->remove($bodega,true);
    }

    public function testInsert(string $nombre): bool
    {
        $entidad = $this->bodegaRepository->findOneBy(['nombre' => $nombre]);
        if (empty($entidad))
            return false;
        else {
            return true;
        }
    }

    public function testDelete(string $nombre): bool
    {
        $entidad = $this->bodegaRepository->findOneBy(['nombre' => $nombre]);
        if (empty($entidad))
            return true;
        else {
            return false;
        }
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

    private function requiredFields(array $data): bool
    {
        return (isset($data['nombre']) && !empty($data['nombre']) &&
            isset($data['direccion']) && !empty($data['direccion']) &&
            isset($data['provincia']) && !empty($data['provincia']) &&
            isset($data['url']) && !empty($data['url']) &&
            isset($data['denominacion']) && !empty($data['denominacion']));
    }

    private function requiredAllFields(array $data): bool
    {
        return (isset($data['direccion']) && !empty($data['direccion']) &&
            isset($data['poblacion']) && !empty($data['poblacion']) &&
            isset($data['provincia']) && !empty($data['provincia']) &&
            isset($data['cod_postal']) && !empty($data['cod_postal']) &&
            isset($data['email']) && !empty($data['email']) &&
            isset($data['telefono']) && !empty($data['telefono']) &&
            isset($data['web']) && !empty($data['web']) &&
            isset($data['url']) && !empty($data['url'])
        );
    }
}
