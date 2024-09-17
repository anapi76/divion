<?php

namespace App\Service;

use App\Entity\Denominacion;
use App\Repository\DenominacionRepository;
use App\Repository\RegionRepository;
use App\Repository\UvaDoRepository;
use Doctrine\Common\Collections\Collection;
use DateTime;
use App\Exception\InvalidParamsException;
use App\Exception\InvalidYearException;
use App\Exception\NameAlreadyExistException;
use App\Exception\RegionNotFoundException;
use App\Exception\DenominationDeletionException;

class DenominacionService
{
    private DenominacionRepository $denominacionRepository;
    private RegionRepository $regionRepository;
    private UvaDoRepository $uvaDoRepository;

    public function __construct(DenominacionRepository $denominacionRepository, RegionRepository $regionRepository, UvaDoRepository $uvaDoRepository)
    {
        $this->denominacionRepository = $denominacionRepository;
        $this->regionRepository = $regionRepository;
        $this->uvaDoRepository = $uvaDoRepository;
    }

    public function findAllDenominaciones(): array
    {
        $denominaciones = $this->denominacionRepository->findAll();
        return [
            'info' => [
                'count' => count($denominaciones)
            ],
            'results' => array_map([$this, 'denominacionesJSON'], $denominaciones)
        ];
    }

    public function findDenominacion(Denominacion $denominacion): array
    {
        $json['results'][] = $this->denominacionesJSON($denominacion);
        return $json;
    }

    public function new(array $data): void
    {
        if (!$this->requiredFieldsCreate($data)) {
            throw new InvalidParamsException('Faltan parámetros');
        }
        $denominacion = $this->denominacionRepository->findOneBy(['nombre' => $data['nombre']]);
        if (!is_null($denominacion)) {
            throw new NameAlreadyExistException('El nombre ya existe en la bd');
        }
        $region = $this->regionRepository->find($data['region']);
        if (is_null($region)) {
            throw new RegionNotFoundException('La región no existe existe en la bd');
        }
        $calificada = (isset($data['calificada']) && !empty($data['calificada'])) ? $data['calificada'] : false;
        $creacion = (!isset($data['creacion']) || empty($data['creacion'])) ? null : $data['creacion'];
        if (!is_null($creacion) && !$this->isValidCreacion($creacion)) {
            throw new InvalidYearException('Año de creación incorrecto');
        }
        $web = (!isset($data['web']) || empty($data['web'])) ? null : $data['web'];
        $uvas = (isset($data['uvasPermitidas']) && !empty($data['uvasPermitidas'])) ? $data['uvasPermitidas'] : null;

        $denominacion = new Denominacion();
        $denominacion->setNombre($data['nombre']);
        $denominacion->setCalificada($calificada);
        $denominacion->setCreacion($creacion);
        $denominacion->setWeb($web);
        $denominacion->setImagen($data['imagen']);
        $denominacion->setImagenHistoria($data['imagenHistoria']);
        $denominacion->setLogo($data['logo']);
        $denominacion->setHistoria($data['historia']);
        $denominacion->setImagenUva($data['imagenUva']);
        $denominacion->setDescripcion($data['descripcion']);
        $denominacion->setDescripcionVinos($data['descripcionVinos']);
        $denominacion->setUrl($data['url']);
        $denominacion->setRegion($region);
        $this->uvaDoRepository->new($uvas, $denominacion);

        $this->denominacionRepository->save($denominacion, true);
    }

    public function update(array $data, Denominacion $denominacion): void
    {
        if (!$this->requiredFieldsUpdate($data)) {
            throw new InvalidParamsException('Faltan parámetros');
        }
        /*         $calificada = (isset($data['calificada']) && !empty($data['calificada'])) ? $data['calificada'] : false;
        $denominacion->setCalificada($calificada); */
        $denominacion->setImagen($data['imagen']);
        $denominacion->setImagenHistoria($data['imagenHistoria']);
        $denominacion->setImagenUva($data['imagenUva']);
        $denominacion->setLogo($data['logo']);
        $denominacion->setHistoria($data['historia']);
        $denominacion->setDescripcion($data['descripcion']);
        $denominacion->setDescripcionVinos($data['descripcionVinos']);
        $denominacion->setWeb($data['web']);
        $denominacion->setUrl($data['url']);

        $this->denominacionRepository->save($denominacion, true);
    }

    public function delete(Denominacion $denominacion): void
    {
        if (count($denominacion->getBodegas()) > 0) {
            throw new DenominationDeletionException('La denominación de origen no puede ser borrada, tiene bodegas asociadas');
        }
        if (count($denominacion->getUvas()) > 0) {
            foreach ($denominacion->getUvas() as $uvaDo) {
                $denominacion->removeUva($uvaDo);
                $this->uvaDoRepository->remove($uvaDo);
            }
        }
        $this->denominacionRepository->remove($denominacion, true);
    }

    public function testInsert(string $nombre): bool
    {
        $entidad = $this->denominacionRepository->findOneBy(['nombre' => $nombre]);
        if (empty($entidad))
            return false;
        else {
            return true;
        }
    }

    public function testDelete(string $nombre): bool
    {
        $entidad = $this->denominacionRepository->findOneBy(['nombre' => $nombre]);
        if (empty($entidad))
            return true;
        else {
            return false;
        }
    }

    private function denominacionesJSON(Denominacion $denominacion): array
    {
        $calificada = ($denominacion->isCalificada()) ? 'Denominación de origen calificada' : '';
        return [
            'id' => $denominacion->getId(),
            'nombre' => $denominacion->getNombre(),
            'calificada' => $calificada,
            'creacion' => $denominacion->getCreacion(),
            'web' => $denominacion->getWeb(),
            'imagen' => $denominacion->getImagen(),
            'imagen_historia' => $denominacion->getImagenHistoria(),
            'imagen_uva' => $denominacion->getImagenUva(),
            'logo' => $denominacion->getLogo(),
            'historia' => $denominacion->getHistoria(),
            'descripcion' => $denominacion->getDescripcion(),
            'descripcion_vinos' => $denominacion->getDescripcionVinos(),
            'url' => $denominacion->getUrl(),
            'region' => $denominacion->getRegion()->getNombre(),
            'bodegas' => $this->bodegasJSON($denominacion->getBodegas()),
            'uvas_permitidas' => $this->uvasJSON($denominacion->getUvas()),
        ];
    }

    private function bodegasJSON(Collection $bodegas): array
    {
        $json = array();
        foreach ($bodegas as $bodega) {
            $json[] = array('nombre' => $bodega->getNombre(), 'web' => $bodega->getWeb());
        }
        return $json;
    }

    private function uvasJSON(Collection $uvas): array
    {
        $json = array();
        foreach ($uvas as $uva) {
            $json[] = $uva->getUva()->getNombre();
        }
        return $json;
    }

    private function requiredFieldsCreate(array $data): bool
    {
        return (isset($data['nombre']) && !empty($data['nombre'])
            && isset($data['imagen']) && !empty($data['imagen'])
            && isset($data['imagenHistoria']) && !empty($data['imagenHistoria'])
            && isset($data['imagenUva']) && !empty($data['imagenUva'])
            && isset($data['logo']) && !empty($data['logo'])
            && isset($data['historia']) && !empty($data['historia'])
            && isset($data['descripcion']) && !empty($data['descripcion'])
            && isset($data['descripcionVinos']) && !empty($data['descripcionVinos'])
            && isset($data['url']) && !empty($data['url'])
            && isset($data['region']) && !empty($data['region']));
    }

    private function requiredFieldsUpdate(array $data): bool
    {
        return (
            isset($data['imagen']) && !empty($data['imagen'])
            && isset($data['imagenHistoria']) && !empty($data['imagenHistoria'])
            && isset($data['imagenUva']) && !empty($data['imagenUva'])
            && isset($data['logo']) && !empty($data['logo'])
            && isset($data['historia']) && !empty($data['historia'])
            && isset($data['descripcion']) && !empty($data['descripcion'])
            && isset($data['descripcionVinos']) && !empty($data['descripcionVinos'])
            && isset($data['web']) && !empty($data['web'])
            && isset($data['url']) && !empty($data['url']));
    }

    private function isValidCreacion(int $creacion): bool
    {
        $now = new DateTime('now');
        $year = (int) $now->format('Y');
        return ($creacion !== null && ($creacion >= 1900 && $creacion <= $year));
    }
}
