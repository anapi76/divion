<?php

namespace App\Repository;

use App\Entity\VinoUva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VinoUva>
 *
 * @method VinoUva|null find($id, $lockMode = null, $lockVersion = null)
 * @method VinoUva|null findOneBy(array $criteria, array $orderBy = null)
 * @method VinoUva[]    findAll()
 * @method VinoUva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VinoUvaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VinoUva::class);
    }

//    /**
//     * @return VinoUva[] Returns an array of VinoUva objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VinoUva
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
