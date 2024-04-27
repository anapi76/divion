<?php

namespace App\Repository;

use App\Entity\Bodega;
use App\Entity\Denominacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
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

    public function findAllBodegas(): mixed
    {
        $bodegas = $this->findAll();
        if (empty($bodegas)) {
            return null;
        }
        $json = array();
        foreach ($bodegas as $bodega) {
            $json[] = $this->bodegasJSON($bodega);
        }
        return $json;
    }

    public function findBodega(Bodega $bodega): mixed
    {
        if (is_null($bodega)) {
            return null;
        }
        $json[] = $this->bodegasJSON($bodega);
        return $json;
    }

    public function bodegasJSON(Bodega $bodega): mixed
    {
        $json = array(
            'id' => $bodega->getId(),
            'nombre' => $bodega->getNombre(),
            'direccion' => $bodega->getDireccion(),
            'poblacion' => $bodega->getPoblacion(),
            'provincia' => $bodega->getProvincia(),
            'cod_postal' => $bodega->getCodPostal(),
            'email' => $bodega->getEmail(),
            'telefono' => $bodega->getTelefono(),
            'web' => $bodega->getWeb(),
            'bodega' => $bodega->getDenominacion()->getNombre(),
            'vinos'=>$this->vinosJSON($bodega->getVinos())
        );

        return $json;
    }

    public function vinosJSON(Collection $vinos): mixed
    {
        $json = array();
        foreach ($vinos as $vino) {
            $json[] = $vino->getNombre();
        }
        return $json;
    }

    public function new(string $nombre,string $direccion, string $poblacion, string $provincia, ?string $codPostal, ?string $email, ?string $telefono, ?string $web, Denominacion $denominacion, bool $flush): void
    {
        try {
            $bodega = new Bodega();
            $bodega->setNombre($nombre);
            $bodega->setDireccion($direccion);
            if (!is_null($poblacion)) $bodega->setPoblacion($poblacion);
            $bodega->setProvincia($provincia);
            if (!is_null($codPostal)) $bodega->setCodPostal($codPostal);
            if (!is_null($email)) $bodega->setEmail($email);
            if (!is_null($telefono)) $bodega->setTelefono($telefono);
            if (!is_null($web)) $bodega->setWeb($web);
            $bodega->setDenominacion($denominacion);
            $denominacion->addBodega($bodega);
            $this->save($bodega, $flush);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(Bodega $bodega, ?string $direccion, ?string $poblacion, ?string $provincia, ?string $codPostal, ?string $email, ?string $telefono, ?string $web, bool $flush): bool
    {
        try {
            $update=false;
            if (!is_null($direccion)){
                $bodega->setDireccion($direccion);
                $update=true;
            } 
            if (!is_null($poblacion)){
                $bodega->setPoblacion($poblacion);
                $update=true;
            } 
            if (!is_null($provincia)){
                $bodega->setProvincia($provincia);
                $update=true;
            } 
            if (!is_null($codPostal)){
                $bodega->setCodPostal($codPostal);
                $update=true;
            } 
            if (!is_null($email)){
                $bodega->setEmail($email);
                $update=true;
            }
            if (!is_null($telefono)){
                $bodega->setTelefono($telefono);
                $update=true;
            } 
            if (!is_null($web)){
                $bodega->setWeb($web);
                $update=true;
            } 
            $this->save($bodega, $flush);
            return $update;
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

    public function testInsert(string $nombre): bool
    {
        $entidad = $this->findOneBy(['nombre' => $nombre]);
        if (empty($entidad))
            return false;
        else {
            return true;
        }
    }

    public function testDelete(string $nombre): bool
    {
        $entidad = $this->findOneBy(['nombre' => $nombre]);
        if (empty($entidad))
            return true;
        else {
            return false;
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
