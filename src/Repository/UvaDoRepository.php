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
    private UvaRepository $uvaRepository;

    public function __construct(ManagerRegistry $registry, UvaRepository $uvaRepository)
    {
        parent::__construct($registry, UvaDo::class);
        $this->uvaRepository = $uvaRepository;
    }

    public function new(array $tiposUva, Denominacion $denominacion): void
    {
        foreach ($tiposUva as $uvaId) {
            $uvaDo = new UvaDo();
            $uvaDo->setDenominacion($denominacion);
            $uva = $this->uvaRepository->find($uvaId);
            $uvaDo->setUva($uva);
            $denominacion->addUva($uvaDo);
            $this->getEntityManager()->persist($uvaDo);
        }
    }

    public function remove(UvaDo $uvaDo, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->remove($uvaDo);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (\Exception $e) {
            throw $e;
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
