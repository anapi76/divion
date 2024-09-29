<?php

namespace App\Repository;

use App\Entity\Vino;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Vino
 *
 * @method Vino|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vino|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vino[]    findAll()
 * @method Vino[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VinoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vino::class);
    }

    public function save(Vino $vino, bool $flush = false): void
    {
        $this->getEntityManager()->persist($vino);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Vino $vino, bool $flush = false): void
    {
        $this->getEntityManager()->remove($vino);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Vino[] Returns an array of Vino objects
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

    //    public function findOneBySomeField($value): ?Vino
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
