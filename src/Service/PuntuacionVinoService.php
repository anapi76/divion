<?php

namespace App\Service;

use App\Entity\PuntuacionVino;
use App\Entity\Puntuacion;
use App\Entity\Vino;
use App\Exception\InvalidFieldException;
use App\Exception\VinoNotFoundException;
use App\Exception\PuntuacionNotFoundException;
use App\Repository\PuntuacionVinoRepository;
use App\Repository\VinoRepository;
use App\Repository\PuntuacionRepository;

class PuntuacionVinoService
{
    private PuntuacionVinoRepository $puntuacionVinoRepository;
    private VinoRepository $vinoRepository;
    private PuntuacionRepository $puntuacionRepository;

    public function __construct(PuntuacionVinoRepository $puntuacionVinoRepository, VinoRepository $vinoRepository, PuntuacionRepository $puntuacionRepository)
    {
        $this->puntuacionVinoRepository = $puntuacionVinoRepository;
        $this->vinoRepository = $vinoRepository;
        $this->puntuacionRepository = $puntuacionRepository;
    }

    public function findAllOrderedByValoraciones(): array
    {
        $puntuaciones = $this->puntuacionVinoRepository->findBy([], ['puntuacion' => 'DESC']);
        return [
            'info' => [
                'count' => count($puntuaciones)
            ],
            'results' => array_map([$this, 'puntuacionJSON'], $puntuaciones)
        ];
    }

    public function findAllByVino(?int $idVino): array
    {
        $vino = $this->vinoRepository->find($idVino);
        if (is_null($vino)) {
            throw new VinoNotFoundException('El vino no existe en la bd');
        }
        $puntuaciones = $this->puntuacionVinoRepository->findBy(["vino" => $vino]);
        return [
            'info' => [
                'count' => count($puntuaciones)
            ],
            'results' => array_map([$this, 'puntuacionJSON'], $puntuaciones)
        ];
    }

    public function new(array $data): void
    {
        $errors = $this->requiredFields($data);
        if (!empty($errors)) {
            throw new InvalidFieldException($errors);
        }
        $vino = $this->findVino($data['vino']);
        $puntuacion = $this->findPuntuacion($data['puntuacion']);
        $comentarios = (isset($data['comentarios']) && !empty($data['comentarios'])) ? $data['comentarios'] : null;
        $usuario = (isset($data['usuario'])  && !empty($data['usuario'])) ? $data['usuario'] : null;

        $puntuacionVino = new PuntuacionVino();
        $puntuacionVino->setVino($vino);
        $puntuacionVino->setPuntuacion($puntuacion);
        $puntos = $vino->getPuntos();
        $vino->setPuntos($puntos + $puntuacion->getPuntos());
        $puntuacionVino->setComentarios($comentarios);
        $puntuacionVino->setUsuario($usuario);
        $this->puntuacionVinoRepository->save($puntuacionVino, true);
    }

    public function delete(PuntuacionVino $puntuacionVino): void
    {
        $this->puntuacionVinoRepository->remove($puntuacionVino, true);
    }

    private function findVino(int $idVino):Vino{
        $vino = $this->vinoRepository->find($idVino);
        if (is_null($vino)) {
            throw new VinoNotFoundException('El vino no existe en la bd');
        } 
        return $vino;
    }

    private function findPuntuacion(int $idPuntuacion): Puntuacion {
        $puntuacion = $this->puntuacionRepository->find($idPuntuacion);
        if (is_null($puntuacion)) {
            throw new PuntuacionNotFoundException('La puntuación no existe existe en la bd');
        }
        return $puntuacion;
    }

    private function puntuacionJson(PuntuacionVino $puntuacion): array
    {
        return [
            'id' => $puntuacion->getId(),
            'vino' => $puntuacion->getVino()->getNombre(),
            'puntuacion' => $puntuacion->getPuntuacion()->getPuntos(),
            'descripcion' => $puntuacion->getPuntuacion()->getDescripcion(),
            'comentarios' => $puntuacion->getComentarios(),
            'usuario' => $puntuacion->getUsuario()
        ];
    }

    private function requiredFields(array $data): array
    {
        $errors = [];
        if (!isset($data['vino']) || empty($data['vino'])) {
            $errors[] = "El campo 'vino' es obligatorio.";
        }
        if (!isset($data['puntuacion']) || empty($data['puntuacion'])) {
            $errors[] = "El campo 'puntuacion' es obligatorio.";
        }
        return $errors;
    }
}
