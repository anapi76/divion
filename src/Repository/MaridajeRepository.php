<?php

namespace App\Repository;

use App\Entity\Maridaje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Maridaje>
 *
 * @method Maridaje|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maridaje|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maridaje[]    findAll()
 * @method Maridaje[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaridajeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Maridaje::class);
    }

    public function findAllMaridajesByColor(int $idColor): mixed
    {
        $maridajes= $this->createQueryBuilder('m')
            ->distinct()
            ->innerJoin('App\Entity\VinoMaridaje', 'vm', 'WITH', 'm.id = vm.maridaje')
            ->innerJoin('App\Entity\Vino', 'v', 'WITH', 'vm.vino = v.id')
            ->where('v.color = :idColor')
            ->setParameter('idColor', $idColor)
            ->getQuery()
            ->getResult();
            if(empty($maridajes)){
                return null;
            }
            $json = array(
                'info' => array('count' => count($maridajes)),
                'results' => array()
            );
            foreach ($maridajes as $maridaje) {
                $json['results'][] = $this->maridajesJSON($maridaje);
            }
            return $json;
    }

    public function findAllMaridajesByEspumoso(int $idEspumoso): mixed
    {
        $maridajes= $this->createQueryBuilder('m')
            ->distinct()
            ->innerJoin('App\Entity\VinoMaridaje', 'vm', 'WITH', 'm.id = vm.maridaje')
            ->innerJoin('App\Entity\Vino', 'v', 'WITH', 'vm.vino = v.id')
            ->where('v.espumoso = :idEspumoso')
            ->setParameter('idEspumoso', $idEspumoso)
            ->getQuery()
            ->getResult();
            if(empty($maridajes)){
                return null;
            }
            $json = array(
                'info' => array('count' => count($maridajes)),
                'results' => array()
            );
            foreach ($maridajes as $maridaje) {
                $json['results'][] = $this->maridajesJSON($maridaje);
            }
            return $json;
    }

    public function findMaridaje(Maridaje $maridaje): mixed
    {
        if (is_null($maridaje)) {
            return null;
        }
        $json['results'][] = $this->maridajesJSON($maridaje);
        return $json;
    }

    public function maridajesJSON(Maridaje $maridaje): mixed
    {
        $json= array(
            'id' => $maridaje->getId(),
            'nombre' => $maridaje->getNombre()
        );
        return $json;
    }


    //    /**
    //     * @return Maridaje[] Returns an array of Maridaje objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Maridaje
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
