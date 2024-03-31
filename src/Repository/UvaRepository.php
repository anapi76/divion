<?php

namespace App\Repository;

use App\Entity\Uva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Uva>
 *
 * @method Uva|null find($id, $lockMode = null, $lockVersion = null)
 * @method Uva|null findOneBy(array $criteria, array $orderBy = null)
 * @method Uva[]    findAll()
 * @method Uva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UvaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Uva::class);
    }

    //    /**
    //     * @return Uva[] Returns an array of Uva objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Uva
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
