<?php

namespace App\Repository;

use App\Entity\Boca;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Boca>
 *
 * @method Boca|null find($id, $lockMode = null, $lockVersion = null)
 * @method Boca|null findOneBy(array $criteria, array $orderBy = null)
 * @method Boca[]    findAll()
 * @method Boca[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BocaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Boca::class);
    }

    //    /**
    //     * @return Boca[] Returns an array of Boca objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Boca
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
