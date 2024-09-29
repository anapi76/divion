<?php

namespace App\Repository;

use App\Entity\VinoMaridaje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VinoMaridaje>
 *
 * @method VinoMaridaje|null find($id, $lockMode = null, $lockVersion = null)
 * @method VinoMaridaje|null findOneBy(array $criteria, array $orderBy = null)
 * @method VinoMaridaje[]    findAll()
 * @method VinoMaridaje[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VinoMaridajeRepository extends ServiceEntityRepository
{


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VinoMaridaje::class);
    }

    public function save(VinoMaridaje $vinoMaridaje, bool $flush = false): void
    {
        $this->getEntityManager()->persist($vinoMaridaje);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VinoMaridaje $vinoMaridaje, bool $flush = false): void
    {
        $this->getEntityManager()->remove($vinoMaridaje);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return VinoMaridaje[] Returns an array of VinoMaridaje objects
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

    //    public function findOneBySomeField($value): ?VinoMaridaje
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
