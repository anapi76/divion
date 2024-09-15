<?php

namespace App\Controller;

use App\Entity\PuntuacionVino;
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


    public function __construct(PuntuacionVinoRepository $puntuacionVinoRepository, VinoRepository $vinoRepository, PuntuacionRepository $puntuacionRepository)
    {
        $this->puntuacionVinoRepository = $puntuacionVinoRepository;
        $this->vinoRepository = $vinoRepository;
        $this->puntuacionRepository = $puntuacionRepository;
    }

    #[Route('/puntuacion/vino', name: 'app_puntuacion_all', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $puntuaciones = $this->puntuacionVinoRepository->findAllOrderedByPuntuaciones('puntuacion');
        if (is_null($puntuaciones)) {
            return new JsonResponse(['status' => 'No existen puntuaciones en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($puntuaciones, Response::HTTP_OK);
    }

    #[Route('/puntuacion/vino/{idVino}', name: 'app_puntuacion_vino', methods: ['GET'])]
    public function show(?int $idVino = null): JsonResponse
    {
        $vino = $this->vinoRepository->find($idVino);
        if (is_null($vino)) {
            return new JsonResponse(['status' => 'El vino no existe en la bd'], Response::HTTP_NOT_FOUND);
        }
        $puntuaciones = $this->puntuacionVinoRepository->findAllByVino($vino);
        if (is_null($puntuaciones)) {
            return new JsonResponse(['status' => 'No existen puntuaciones en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($puntuaciones, Response::HTTP_OK);
    }

    #[Route('/puntuacion/vino', name: 'app_puntuacion_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent());
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            if (!$this->puntuacionVinoRepository->requiredFields($data)) {
                return new JsonResponse(['status' => 'Faltan parámetros'], Response::HTTP_BAD_REQUEST);
            }
            $vino = $this->vinoRepository->find($data->vino);
            if (is_null($vino)) {
                return new JsonResponse(['status' => 'El vino no existe en la bd'], Response::HTTP_BAD_REQUEST);
            }
            $puntuacion = $this->puntuacionRepository->find($data->puntuacion);
            if (is_null($puntuacion)) {
                return new JsonResponse(['status' => 'La puntuación no existe existe en la bd'], Response::HTTP_BAD_REQUEST);
            }
            $comentarios = (isset($data->comentarios) && !empty($data->comentarios)) ? $data->comentarios : null;
            $usuario = (isset($data->usuario)  && !empty($data->usuario)) ? $data->usuario : null;
            if (!$this->puntuacionVinoRepository->new($vino, $puntuacion, $comentarios, $usuario, true)) {
                return new JsonResponse(['status' => 'La inserción de la puntuación falló'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return new JsonResponse(['status' => 'Puntuación insertada correctamente'], Response::HTTP_CREATED);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/puntuacion/vino/{id}', name: 'app_puntuacion_delete', methods: ['DELETE'])]
    public function delete(?PuntuacionVino $puntuacionVino = null): JsonResponse
    {
        try {
            if (is_null($puntuacionVino)) {
                return new JsonResponse(['status' => 'La puntuación no existe en la bd'], Response::HTTP_NOT_FOUND);
            }
            if (!$this->puntuacionVinoRepository->remove($puntuacionVino, true)) {
                return new JsonResponse(['status' => 'La eliminación de la puntuación falló'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return new JsonResponse('La puntuación ha sido borrada', Response::HTTP_OK);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
