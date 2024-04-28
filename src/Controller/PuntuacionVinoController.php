<?php

namespace App\Controller;

use App\Repository\PuntuacionRepository;
use App\Repository\PuntuacionVinoRepository;
use App\Repository\VinoRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PuntuacionVinoController extends AbstractController
{
    private PuntuacionVinoRepository $puntuacionVinoRepository;
    private VinoRepository $vinoRepository;
    private PuntuacionRepository $puntuacionRepository;


    public function __construct(PuntuacionVinoRepository $puntuacionVinoRepository,VinoRepository $vinoRepository,PuntuacionRepository $puntuacionRepository)
    {
        $this->puntuacionVinoRepository = $puntuacionVinoRepository; 
        $this->vinoRepository=$vinoRepository;
        $this->puntuacionRepository=$puntuacionRepository;
    }

    #[Route('/puntuacion', name: 'app_puntuacion_all', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $puntuaciones = $this->puntuacionVinoRepository->findAllPuntuaciones();
        if (is_null($puntuaciones)) {
            return new JsonResponse(['status' => 'No existen puntuaciones en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($puntuaciones, Response::HTTP_OK);
    }

    #[Route('/puntuacion/{idVino}', name: 'app_puntuacion_vino', methods: ['GET'])]
    public function show(?int $idVino = null): JsonResponse
    {
        $vino=$this->vinoRepository->find($idVino);
        if (is_null($vino)) {
            return new JsonResponse(['status' => 'El vino no existe en la bd'], Response::HTTP_NOT_FOUND);
        }
        $puntuaciones = $this->puntuacionVinoRepository->findAllByVino($vino);
        if(is_null($puntuaciones)){
            return new JsonResponse(['status' => 'No existen puntuaciones en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($puntuaciones, Response::HTTP_OK);
    }

  #[Route('/puntuacion', name: 'app_puntuacion_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent());
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            if (!isset($data->vino) || empty($data->vino) || !isset($data->puntuacion) || empty($data->puntuacion)) {
                return new JsonResponse(['status' => 'Faltan parámetros'], Response::HTTP_BAD_REQUEST);
            }
            $vino = $this->vinoRepository->find(['nombre' => $data->vino]);
            if (!is_null($vino)) {
                return new JsonResponse(['status' => 'El vino no existe en la bd'], Response::HTTP_BAD_REQUEST);
            }
            $puntuacion = $this->puntuacionRepository->find($data->puntuacion);
            if (is_null($puntuacion)) {
                return new JsonResponse(['status' => 'La puntuación no existe existe en la bd'], Response::HTTP_BAD_REQUEST);
            }
            $comentarios = (isset($data->comentarios) && !empty($data->comentarios)) ? $data->comentarios : true;
            $usuario = (isset($data->usuario)  && !empty($data->usuario)) ? $data->usuario:null;

            $this->puntuacionVinoRepository->new($vino, $puntuacion, $comentarios, $usuario, true);
            if (!$this->puntuacionVinoRepository->testInsert($data->nombre)) {
                return new JsonResponse(['status' => 'La inserción de la puntuación falló'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return new JsonResponse(['status' => 'Puntuación insertada correctamente'], Response::HTTP_CREATED);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     /*  #[Route('/denominacion/{id}', name: 'app_denominacion_update', methods: ['PUT'])]
    public function update(Request $request, ?Denominacion $denominacion = null): JsonResponse
    {
        try {
            $data = json_decode($request->getContent());
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            if (is_null($denominacion)) {
                return new JsonResponse(['status' => "La denominación de origen no existe en la bd"], Response::HTTP_NOT_FOUND);
            }
            if(isset($data->creacion) && !$this->denominacionRepository->isValidCreacion($data->creacion)){
                return new JsonResponse(['status' => 'Año de creación incorrecto'], Response::HTTP_BAD_REQUEST);
            }
            $creacion = (isset($data->creacion) && $this->denominacionRepository->isValidCreacion($data->creacion)) ? $data->creacion : null;
            $calificada = (isset($data->calificada) && !empty($data->calificada)) ? $data->calificada : false; 
            $web = (isset($data->web)) ? $data->web : null;
            $imagen = (isset($data->imagen) && !empty($data->imagen)) ? $data->imagen : null;
            $historia = (isset($data->historia) && !empty($data->historia)) ? $data->historia : null;
            $descripcion = (isset($data->descripcion) && !empty($data->descripcion)) ? $data->descripcion : null;
            $descripcionVinos = (isset($data->descripcion_vinos) && !empty($data->descripcion_vinos)) ? $data->descripcion_vinos : null;
            $uvas = (isset($data->uvas_permitidas) && !empty($data->uvas_permitidas)) ? $data->uvas_permitidas : null;
            if (!$this->denominacionRepository->update($denominacion, $calificada, $creacion,$web, $imagen, $historia, $descripcion, $descripcionVinos, $uvas, true)) {
                return new JsonResponse(['status' => 'La denominación de origen no se ha actualizado'], Response::HTTP_BAD_REQUEST);
            }
            return new JsonResponse(['status' => 'Denominación de origen actualizada correctamente'], Response::HTTP_OK);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/denominacion/{id}', name: 'app_denominacion_delete', methods: ['DELETE'])]
    public function delete(?Denominacion $denominacion = null): JsonResponse
    {
        try {
            if (is_null($denominacion)) {
                return new JsonResponse(['status' => 'La denominación de origen no existe en la bd'], Response::HTTP_NOT_FOUND);
            }
            if (count($denominacion->getBodegas()) > 0) {
                return new JsonResponse(['status' => 'La denominación de origen no puede ser borrada, tiene bodegas asociadas'], Response::HTTP_BAD_REQUEST);
            }
            if (count($denominacion->getUvas()) > 0) {
                foreach ($denominacion->getUvas() as $uvaDo) {
                    $denominacion->removeUva($uvaDo);
                    $this->uvaDoRepository->remove($uvaDo);
                }
            }
            $this->denominacionRepository->remove($denominacion, true);
            if ($this->denominacionRepository->testDelete($denominacion->getNombre())) {
                return new JsonResponse('La denominación de origen ha sido borrada', Response::HTTP_OK);
            } else {
                return new JsonResponse(['status' => 'La eliminación de la denominación de origen falló'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    } */
}
