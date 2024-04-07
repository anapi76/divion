<?php

namespace App\Repository;

use App\Entity\Denominacion;
use App\Entity\Region;
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

    public function denominacionesJSON(): mixed
    {
        $denominaciones = $this->findAll();
        if (empty($denominaciones)) {
            return null;
        } else {
            $json = array();
            foreach ($denominaciones as $denominacion) {
                $calificada = ($denominacion->isCalificada()) ? 'Denominación de origen calificada' : '';
                $json[] = array(
                    'id' => $denominacion->getId(),
                    'nombre' => $denominacion->getNombre(),
                    'calificada' => $calificada,
                    'creacion' => $denominacion->getCreacion(),
                    'web' => $denominacion->getWeb(),
                    'imagen' => $denominacion->getImagen(),
                    'historia' => $denominacion->getHistoria(),
                    'descripcion' => $denominacion->getDescripcion(),
                    'tipo_vinos' => $denominacion->getTipoVinos(),
                    'region' => $denominacion->getRegion()->getNombre(),
                    'bodegas' => $this->bodegasJSON($denominacion->getBodegas()),
                    'uvas_permitidas' => $this->uvasJSON($denominacion->getUvas()),
                );
            }
            return $json;
        }
    }

    public function denominacionJSON(Denominacion $denominacion): mixed
    {
        $json = array();
        $calificada = ($denominacion->isCalificada()) ? 'Denominación de origen calificada' : '';
        $json[] = array(
            'id' => $denominacion->getId(),
            'nombre' => $denominacion->getNombre(),
            'calificada' => $calificada,
            'creacion' => $denominacion->getCreacion(),
            'web' => $denominacion->getWeb(),
            'imagen' => $denominacion->getImagen(),
            'historia' => $denominacion->getHistoria(),
            'descripcion' => $denominacion->getDescripcion(),
            'vinos' => $denominacion->getTipoVinos(),
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
            $json[] = $bodega->getNombre();
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

    public function new(string $nombre, bool $calificada, ?int $creacion, ?string $web, string $imagen, string $historia, string $descripcion, string $tipoVinos, Region $region, array $tiposUva, bool $flush): void
    {
        try {
            $denominacion = new Denominacion();
            $denominacion->setNombre($nombre);
            $denominacion->setCalificada($calificada);
            if (!is_null($creacion)) $denominacion->setCreacion($creacion);
            if (!is_null($web)) $denominacion->setWeb($web);
            $denominacion->setImagen($imagen);
            $denominacion->setHistoria($historia);
            $denominacion->setDescripcion($descripcion);
            $denominacion->setTipoVinos($tipoVinos);
            $denominacion->setRegion($region);
            $this->uvaDoRepository->new($tiposUva, $denominacion);
            $this->save($denominacion, $flush);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(Denominacion $denominacion, bool $calificada, ?string $web, ?string $imagen, ?string $historia, ?string $descripcion, ?string $tipoVinos, ?array $tiposUva, bool $flush): void
    {
        try {
            $denominacion->setCalificada($calificada);
            if (!is_null($web)) $denominacion->setWeb($web);
            if (!is_null($imagen)) $denominacion->setImagen($imagen);
            if (!is_null($historia)) $denominacion->setHistoria($historia);
            if (!is_null($descripcion)) $denominacion->setDescripcion($descripcion);
            if (!is_null($tipoVinos)) $denominacion->setTipoVinos($tipoVinos);
            if (!is_null($tiposUva)) $this->uvaDoRepository->new($tiposUva, $denominacion);
            $this->save($denominacion, $flush);
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

    //método para comprobar si se ha insertado el proveedor
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
