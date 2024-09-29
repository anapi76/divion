<?php

namespace App\Service;

use App\Entity\Vino;
use App\Entity\Color;
use App\Entity\Espumoso;
use App\Entity\Bodega;
use App\Exception\ColorNotFoundException;
use App\Exception\EspumosoNotFoundException;
use App\Exception\BodegaNotFoundException;
use App\Exception\InvalidFieldException;
use App\Exception\NameAlreadyExistException;
use App\Repository\VinoRepository;
use App\Repository\ColorRepository;
use App\Repository\EspumosoRepository;
use App\Repository\BodegaRepository;
use App\Repository\VinoUvaRepository;
use App\Repository\VinoMaridajeRepository;
use App\Repository\MaduracionRepository;
use App\Repository\SaborRepository;
use App\Repository\BocaRepository;
use App\Repository\CuerpoRepository;
use App\Repository\AzucarRepository;
use App\Service\VinoUvaService;
use Doctrine\Common\Collections\Collection;

class VinoService
{
    private VinoRepository $vinoRepository;
    private ColorRepository $colorRepository;
    private EspumosoRepository $espumosoRepository;
    private BodegaRepository $bodegaRepository;
    private VinoUvaRepository $vinoUvaRepository;
    private VinoMaridajeRepository $vinoMaridajeRepository;
    private VinoMaridajeService $vinoMaridajeService;
    private MaduracionRepository $maduracionRepository;
    private SaborRepository $saborRepository;
    private BocaRepository $bocaRepository;
    private CuerpoRepository $cuerpoRepository;
    private AzucarRepository $azucarRepository;
    private VinoUvaService $vinoUvaService;

    public function __construct(VinoRepository $vinoRepository, ColorRepository $colorRepository, EspumosoRepository $espumosoRepository, BodegaRepository $bodegaRepository, VinoUvaRepository $vinoUvaRepository, VinoMaridajeRepository $vinoMaridajeRepository, MaduracionRepository $maduracionRepository, SaborRepository $saborRepository, BocaRepository $bocaRepository, CuerpoRepository $cuerpoRepository, AzucarRepository $azucarRepository, VinoMaridajeService $vinoMaridajeService, VinoUvaService $vinoUvaService)
    {
        $this->vinoRepository = $vinoRepository;
        $this->colorRepository = $colorRepository;
        $this->espumosoRepository = $espumosoRepository;
        $this->bodegaRepository = $bodegaRepository;
        $this->vinoUvaRepository = $vinoUvaRepository;
        $this->vinoMaridajeRepository = $vinoMaridajeRepository;
        $this->maduracionRepository = $maduracionRepository;
        $this->saborRepository = $saborRepository;
        $this->bocaRepository = $bocaRepository;
        $this->cuerpoRepository = $cuerpoRepository;
        $this->azucarRepository = $azucarRepository;
        $this->vinoMaridajeService = $vinoMaridajeService;
        $this->vinoUvaService = $vinoUvaService;
    }

    public function findAllVinos(): array
    {
        $vinos = $this->vinoRepository->findAll();
        return [
            'info' => ['count' => count($vinos)],
            'results' => array_map([$this, 'vinosJSON'], $vinos)
        ];
    }

    public function findAllVinosByColor(int $colorId): array
    {
        $color = $this->findColor($colorId);
        $vinos = $this->vinoRepository->findBy(["color" => $color]);
        return [
            'info' => ['count' => count($vinos)],
            'results' => array_map([$this, 'vinosJSON'], $vinos)
        ];
    }

    public function findAllVinosByEspumoso(int $espumosoId): array
    {
        $espumoso = $this->findEspumoso($espumosoId);
        $vinos = $this->vinoRepository->findBy(["espumoso" => $espumoso]);
        return [
            'info' => ['count' => count($vinos)],
            'results' => array_map([$this, 'vinosJSON'], $vinos)
        ];
    }

    public function findVino(Vino $vino): array
    {
        $json['results'][] = $this->vinosJSON($vino);
        return $json;
    }

    public function findRanking(): array
    {
        $vinos = $this->vinoRepository->findBy([], ['puntos' => 'DESC','nombre' => 'ASC'], 10);
        return [
            'info' => ['count' => count($vinos)],
            'results' => array_map([$this, 'vinosJSON'], $vinos)
        ];
    }

    public function new(array $data): void
    {
        $errors = $this->requiredFieldsCreate($data);
        if (!empty($errors)) {
            throw new InvalidFieldException($errors);
        }
        if ($this->vinoRepository->findBy(["nombre" => $data['nombre']])) {
            throw new NameAlreadyExistException('El vino ya existe en la BD');
        }
        $color = $this->findColor($data['color']);
        $bodega = $this->findBodega($data['bodega']);
        $errors = [];
        $optionalFields = [
            'maduracion' => $this->maduracionRepository,
            'azucar'     => $this->azucarRepository,
            'sabor'      => $this->saborRepository,
            'cuerpo'     => $this->cuerpoRepository,
            'boca'       => $this->bocaRepository,
            'espumoso'   => $this->espumosoRepository
        ];
        $optionalValues = $this->validateOptionalFields($data, $optionalFields);
        if (!empty($optionalValues[1])) {
            throw new InvalidFieldException($optionalValues[1]);
        }
        $vino = new Vino();
        $vino->setNombre($data['nombre']);
        $vino->setDescripcion($data['descripcion']);
        $vino->setNotaCata($data['notaCata']);
        $vino->setImagen($data['imagen']);
        $vino->setUrl($data['url']);
        $vino->setColor($color);
        $vino->setBodega($bodega);
        $vino->setAzucar($optionalValues['azucar']);
        $vino->setEspumoso($optionalValues['espumoso']);
        $vino->setMaduracion($optionalValues['maduracion']);
        $vino->setSabor($optionalValues['sabor']);
        $vino->setCuerpo($optionalValues['cuerpo']);
        $vino->setBoca($optionalValues['boca']);
        $bodega->addVino($vino);
        if (!is_null($data['uvas'])) {
            $this->vinoUvaService->new($data['uvas'], $vino);
        }
        if (!is_null($data['maridajes'])) {
            $this->vinoMaridajeService->new($data['maridajes'], $vino);
        }
        $this->vinoRepository->save($vino, true);
    }

    public function update(array $data, Vino $vino): void
    {
        $errors = $this->requiredFieldsUpdate($data);
        if (!empty($errors)) {
            throw new InvalidFieldException($errors);
        }
        $vino->setDescripcion($data['descripcion']);
        $vino->setNotaCata($data['notaCata']);
        $vino->setImagen($data['imagen']);
        $vino->setUrl($data['url']);
        if (!is_null($data['uvas'])) {
            $this->vinoUvaService->new($data['uvas'], $vino);
        }
        if (!is_null($data['maridajes'])) {
            $this->vinoMaridajeService->new($data['maridajes'], $vino);
        }
        $this->vinoRepository->save($vino, true);
    }

    public function delete(Vino $vino): void
    {
        if (count($vino->getUvas()) > 0) {
            foreach ($vino->getUvas() as $vinoUva) {
                $vino->removeUva($vinoUva);
                $this->vinoUvaRepository->remove($vinoUva);
            }
        }
        if (count($vino->getMaridajes()) > 0) {
            foreach ($vino->getMaridajes() as $vinoMaridaje) {
                $vino->removeMaridaje($vinoMaridaje);
                $this->vinoMaridajeRepository->remove($vinoMaridaje);
            }
        }
        $this->vinoRepository->remove($vino, true);
    }

    private function findColor(int $colorId): Color
    {
        $color = $this->colorRepository->find($colorId);
        if (is_null($color)) {
            throw new ColorNotFoundException('El color no existe en la BD');
        }
        return $color;
    }

    private function findEspumoso(int $espumosoId): Espumoso
    {
        $espumoso = $this->espumosoRepository->find($espumosoId);
        if (is_null($espumoso)) {
            throw new EspumosoNotFoundException('El espumoso no existe en la BD');
        }
        return $espumoso;
    }

    private function findBodega(int $bodegaId): Bodega
    {
        $bodega = $this->bodegaRepository->find($bodegaId);
        if (is_null($bodega)) {
            throw new BodegaNotFoundException('La bodega no existe existe en la bd');
        }
        return $bodega;
    }

    private function vinosJSON(Vino $vino): array
    {
        return [
            'id' => $vino->getId(),
            'nombre' => $vino->getNombre(),
            'descripcion' => $vino->getDescripcion(),
            'notaCata' => $vino->getNotaCata(),
            'imagen' => $vino->getImagen(),
            'url' => $vino->getUrl(),
            'color' => $vino->getColor()->getNombre(),
            'azucar' => ($vino->getAzucar() == null) ? null : $vino->getAzucar()->getNombre(),
            'espumoso' => ($vino->getEspumoso() == null) ? null : $vino->getEspumoso()->getNombre(),
            'maduracion' => ($vino->getMaduracion() == null) ? null : $vino->getMaduracion()->getNombre(),
            'sabor' => ($vino->getSabor() == null) ? null : $vino->getSabor()->getNombre(),
            'cuerpo' => ($vino->getCuerpo() == null) ? null : $vino->getCuerpo()->getNombre(),
            'bodega' => ($vino->getBodega() == null) ? null : $vino->getBodega(),
            'do' => ($vino->getBodega()->getDenominacion() == null) ? null : $vino->getBodega()->getDenominacion()->getNombre(),
            'boca' => ($vino->getBoca() == null) ? null : $vino->getBoca()->getNombre(),
            'uvas' => $this->uvasJSON($vino->getUvas()),
            'maridajes' => $this->maridajesJSON($vino->getMaridajes()),
            'puntos' => $vino->getPuntos(),
            'puntuaciones' => $this->puntuacionesJSON($vino->getPuntuaciones())
        ];
    }

    private function uvasJSON(Collection $uvas): array
    {
        $json = array();
        foreach ($uvas as $uva) {
            $json[] = array("nombre" => $uva->getUva()->getNombre(), "porcentaje" => $uva->getPorcentaje());
        }
        return $json;
    }

    private function maridajesJSON(Collection $maridajes): array
    {
        $json = array();
        foreach ($maridajes as $maridaje) {
            $json[] = $maridaje->getMaridaje()->getNombre();
        }
        return $json;
    }

    private function puntuacionesJSON(Collection $puntuaciones): array
    {
        $json = array();
        foreach ($puntuaciones as $puntuacion) {
            $json[] = array(
                'puntos' => $puntuacion->getPuntuacion()->getPuntos(),
                'descripcion' => $puntuacion->getPuntuacion()->getDescripcion(),
                'comentarios' => $puntuacion->getComentarios()
            );
        }
        return $json;
    }

    private function requiredFieldsCreate(array $data): array
    {
        $errors = [];
        if (!isset($data['nombre']) || empty($data['nombre'])) {
            $errors[] = "El campo 'nombre' es obligatorio.";
        }
        if (!isset($data['descripcion']) || empty($data['descripcion'])) {
            $errors[] = "El campo 'descripcion' es obligatorio.";
        }
        if (!isset($data['notaCata']) || empty($data['notaCata'])) {
            $errors[] = "El campo 'notaCata' es obligatorio.";
        }
        if (!isset($data['imagen']) || empty($data['imagen'])) {
            $errors[] = "El campo 'imagen' es obligatorio.";
        }
        if (!isset($data['url']) || empty($data['url'])) {
            $errors[] = "El campo 'url' es obligatorio.";
        }
        if (!isset($data['color']) || empty($data['color'])) {
            $errors[] = "El campo 'color' es obligatorio.";
        }
        if (!isset($data['bodega']) || empty($data['bodega'])) {
            $errors[] = "El campo 'bodega' es obligatorio.";
        }
        return $errors;
    }

    private function requiredFieldsUpdate(array $data): array
    {
        $errors = [];

        if (!isset($data['descripcion']) || empty($data['descripcion'])) {
            $errors[] = 'El campo "descripcion" es obligatorio.';
        }
        if (!isset($data['notaCata']) || empty($data['notaCata'])) {
            $errors[] = 'El campo "notaCata" es obligatorio.';
        }
        if (!isset($data['imagen']) || empty($data['imagen'])) {
            $errors[] = 'El campo "imagen" es obligatorio.';
        }
        if (!isset($data['url']) || empty($data['url'])) {
            $errors[] = 'El campo "url" es obligatorio.';
        }
        if (!isset($data['uvas']) || empty($data['uvas'])) {
            $errors[] = 'El campo "uvas" es obligatorio.';
        }
        if (!isset($data['maridajes']) || empty($data['maridajes'])) {
            $errors[] = 'El campo "maridajes" es obligatorio.';
        }

        return $errors;
    }

    private function validateOptionalFields(array $data, array $optionalFields): array
    {
        $optionalValues = [];
        $errors = [];
        foreach ($optionalFields as $field => $repository) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $value = $repository->find($data[$field]);
                if (is_null($value)) {
                    $errors[] = "El campo '" . $field . "' es incorrecto";
                }
                $optionalValues[$field] = $value;
            } else {
                $optionalValues[$field] = null;
            }
        }
        return [$optionalValues, $errors];
    }
}
