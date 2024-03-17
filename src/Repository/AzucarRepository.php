<?php

namespace App\Repository;

use App\Entity\Azucar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Azucar>
 *
 * @method Azucar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Azucar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Azucar[]    findAll()
 * @method Azucar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AzucarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Azucar::class);
    }

    //    /**
    //     * @return Azucar[] Returns an array of Azucar objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Azucar
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
