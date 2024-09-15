<?php

namespace App\Repository;

use App\Entity\Bodega;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bodega>
 *
 * @method Bodega|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bodega|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bodega[]    findAll()
 * @method Bodega[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BodegaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bodega::class);
    }

    public function save(Bodega $bodega, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->persist($bodega);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    } 

    public function remove(Bodega $bodega, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->remove($bodega);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    //    /**
    //     * @return Bodega[] Returns an array of Bodega objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Bodega
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
