<?php

namespace App\Repository;

use App\Entity\Puntuacion;
use App\Entity\PuntuacionVino;
use App\Entity\Region;
use App\Entity\Vino;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PuntuacionVino>
 *
 * @method PuntuacionVino|null find($id, $lockMode = null, $lockVersion = null)
 * @method PuntuacionVino|null findOneBy(array $criteria, array $orderBy = null)
 * @method PuntuacionVino[]    findAll()
 * @method PuntuacionVino[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PuntuacionVinoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PuntuacionVino::class);
    }

    public function findAllPuntuaciones(): mixed
    {
        $puntuaciones = $this->findAll();
        if (empty($puntuaciones)) {
            return null;
        }
        $json = array();
        foreach ($puntuaciones as $puntuacion) {
            $json[] = $this->puntuacionJSON($puntuacion);
        }
        return $json;
    }

    public function findAllByVino(Vino $vino): mixed
    {
        $puntuaciones = $this->findBy(["vino" => $vino]);
        if (empty($puntuaciones)) {
            return null;
        }
        $json = array();
        foreach ($puntuaciones as $puntuacion) {
            $json[] = $this->puntuacionJSON($puntuacion);
        }
        return $json;
    }

    public function new(Vino $vino, Puntuacion $puntuacion, ?string $comentarios, ?string $usuario, bool $flush): bool
    {
        try {
            $puntuacionVino = new PuntuacionVino();
            $puntuacionVino->setVino($vino);
            $puntuacionVino->setPuntuacion($puntuacion);
            if (!is_null($comentarios)) $puntuacionVino->setComentarios($comentarios);
            if (!is_null($usuario)) $puntuacionVino->setUsuario($usuario);
            return ($this->save($puntuacionVino, $flush));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function save(PuntuacionVino $puntuacionVino, bool $flush = false): bool
    {
        try {
            $this->getEntityManager()->persist($puntuacionVino);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
            return ($flush);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function remove(PuntuacionVino $puntuacionVino, bool $flush = false):bool
    {
        try {
            $this->getEntityManager()->remove($puntuacionVino);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
            return $flush;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function puntuacionJson(PuntuacionVino $puntuacion): mixed
    {
        $json = array(
            'id' => $puntuacion->getId(),
            'vino' => $puntuacion->getVino()->getNombre(),
            'puntuacion' => $puntuacion->getPuntuacion()->getPuntos(),
            'descripcion' => $puntuacion->getPuntuacion()->getDescripcion(),
            'comentarios' => $puntuacion->getComentarios(),
            'usuario' => $puntuacion->getUsuario()
        );

        return $json;
    }

    public function requiredFields(Object $data): bool
    {
        return (isset($data->vino) && !empty($data->vino) && isset($data->puntuacion) && !empty($data->puntuacion));
    }

    //    /**
    //     * @return PuntuacionVino[] Returns an array of PuntuacionVino objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PuntuacionVino
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
