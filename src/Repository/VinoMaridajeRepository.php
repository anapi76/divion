<?php

namespace App\Repository;

use App\Entity\Vino;
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
    private MaridajeRepository $maridajeRepository;
    
    public function __construct(ManagerRegistry $registry, MaridajeRepository $maridajeRepository)
    {
        parent::__construct($registry, VinoMaridaje::class);
        $this->maridajeRepository = $maridajeRepository;
    }

    public function new(array $maridajes, Vino $vino): void
    {
        foreach ($maridajes as $maridajeId) {
            $maridaje = $this->maridajeRepository->find($maridajeId);
            $vinoMaridaje = new VinoMaridaje();
            $vinoMaridaje->setVino($vino);
            $vinoMaridaje->setMaridaje($maridaje);
            $this->getEntityManager()->persist($vinoMaridaje);
            $vino->addMaridaje($vinoMaridaje);
        }
    }

    public function save(VinoMaridaje $vinoMaridaje, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->persist($vinoMaridaje);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (\Exception $e) {
            throw $e;
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
