<?php

namespace App\Repository;

use App\Entity\PuntuacionVino;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PuntuacionVino>
 *
 * @method PuntuacionVino|null find($id, $lockMode = null, $lockVersion = null)
 * @method PuntuacionVino|null findOneBy(array $criteria, array $orderBy = null)
 * @method PuntuacionVino[]    findAll()
 * @method PuntuacionVino[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PuntuacionVinoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PuntuacionVino::class);
    }

    //    /**
    //     * @return PuntuacionVino[] Returns an array of PuntuacionVino objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PuntuacionVino
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
