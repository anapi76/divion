<?php

namespace App\Repository;

use App\Entity\TipoVino;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoVino>
 *
 * @method TipoVino|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoVino|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoVino[]    findAll()
 * @method TipoVino[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoVinoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoVino::class);
    }

//    /**
//     * @return TipoVino[] Returns an array of TipoVino objects
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

//    public function findOneBySomeField($value): ?TipoVino
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
