<?php

namespace App\Repository;

use App\Entity\Denominacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
