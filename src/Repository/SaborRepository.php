<?php

namespace App\Repository;

use App\Entity\Sabor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sabor>
 *
 * @method Sabor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sabor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sabor[]    findAll()
 * @method Sabor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaborRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sabor::class);
    }

    public function findAllSaboresByColor(int $idColor): mixed
    {
        $sabores= $this->createQueryBuilder('s')
            ->distinct()
            ->innerJoin('App\Entity\Vino', 'v', 'WITH', 's.id = v.sabor')
            ->where('v.color = :idColor')
            ->setParameter('idColor', $idColor)
            ->getQuery()
            ->getResult();
            if(empty($sabores)){
                return null;
            }
            $json = array(
                'info' => array('count' => count($sabores)),
                'results' => array()
            );
            foreach ($sabores as $sabor) {
                $json['results'][] = $this->saboresJSON($sabor);
            }
            return $json;
    }

    public function saboresJSON(Sabor $sabor): mixed
    {
        $json= array(
            'id' => $sabor->getId(),
            'nombre' => $sabor->getNombre()
        );

        return $json;
    }

    //    /**
    //     * @return Sabor[] Returns an array of Sabor objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Sabor
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
