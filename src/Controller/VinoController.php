<?php

namespace App\Controller;


use App\Entity\Vino;
use App\Repository\BodegaRepository;
use App\Repository\ColorRepository;
use App\Repository\TipoVinoRepository;
use App\Repository\VinoRepository;
use App\Repository\VinoUvaRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VinoController extends AbstractController
{
    private VinoRepository $vinoRepository;
    private BodegaRepository $bodegaRepository;
    private ColorRepository $colorRepository;
    private TipoVinoRepository $tipoVinoRepository;
    private VinoUvaRepository $vinoUvaRepository;

    public function __construct(VinoRepository $vinoRepository, BodegaRepository $bodegaRepository, ColorRepository $colorRepository, TipoVinoRepository $tipoVinoRepository, VinoUvaRepository $vinoUvaRepository)
    {
        $this->vinoRepository = $vinoRepository;
        $this->bodegaRepository = $bodegaRepository;
        $this->colorRepository = $colorRepository;
        $this->tipoVinoRepository = $tipoVinoRepository;
        $this->vinoUvaRepository=$vinoUvaRepository;
    }

    #[Route('/vino', name: 'app_vino_all', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $vinos = $this->vinoRepository->findAllVinos();
        if (is_null($vinos)) {
            return new JsonResponse(['status' => 'No existen vinos en la bd'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($vinos, Response::HTTP_OK);
    }

    #[Route('/vino/{id}', name: 'app_vino', methods: ['GET'])]
    public function show(?Vino $vino = null): JsonResponse
    {
        if (is_null($vino)) {
            return new JsonResponse(['status' => 'El vino no existe en la bd'], Response::HTTP_NOT_FOUND);
        }
        $vinoJson = $this->vinoRepository->findvino($vino);
        return new JsonResponse($vinoJson, Response::HTTP_OK);
    }

    #[Route('/vino', name: 'app_vino_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent());
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            if (!isset($data->nombre) || empty($data->nombre) || !isset($data->descripcion) || empty($data->descripcion) || !isset($data->notaCata) || empty($data->notaCata) || !isset($data->imagen) || empty($data->imagen) || !isset($data->color) || empty($data->color) || !isset($data->tipoVino) || empty($data->tipoVino) || !isset($data->bodega) || empty($data->bodega)) {
                return new JsonResponse(['status' => 'Faltan parámetros'], Response::HTTP_BAD_REQUEST);
            }
            $color = $this->colorRepository->find($data->color);
            if (is_null($color)) {
                return new JsonResponse(['status' => 'El color no existe existe en la bd'], Response::HTTP_BAD_REQUEST);
            }
            $tipoVino = $this->tipoVinoRepository->find($data->tipoVino);
            if (is_null($tipoVino)) {
                return new JsonResponse(['status' => 'El tipo de vino no existe existe en la bd '], Response::HTTP_BAD_REQUEST);
            }
            $bodega = $this->bodegaRepository->find($data->bodega);
            if (is_null($bodega)) {
                return new JsonResponse(['status' => 'La bodega no existe existe en la bd'], Response::HTTP_BAD_REQUEST);
            }
            $maduracion = (isset($data->maduracion) && !empty($data->maduracion)) ? $this->vinoRepository->validateMaduracion($data->maduracion) : null;
            $azucar = (isset($data->azucar) && !empty($data->azucar)) ? $this->vinoRepository->validateAzucar($data->azucar) : null;
            $sabor = (isset($data->sabor) && !empty($data->sabor)) ? $this->vinoRepository->validateSabor($data->sabor) : null;
            $cuerpo = (isset($data->cuerpo) && !empty($data->cuerpo)) ? $this->vinoRepository->validateCuerpo($data->cuerpo) : null;
            $boca = (isset($data->boca) && !empty($data->boca)) ? $this->vinoRepository->validateBoca($data->boca) : null;

            $uvas = (isset($data->uvas) && !empty($data->uvas)) ? $data->uvas : null;
            $maridajes = (isset($data->maridajes) && !empty($data->maridajes)) ? $data->maridajes : null;
            $this->vinoRepository->new($data->nombre, $data->descripcion, $data->notaCata, $data->imagen, $color, $azucar, $tipoVino, $maduracion, $bodega, $sabor, $cuerpo, $boca, $uvas, $maridajes, true);
            if (!$this->vinoRepository->testInsert($data->nombre)) {
                return new JsonResponse(['status' => 'La inserción del vino falló'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return new JsonResponse(['status' => 'Vino insertado correctamente'], Response::HTTP_CREATED);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/vino/{id}', name: 'app_vino_update', methods: ['PUT'])]
    public function update(Request $request, ?Vino $vino = null): JsonResponse
    {
        try {
            $data = json_decode($request->getContent());
            if (is_null($data)) {
                return new JsonResponse(['status' => 'Error al decodificar el archivo json'], Response::HTTP_BAD_REQUEST);
            }
            if (is_null($vino)) {
                return new JsonResponse(['status' => "El vino no existe en la bd"], Response::HTTP_NOT_FOUND);
            }
            $descripcion = (isset($data->descripcion) && !empty($data->descripcion)) ? $data->descripcion : null;
            $notaCata = (isset($data->notaCata) && !empty($data->notaCata)) ? $data->notaCata : null;
            $imagen = (isset($data->imagen) && !empty($data->imagen)) ? $data->imagen : null;
            $uvas = (isset($data->uvas) && !empty($data->uvas)) ? $data->uvas : null;
            $maridajes = (isset($data->maridajes) && !empty($data->maridajes)) ? $data->maridajes : null;
            if (!$this->vinoRepository->update($vino, $descripcion, $notaCata, $imagen, $uvas, $maridajes, true)) {
                return new JsonResponse(['status' => 'El vino no se ha actualizado'], Response::HTTP_BAD_REQUEST);
            }
            return new JsonResponse(['status' => 'Vino actualizado correctamente'], Response::HTTP_OK);
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

   #[Route('/vino/{id}', name: 'app_vino_delete', methods: ['DELETE'])]
    public function delete(?Vino $vino = null): JsonResponse
    {
        try {
            if (is_null($vino)) {
                return new JsonResponse(['status' => 'El vino no existe en la bd'], Response::HTTP_NOT_FOUND);
            }
            if (count($vino->getUvas()) > 0) {
                foreach ($vino->getUvas() as $uvaDo) {
                    $vino->removeUva($uvaDo);
                    $this->vinoUvaRepository->remove($uvaDo);
                }
            }
            $this->vinoRepository->remove($vino, true);
            if ($this->vinoRepository->testDelete($vino->getNombre())) {
                return new JsonResponse('El vinon ha sido borrado', Response::HTTP_OK);
            } else {
                return new JsonResponse(['status' => 'La eliminación del vino falló'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            $msg = 'Error del servidor: ' . $e->getMessage();
            return new JsonResponse(['status' => $msg], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
