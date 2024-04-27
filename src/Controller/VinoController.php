<?php

namespace App\Controller;

use App\Entity\Denominacion;
use App\Entity\TipoVino;
use App\Entity\Vino;
use App\Repository\AzucarRepository;
use App\Repository\BocaRepository;
use App\Repository\BodegaRepository;
use App\Repository\ColorRepository;
use App\Repository\CuerpoRepository;
use App\Repository\DenominacionRepository;
use App\Repository\MaduracionRepository;
use App\Repository\RegionRepository;
use App\Repository\SaborRepository;
use App\Repository\TipoVinoRepository;
use App\Repository\UvaDoRepository;
use App\Repository\VinoRepository;
use DateTime;
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
    private AzucarRepository $azucarRepository;
    private TipoVinoRepository $tipoVinoRepository;
    private MaduracionRepository $maduracionRepository;
    private SaborRepository $saborRepository;
    private BocaRepository $bocaRepository;
    private CuerpoRepository $cuerpoRepository;

    public function __construct(VinoRepository $vinoRepository, BodegaRepository $bodegaRepository, ColorRepository $colorRepository, AzucarRepository $azucarRepository, TipoVinoRepository $tipoVinoRepository, MaduracionRepository $maduracionRepository, SaborRepository $saborRepository, BocaRepository $bocaRepository, CuerpoRepository $cuerpoRepository)
    {
        $this->vinoRepository = $vinoRepository;
        $this->bodegaRepository = $bodegaRepository;
        $this->colorRepository = $colorRepository;
        $this->azucarRepository = $azucarRepository;
        $this->tipoVinoRepository = $tipoVinoRepository;
        $this->maduracionRepository = $maduracionRepository;
        $this->saborRepository = $saborRepository;
        $this->bocaRepository = $bocaRepository;
        $this->cuerpoRepository = $cuerpoRepository;
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
                return new JsonResponse(['status' => 'La bodega no existe existe en la bd'], Response::HTTP_BAD_REQUEST);
            }
            $tipoVino = $this->tipoVinoRepository->find($data->tipoVino);
            if (is_null($tipoVino)) {
                return new JsonResponse(['status' => 'El tipo de vino no existe existe en la bd '], Response::HTTP_BAD_REQUEST);
            }
            $bodega = $this->bodegaRepository->find($data->bodega);
            if (is_null($bodega)) {
                return new JsonResponse(['status' => 'La bodega no existe existe en la bd'], Response::HTTP_BAD_REQUEST);
            }
            if (isset($data->azucar) && !empty($data->azucar)) {
                $azucar = $this->azucarRepository->find($data->azucar);
                if (is_null($azucar)) {
                    return new JsonResponse(['status' => 'El azúcar no existe existe en la bd'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                $azucar = null;
            }
            if (isset($data->maduracion) && !empty($data->maduracion)) {
                $maduracion = $this->maduracionRepository->find($data->maduracion);
                if (is_null($maduracion)) {
                    return new JsonResponse(['status' => 'La maduración no existe existe en la bd'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                $maduracion = null;
            }
            if (isset($data->sabor) && !empty($data->sabor)) {
                $sabor = $this->saborRepository->find($data->sabor);
                if (is_null($sabor)) {
                    return new JsonResponse(['status' => 'El sabor no existe existe en la bd'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                $sabor = null;
            }
            if (isset($data->cuerpo) && !empty($data->cuerpo)) {
                $cuerpo = $this->cuerpoRepository->find($data->cuerpo);
                if (is_null($cuerpo)) {
                    return new JsonResponse(['status' => 'El cuerpo no existe existe en la bd'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                $cuerpo = null;
            }

            if (isset($data->boca) && !empty($data->boca)) {
                $boca = $this->bocaRepository->find($data->boca);
                if (is_null($boca)) {
                    return new JsonResponse(['status' => 'La boca no existe existe en la bd'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                $boca = null;
            }
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
            $calificada = (isset($data->calificada) && !empty($data->calificada)) ? $data->calificada : false;
            $web = (isset($data->web) && !empty($data->web)) ? $data->web : null;
            $imagen = (isset($data->imagen) && !empty($data->imagen)) ? $data->imagen : null;
            $historia = (isset($data->historia) && !empty($data->historia)) ? $data->histioria : null;
            $descripcion = (isset($data->descripcion) && !empty($data->descripcion)) ? $data->descripcion : null;
            $tipoVinos = (isset($data->tipo_vinos) && !empty($data->tipo_vinos)) ? $data->tipo_vinos : null;
            $tiposUva = (isset($data->tipos_uva) && !empty($data->tipos_uva)) ? $data->tipos_uva : null;
            if(!$this->denominacionRepository->update($denominacion, $calificada, $web, $imagen, $historia, $descripcion, $tipoVinos, $tiposUva, true)){
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
    }

    public function validateCreacion(int $creacion): bool
    {
        $now = new DateTime('now');
        $year = (int) $now->format('Y');
        return ($creacion !== null && ($creacion > 1900 && $creacion <= $year));
    } */
}
