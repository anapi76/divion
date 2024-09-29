<?php

namespace App\Repository;

use App\Entity\Puntuacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Puntuacion>
 *
 * @method Puntuacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Puntuacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Puntuacion[]    findAll()
 * @method Puntuacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PuntuacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Puntuacion::class);
    }

    //    /**
    //     * @return Puntuacion[] Returns an array of Puntuacion objects
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

    //    public function findOneBySomeField($value): ?Puntuacion
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
