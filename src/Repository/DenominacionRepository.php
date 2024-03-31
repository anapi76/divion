<?php

namespace App\Repository;

use App\Entity\Denominacion;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Denominacion::class);
    }

    public function denominacionesJSON(): mixed
    {
        $denominaciones = $this->findAll();
        if (empty($denominaciones)) {
            return null;
        } else {
            $json = array();
            foreach ($denominaciones as $denominacion) {
                $certificada = ($denominacion->isCertificada()) ? 'Denominación de origen calificada' : '';
                $json[] = array(
                    'id' => $denominacion->getId(),
                    'nombre' => $denominacion->getNombre(),
                    'calificada' => $certificada,
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
            }
            return $json;
        }
    }

    public function denominacionJSON(Denominacion $denominacion): mixed
    {
        $json = array();
        $certificada = ($denominacion->isCertificada()) ? 'Denominación de origen calificada' : '';
        $json[] = array(
            'id' => $denominacion->getId(),
            'nombre' => $denominacion->getNombre(),
            'calificada' => $certificada,
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
