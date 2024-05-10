<?php

namespace App\Repository;

use App\Entity\Azucar;
use App\Entity\Boca;
use App\Entity\Bodega;
use App\Entity\Color;
use App\Entity\Cuerpo;
use App\Entity\Maduracion;
use App\Entity\Sabor;
use App\Entity\Espumoso;
use App\Entity\Vino;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<Vino>
 *
 * @method Vino|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vino|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vino[]    findAll()
 * @method Vino[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VinoRepository extends ServiceEntityRepository
{

    private VinoMaridajeRepository $vinoMaridajeRepository;
    private VinoUvaRepository $vinoUvaRepository;
    private MaduracionRepository $maduracionRepository;
    private SaborRepository $saborRepository;
    private BocaRepository $bocaRepository;
    private CuerpoRepository $cuerpoRepository;
    private AzucarRepository $azucarRepository;

    public function __construct(ManagerRegistry $registry, VinoUvaRepository $vinoUvaRepository, VinoMaridajeRepository $vinoMaridajeRepository, MaduracionRepository $maduracionRepository, SaborRepository $saborRepository, BocaRepository $bocaRepository, CuerpoRepository $cuerpoRepository, AzucarRepository $azucarRepository)
    {
        parent::__construct($registry, Vino::class);
        $this->vinoUvaRepository = $vinoUvaRepository;
        $this->vinoMaridajeRepository = $vinoMaridajeRepository;
        $this->maduracionRepository = $maduracionRepository;
        $this->saborRepository = $saborRepository;
        $this->bocaRepository = $bocaRepository;
        $this->cuerpoRepository = $cuerpoRepository;
        $this->azucarRepository = $azucarRepository;
    }

    public function findAllVinos(): mixed
    {
        $vinos = $this->findAll();
        if (empty($vinos)) {
            return null;
        }
        $json = array('info' => array('count'=>count($vinos)), 
        'results' => array());
        foreach ($vinos as $vino) {
            $json['results'][] = $this->vinosJSON($vino);
        }
        return $json;
    }

    public function findAllVinosByColor(Color $color): mixed
    {
        $vinos = $this->findBy(["color"=>$color]);
        if (empty($vinos)) {
            return null;
        }
        $json = array('info' => array('count'=>count($vinos)), 
        'results' => array());
        foreach ($vinos as $vino) {
            $json['results'][] = $this->vinosJSON($vino);
        }
        return $json;
    }

    public function findAllVinosByEspumoso(Espumoso $espumoso): mixed
    {
        $vinos = $this->findBy(["espumoso"=>$espumoso]);
        if (empty($vinos)) {
            return null;
        }
        $json = array('info' => array('count'=>count($vinos)), 
        'results' => array());
        foreach ($vinos as $vino) {
            $json['results'][] = $this->vinosJSON($vino);
        }
        return $json;
    }

    public function findVino(Vino $vino): mixed
    {
        if (is_null($vino)) {
            return null;
        }
        $json = $this->vinosJSON($vino);
        return $json;
    }

    public function new(string $nombre, string $descripcion, string $notaCata, string $imagen, string $url, Color $color, ?Azucar $azucar, Espumoso $espumoso, ?Maduracion $maduracion, Bodega $bodega, ?Sabor $sabor, ?Cuerpo $cuerpo, ?Boca $boca, array $uvas, array $maridajes, bool $flush): void
    {
        try {
            $vino = new Vino();
            $vino->setNombre($nombre);
            $vino->setDescripcion($descripcion);
            $vino->setNotaCata($notaCata);
            $vino->setImagen($imagen);
            $vino->setUrl($url);
            $vino->setColor($color);
            if (!is_null($azucar)) $vino->setAzucar($azucar);
            if (!is_null($espumoso)) $vino->setEspumoso($espumoso);
            if (!is_null($maduracion)) $vino->setMaduracion($maduracion);
            $vino->setBodega($bodega);
            if (!is_null($sabor)) $vino->setSabor($sabor);
            if (!is_null($cuerpo)) $vino->setCuerpo($cuerpo);
            if (!is_null($boca)) $vino->setBoca($boca);
            $bodega->addVino($vino);
            $this->vinoUvaRepository->new($uvas, $vino);
            $this->vinoMaridajeRepository->new($maridajes, $vino);
            $this->save($vino, $flush);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(Vino $vino, ?string $descripcion, ?string $notaCata, ?string $imagen, ?string $url,?array $uvas, ?array $maridajes, bool $flush): bool
    {
        try {
            $update = false;
            if (!is_null($descripcion)) {
                $vino->setDescripcion($descripcion);
                $update = true;
            }
            if (!is_null($notaCata)) {
                $vino->setNotaCata($notaCata);
                $update = true;
            }
            if (!is_null($imagen)) {
                $vino->setImagen($imagen);
                $update = true;
            }
            if (!is_null($url)) {
                $vino->setUrl($url);
                $update = true;
            }
            if (!is_null($uvas)) {
                $this->vinoUvaRepository->new($uvas, $vino);
                $update = true;
            }
            if (!is_null($maridajes)) {
                $this->vinoUvaRepository->new($maridajes, $vino);
                $update = true;
            }
            $this->save($vino, $flush);
            return $update;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function remove(Vino $vino, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->remove($vino);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function vinosJSON(Vino $vino): mixed
    {
        $azucar = ($vino->getAzucar() == null) ? null : $vino->getAzucar()->getNombre();
        $maduracion = ($vino->getMaduracion() == null) ? null : $vino->getMaduracion()->getNombre();
        $sabor = ($vino->getSabor() == null) ? null : $vino->getSabor()->getNombre();
        $cuerpo = ($vino->getCuerpo() == null) ? null : $vino->getCuerpo()->getNombre();
        $boca = ($vino->getBoca() == null) ? null : $vino->getBoca()->getNombre();
        $espumoso = ($vino->getEspumoso() == null) ? null : $vino->getEspumoso()->getNombre();
        $json = array(
            'id' => $vino->getId(),
            'nombre' => $vino->getNombre(),
            'descripcion' => $vino->getDescripcion(),
            'notaCata' => $vino->getNotaCata(),
            'imagen' => $vino->getImagen(),
            'url' => $vino->getUrl(),
            'color' => $vino->getColor()->getNombre(),
            'azucar' => $azucar,
            'espumoso' => $espumoso,
            'maduracion' => $maduracion,
            'sabor' => $sabor,
            'cuerpo' => $cuerpo,
            'boca' => $boca,
            'uvas' => $this->uvasJSON($vino->getUvas()),
            'maridajes' => $this->maridajesJSON($vino->getMaridajes()),
            'puntuaciones' => $this->puntuacionesJSON($vino->getPuntuaciones())
        );

        return $json;
    }

    public function uvasJSON(Collection $uvas): mixed
    {
        $json = array();
        foreach ($uvas as $uva) {
            $json[] = $uva->getUva()->getNombre();
        }
        return $json;
    }

    public function maridajesJSON(Collection $maridajes): mixed
    {
        $json = array();
        foreach ($maridajes as $maridaje) {
            $json[] = $maridaje->getMaridaje()->getNombre();
        }
        return $json;
    }

    public function puntuacionesJSON(Collection $puntuaciones): mixed
    {
        $json = array();
        foreach ($puntuaciones as $puntuacion) {
            $json[] = array(
                'puntos'=>$puntuacion->getPuntuacion()->getPuntos(),
                'descripcion'=>$puntuacion->getPuntuacion()->getDescripcion(),
                'comentarios'=>$puntuacion->getComentarios()
            );
        }
        return $json;
    }

    public function save(Vino $vino, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->persist($vino);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function testInsert(string $nombre): bool
    {
        $entidad = $this->findOneBy(['nombre' => $nombre]);
        if (empty($entidad))
            return false;
        else {
            return true;
        }
    }

    public function testDelete(string $nombre): bool
    {
        $entidad = $this->findOneBy(['nombre' => $nombre]);
        if (empty($entidad))
            return true;
        else {
            return false;
        }
    }

    public function requiredFields(Object $data): bool
    {
        return (isset($data->nombre) && !empty($data->nombre) &&
            isset($data->descripcion) && !empty($data->descripcion) &&
            isset($data->notaCata) && !empty($data->notaCata) &&
            isset($data->imagen) && !empty($data->imagen) &&
            isset($data->url) && !empty($data->url) &&
            isset($data->color) && !empty($data->color) &&
            isset($data->bodega) && !empty($data->bodega));
    }
    
    public function isValidMaduracion(int $maduracionId): mixed
    {
        $maduracion = $this->maduracionRepository->find($maduracionId);
        if (is_null($maduracion)) {
            return new JsonResponse(['status' => 'Campo incorrecto'], Response::HTTP_BAD_REQUEST);
        }
        return $maduracion;
    }

    public function isValidAzucar(int $azucarId): mixed
    {
        $azucar = $this->azucarRepository->find($azucarId);
        if (is_null($azucar)) {
            return new JsonResponse(['status' => 'Campo incorrecto'], Response::HTTP_BAD_REQUEST);
        }
        return $azucar;
    }

    public function isValidSabor(int $saborId): mixed
    {
        $sabor = $this->saborRepository->find($saborId);
        if (is_null($sabor)) {
            return new JsonResponse(['status' => 'Campo incorrecto'], Response::HTTP_BAD_REQUEST);
        }
        return $sabor;
    }

    public function isValidCuerpo(int $cuerpoId): mixed
    {
        $cuerpo = $this->cuerpoRepository->find($cuerpoId);
        if (is_null($cuerpo)) {
            return new JsonResponse(['status' => 'Campo incorrecto'], Response::HTTP_BAD_REQUEST);
        }
        return $cuerpo;
    }

    public function isValidBoca(int $bocaId): mixed
    {
        $boca = $this->bocaRepository->find($bocaId);
        if (is_null($boca)) {
            return new JsonResponse(['status' => 'Campo incorrecto'], Response::HTTP_BAD_REQUEST);
        }
        return $boca;
    }

    public function isValidEspumoso(int $espumosoId): mixed
    {
        $espumoso = $this->espumosoRepository->find($espumosoId);
        if (is_null($espumoso)) {
            return new JsonResponse(['status' => 'Campo incorrecto'], Response::HTTP_BAD_REQUEST);
        }
        return $espumoso;
    }

    //    /**
    //     * @return Vino[] Returns an array of Vino objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vino
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
