<?php

namespace App\Repository;

use App\Entity\Denominacion;
use App\Entity\UvaDo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UvaDo>
 *
 * @method UvaDo|null find($id, $lockMode = null, $lockVersion = null)
 * @method UvaDo|null findOneBy(array $criteria, array $orderBy = null)
 * @method UvaDo[]    findAll()
 * @method UvaDo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UvaDoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UvaDo::class);
    }

    public function save(UvaDo $uvaDo, bool $flush = false): void
    {
        $this->getEntityManager()->persist($uvaDo);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UvaDo $uvaDo, bool $flush = false): void
    {
        $this->getEntityManager()->remove($uvaDo);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return UvaDo[] Returns an array of UvaDo objects
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

    //    public function findOneBySomeField($value): ?UvaDo
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
