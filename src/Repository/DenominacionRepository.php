<?php

namespace App\Repository;

use App\Entity\Denominacion;
use App\Entity\Region;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Denominacion>
 *
 * @method Denominacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Denominacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Denominacion[]    findAll()
 * @method Denominacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DenominacionRepository extends ServiceEntityRepository
{

    private UvaDoRepository $uvaDoRepository;

    public function __construct(ManagerRegistry $registry, UvaDoRepository $uvaDoRepository)
    {
        parent::__construct($registry, Denominacion::class);
        $this->uvaDoRepository = $uvaDoRepository;
    }

    public function findAllDenominaciones(): mixed
    {
        $denominaciones = $this->findAll();
        if (empty($denominaciones)) {
            return null;
        }
        $json = array(
            'info' => array('count' => count($denominaciones)),
            'results' => array()
        );
        foreach ($denominaciones as $denominacion) {
            $json['results'][] = $this->denominacionesJSON($denominacion);
        }
        return $json;
    }

    public function findDenominacion(Denominacion $denominacion): mixed
    {
        if (is_null($denominacion)) {
            return null;
        }
        $json['results'][] = $this->denominacionesJSON($denominacion);
        return $json;
    }

    public function new(string $nombre, bool $calificada, ?int $creacion, ?string $web, string $imagen, ?string $imagenHistoria, ?string $imagenUva, ?string $logo,string $historia, string $descripcion, string $descripcionVinos, string $url, Region $region, array $uvas, bool $flush): void
    {
        try {
            $denominacion = new Denominacion();
            $denominacion->setNombre($nombre);
            $denominacion->setCalificada($calificada);
            if (!is_null($creacion)) $denominacion->setCreacion($creacion);
            if (!is_null($web)) $denominacion->setWeb($web);
            $denominacion->setImagen($imagen);
            $denominacion->setImagenHistoria($imagenHistoria);
            $denominacion->setImagen($logo);
            $denominacion->setHistoria($historia);
            $denominacion->setImagenUva($imagenUva);
            $denominacion->setDescripcion($descripcion);
            $denominacion->setDescripcionVinos($descripcionVinos);
            $denominacion->setUrl($url);
            $denominacion->setRegion($region);
            $this->uvaDoRepository->new($uvas, $denominacion);
            $this->save($denominacion, $flush);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(Denominacion $denominacion, bool $calificada, ?int $creacion, ?string $web, ?string $imagen, ?string $imagenHistoria, ?string $imagenUva, ?string $logo, ?string $historia, ?string $descripcion, ?string $descripcionVinos, ?string $url, ?array $uvas, bool $flush): bool
    {
        try {
            $update = false;
            if ($calificada) {
                $denominacion->setCalificada($calificada);
                $update = true;
            }
            if (!is_null($creacion)) {
                $creacion = (!empty($creacion)) ? $creacion : null;
                $denominacion->setCreacion($creacion);
                $update = true;
            }
            if (!is_null($web)) {
                $web = (!empty($web)) ? $web : null;
                $denominacion->setWeb($web);
                $update = true;
            }
            if (!is_null($imagen)) {
                $denominacion->setImagen($imagen);
                $update = true;
            }
            if (!is_null($imagenHistoria)) {
                $denominacion->setImagenHistoria($imagenHistoria);
                $update = true;
            }
            if (!is_null($imagenUva)) {
                $denominacion->setImagenUva($imagenUva);
                $update = true;
            }
            if (!is_null($logo)) {
                $denominacion->setImagen($logo);
                $update = true;
            }
            if (!is_null($historia)) {
                $denominacion->setHistoria($historia);
                $update = true;
            }
            if (!is_null($descripcion)) {
                $denominacion->setDescripcion($descripcion);
                $update = true;
            }
            if (!is_null($descripcionVinos)) {
                $denominacion->setDescripcionVinos($descripcionVinos);
                $update = true;
            }
            if (!is_null($url)) {
                $denominacion->setUrl($url);
                $update = true;
            }
            if (!is_null($uvas)) {
                $this->uvaDoRepository->new($uvas, $denominacion);
                $update = true;
            }
            $this->save($denominacion, $flush);
            return $update;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function remove(Denominacion $denominacion, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->remove($denominacion);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function denominacionesJSON(Denominacion $denominacion): mixed
    {
        $calificada = ($denominacion->isCalificada()) ? 'Denominación de origen calificada' : '';
        $json = array(
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
        );
        return $json;
    }

    public function bodegasJSON(Collection $bodegas): mixed
    {
        $json = array();
        foreach ($bodegas as $bodega) {
            $json[] = array('nombre' => $bodega->getNombre(), 'web' => $bodega->getWeb());
        }
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

    public function save(Denominacion $denominacion, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->persist($denominacion);
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

    public function isValidCreacion(int $creacion): bool
    {
        $now = new DateTime('now');
        $year = (int) $now->format('Y');
        return ($creacion !== null && ($creacion >= 1900 && $creacion <= $year));
    }

    public function requiredFields(Object $data): bool
    {
        return (isset($data->nombre) && !empty($data->nombre) && isset($data->imagen) && !empty($data->imagen)  && isset($data->imagenHistoria) && !empty($data->imagenHistoria) && isset($data->imagenUva) && !empty($data->imagenUva) && isset($data->logo) && !empty($data->logo) && isset($data->historia) && !empty($data->historia) && isset($data->descripcion) && !empty($data->descripcion) && isset($data->descripcion_vinos) && !empty($data->descripcion_vinos) && isset($data->url) && !empty($data->url) && isset($data->region) && !empty($data->region));
    }

    //    /**
    //     * @return Denominacion[] Returns an array of Denominacion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Denominacion
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
