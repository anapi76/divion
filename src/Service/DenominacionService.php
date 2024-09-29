<?php

namespace App\Service;

use App\Entity\Denominacion;
use App\Entity\Region;
use App\Repository\DenominacionRepository;
use App\Repository\RegionRepository;
use App\Repository\UvaDoRepository;
use App\Service\UvaDoService;
use Doctrine\Common\Collections\Collection;
use DateTime;
use App\Exception\InvalidYearException;
use App\Exception\InvalidFieldException;
use App\Exception\NameAlreadyExistException;
use App\Exception\RegionNotFoundException;
use App\Exception\DenominacionDeletionException;

class DenominacionService
{
    private DenominacionRepository $denominacionRepository;
    private RegionRepository $regionRepository;
    private UvaDoService $uvaDoService;
    private UvaDoRepository $uvaDoRepository;

    public function __construct(DenominacionRepository $denominacionRepository, RegionRepository $regionRepository, UvaDoService $uvaDoService, UvaDoRepository $uvaDoRepository)
    {
        $this->denominacionRepository = $denominacionRepository;
        $this->regionRepository = $regionRepository;
        $this->uvaDoService = $uvaDoService;
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
        $errors = $this->requiredFieldsCreate($data);
        if (!empty($errors)) {
            throw new InvalidFieldException($errors);
        }
        $denominacion = $this->denominacionRepository->findOneBy(['nombre' => $data['nombre']]);
        if (!is_null($denominacion)) {
            throw new NameAlreadyExistException('El nombre ya existe en la bd');
        }
        $region = $this->findRegion($data['region']);
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
        if (!is_null($uvas)) {
            $this->uvaDoService->new($uvas, $denominacion);
        }
        $this->denominacionRepository->save($denominacion, true);
    }

    public function update(array $data, Denominacion $denominacion): void
    {
        $errors = $this->requiredFieldsUpdate($data);
        if (!empty($errors)) {
            throw new InvalidFieldException($errors);
        }
        /*$calificada = (isset($data['calificada']) && !empty($data['calificada'])) ? $data['calificada'] : false;
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
            throw new DenominacionDeletionException('La denominación de origen no puede ser borrada, tiene bodegas asociadas');
        }
        if (count($denominacion->getUvas()) > 0) {
            foreach ($denominacion->getUvas() as $uvaDo) {
                $denominacion->removeUva($uvaDo);
                $this->uvaDoRepository->remove($uvaDo);
            }
        }
        $this->denominacionRepository->remove($denominacion, true);
    }

    private function findRegion(int $idRegion): Region
    {
        $region = $this->regionRepository->find($idRegion);
        if (is_null($region)) {
            throw new RegionNotFoundException('La región no existe existe en la bd');
        }
        return $region;
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

    private function requiredFieldsCreate(array $data): array
    {
        $errors = [];

        if (!isset($data['nombre']) || empty($data['nombre'])) {
            $errors[] = "El campo 'nombre' es obligatorio.";
        }
        if (!isset($data['imagen']) || empty($data['imagen'])) {
            $errors[] = "El campo 'imagen' es obligatorio.";
        }
        if (!isset($data['imagenHistoria']) || empty($data['imagenHistoria'])) {
            $errors[] = "El campo 'imagenHistoria' es obligatorio.";
        }
        if (!isset($data['imagenUva']) || empty($data['imagenUva'])) {
            $errors[] = "El campo 'imagenUva' es obligatorio.";
        }
        if (!isset($data['logo']) || empty($data['logo'])) {
            $errors[] = "El campo 'logo' es obligatorio.";
        }
        if (!isset($data['historia']) || empty($data['historia'])) {
            $errors[] = "El campo 'historia' es obligatorio.";
        }
        if (!isset($data['descripcion']) || empty($data['descripcion'])) {
            $errors[] = "El campo 'descripcion' es obligatorio.";
        }
        if (!isset($data['descripcionVinos']) || empty($data['descripcionVinos'])) {
            $errors[] = "El campo 'descripcionVinos' es obligatorio.";
        }
        if (!isset($data['url']) || empty($data['url'])) {
            $errors[] = "El campo 'url' es obligatorio.";
        }
        if (!isset($data['region']) || empty($data['region'])) {
            $errors[] = "El campo 'region' es obligatorio.";
        }
        return $errors;
    }

    private function requiredFieldsUpdate(array $data): array
    {
        $errors = [];

        if (!isset($data['imagen']) || empty($data['imagen'])) {
            $errors[] = "El campo 'imagen' es obligatorio.";
        }
        if (!isset($data['imagenHistoria']) || empty($data['imagenHistoria'])) {
            $errors[] = "El campo 'imagenHistoria' es obligatorio.";
        }
        if (!isset($data['imagenUva']) || empty($data['imagenUva'])) {
            $errors[] = "El campo 'imagenUva' es obligatorio.";
        }
        if (!isset($data['logo']) || empty($data['logo'])) {
            $errors[] = "El campo 'logo' es obligatorio.";
        }
        if (!isset($data['historia']) || empty($data['historia'])) {
            $errors[] = "El campo 'historia' es obligatorio.";
        }
        if (!isset($data['descripcion']) || empty($data['descripcion'])) {
            $errors[] = "El campo 'descripcion' es obligatorio.";
        }
        if (!isset($data['descripcionVinos']) || empty($data['descripcionVinos'])) {
            $errors[] = "El campo 'descripcionVinos' es obligatorio.";
        }
        if (!isset($data['web']) || empty($data['web'])) {
            $errors[] = "El campo 'web' es obligatorio.";
        }
        if (!isset($data['url']) || empty($data['url'])) {
            $errors[] = "El campo 'url' es obligatorio.";
        }
        return $errors;
    }

    private function isValidCreacion(int $creacion): bool
    {
        $now = new DateTime('now');
        $year = (int) $now->format('Y');
        return ($creacion !== null && ($creacion >= 1900 && $creacion <= $year));
    }
}
