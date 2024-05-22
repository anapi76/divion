<?php

namespace App\Repository;

use App\Entity\Vino;
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
    private UvaRepository $uvaRepository;
    
    public function __construct(ManagerRegistry $registry, UvaRepository $uvaRepository)
    {
        parent::__construct($registry, VinoUva::class);
        $this->uvaRepository = $uvaRepository;
    }

    public function new(array $uvas, Vino $vino): void
    {
        foreach ($uvas as $uvaId) {
            $uva = $this->uvaRepository->find($uvaId);
            $vinoUva = new VinoUva();
            $vinoUva->setVino($vino);
            $vinoUva->setUva($uva);
            $this->getEntityManager()->persist($vinoUva);
            $vino->addUva($vinoUva);
        }
    }

    public function save(VinoUva $vinoUva, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->persist($vinoUva);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function remove(VinoUva $vinoUva, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->remove($vinoUva);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (\Exception $e) {
            throw $e;
        }
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
