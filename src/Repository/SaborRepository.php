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
