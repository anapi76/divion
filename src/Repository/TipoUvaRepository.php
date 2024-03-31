<?php

namespace App\Repository;

use App\Entity\TipoUva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoUva>
 *
 * @method TipoUva|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoUva|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoUva[]    findAll()
 * @method TipoUva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoUvaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoUva::class);
    }

    //    /**
    //     * @return TipoUva[] Returns an array of TipoUva objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TipoUva
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
