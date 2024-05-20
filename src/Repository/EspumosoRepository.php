<?php

namespace App\Repository;

use App\Entity\Espumoso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Espumoso>
 *
 * @method Espumoso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Espumoso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Espumoso[]    findAll()
 * @method Espumoso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EspumosoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Espumoso::class);
    }

    public function findAllEspumosos(): mixed
    {
        $espumosos = $this->findAll();
        if (empty($espumosos)) {
            return null;
        }
        $json = array(
            'info' => array('count' => count($espumosos)),
            'results' => array()
        );
        foreach ($espumosos as $espumoso) {
            $json['results'][] = $this->espumososJSON($espumoso);
        }
        return $json;
    }

    public function espumososJSON(Espumoso $espumoso): mixed
    {
        $json= array(
            'id' => $espumoso->getId(),
            'nombre' => $espumoso->getNombre()
        );

        return $json;
    }

//    /**
//     * @return Espumoso[] Returns an array of Espumoso objects
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
