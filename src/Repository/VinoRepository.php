<?php

namespace App\Repository;

use App\Entity\Azucar;
use App\Entity\Boca;
use App\Entity\Bodega;
use App\Entity\Color;
use App\Entity\Cuerpo;
use App\Entity\Maduracion;
use App\Entity\Maridaje;
use App\Entity\Sabor;
use App\Entity\TipoVino;
use App\Entity\Vino;
use App\Entity\VinoMaridaje;
use App\Entity\VinoUva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vino>
 *
 * @method Vino|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vino|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vino[]    findAll()
 * @method Vino[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VinoRepository extends ServiceEntityRepository
{

    private VinoMaridajeRepository $vinoMaridajeRepository;
    private VinoUvaRepository $vinoUvaRepository;


    public function __construct(ManagerRegistry $registry, VinoUvaRepository $vinoUvaRepository, VinoMaridajeRepository $vinoMaridajeRepository)
    {
        parent::__construct($registry, Vino::class);
        $this->vinoUvaRepository = $vinoUvaRepository;
        $this->vinoMaridajeRepository = $vinoMaridajeRepository;
    }

    public function findAllVinos(): mixed
    {
        $vinos = $this->findAll();
        if (empty($vinos)) {
            return null;
        }
        $json = array();
        foreach ($vinos as $vino) {
            $json[] = $this->vinosJSON($vino);
        }
        return $json;
    }

    public function findVino(Vino $vino): mixed
    {
        if (is_null($vino)) {
            return null;
        }
        $json[] = $this->vinosJSON($vino);
        return $json;
    }

    public function new(string $nombre, string $descripicion, string $notaCata, string $imagen, Color $color, ?Azucar $azucar, TipoVino $tipoVino, ?Maduracion $maduracion, Bodega $bodega, ?Sabor $sabor, ?Cuerpo $cuerpo, ?Boca $boca, array $uvas, array $maridajes, bool $flush): void
    {
        try {
            $vino = new Vino();
            $vino->setNombre($nombre);
            $vino->setDescripcion($descripicion);
            $vino->setNotaCata($notaCata);
            $vino->setImagen($imagen);
            $vino->setColor($color);
            if (!is_null($azucar)) $vino->setAzucar($azucar);
            if (!is_null($tipoVino)) $vino->setTipoVino($tipoVino);
            if (!is_null($maduracion)) $vino->setMaduracion($maduracion);
            $vino->setBodega($bodega);
            if (!is_null($sabor)) $vino->setSabor($sabor);
            if (!is_null($cuerpo)) $vino->setCuerpo($cuerpo);
            if (!is_null($boca)) $vino->setBoca($boca);
            $bodega->addVino($vino);
            $this->vinoUvaRepository->new($uvas, $vino);
            $this->vinoMaridajeRepository->new($maridajes, $vino);
            $this->save($vino, $flush);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function vinosJSON(Vino $vino): mixed
    {
        $azucar = ($vino->getAzucar() == null) ? null : $vino->getAzucar()->getNombre();
        $maduracion = ($vino->getMaduracion() == null) ? null : $vino->getMaduracion()->getNombre();
        $sabor = ($vino->getSabor() == null) ? null : $vino->getSabor()->getNombre();
        $cuerpo = ($vino->getCuerpo() == null) ? null : $vino->getCuerpo()->getNombre();
        $boca = ($vino->getBoca() == null) ? null : $vino->getBoca()->getNombre();
        $json = array(
            'id' => $vino->getId(),
            'nombre' => $vino->getNombre(),
            'descripcion' => $vino->getDescripcion(),
            'notaCata' => $vino->getNotaCata(),
            'imagen' => $vino->getImagen(),
            'color' => $vino->getColor()->getNombre(),
            'azucar' => $azucar,
            'tipoVino' => $vino->getTipoVino()->getNombre(),
            'maduracion' => $maduracion,
            'sabor' => $sabor,
            'cuerpo' => $cuerpo,
            'boca' => $boca,
            'uvas' => $this->uvasJSON($vino->getUvas()),
            'maridajes' => $this->maridajesJSON($vino->getMaridajes()),
            'puntuaciones' => $this->puntuacionesJSON($vino->getPuntuaciones())
        );

        return $json;
    }

    public function uvasJSON(Collection $uvas): mixed
    {
        $json = array();
        foreach ($uvas as $uva) {
            $json[] = $uva->getUva()->getNombre();
        }
        return $json;
    }

    public function maridajesJSON(Collection $maridajes): mixed
    {
        $json = array();
        foreach ($maridajes as $maridaje) {
            $json[] = $maridaje->getMaridaje()->getNombre();
        }
        return $json;
    }

    public function puntuacionesJSON(Collection $puntuaciones): mixed
    {
        $json = array();
        foreach ($puntuaciones as $puntuacion) {
            $json[] = [
                $puntuacion->getPuntuacion()->getPuntos(),
                $puntuacion->getPuntuacion()->getDescripcion()
            ];
        }
        return $json;
    }

    public function save(Vino $vino, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->persist($vino);
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
