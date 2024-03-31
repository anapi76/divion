<?php

namespace App\Repository;

use App\Entity\Maridaje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
