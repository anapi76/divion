<?php

namespace App\Repository;

use App\Entity\Cuerpo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cuerpo>
 *
 * @method Cuerpo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cuerpo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cuerpo[]    findAll()
 * @method Cuerpo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuerpoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cuerpo::class);
    }

    //    /**
    //     * @return Cuerpo[] Returns an array of Cuerpo objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Cuerpo
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
