<?php

namespace App\Entity;

use App\Repository\VinoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VinoRepository::class)]
#[ORM\Table(name: 'vino')]
class Vino
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idVino')]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    #[ORM\Column(name: 'notaCata',type: Types::TEXT)]
    private ?string $notaCata = null;

    #[ORM\Column(length: 50)]
    private ?string $imagen = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idColor', referencedColumnName: 'idColor',nullable: false)]
    private ?Color $color = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idAzucar', referencedColumnName: 'idAzucar',nullable: true)]
    private ?Azucar $azucar = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idTipo', referencedColumnName: 'idTipo',nullable: false)]
    private ?TipoVino $tipoVino = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idMaduracion', referencedColumnName: 'idMaduracion',nullable: true)]
    private ?Maduracion $maduracion = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idBodega', referencedColumnName: 'idBodega',nullable: false)]
    private ?Bodega $bodega = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idSabor', referencedColumnName: 'idSabor',nullable: true)]
    private ?Sabor $sabor = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idCuerpo', referencedColumnName: 'idCuerpo',nullable: true)]
    private ?Cuerpo $cuerpo = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idBoca', referencedColumnName: 'idBoca',nullable: true)]
    private ?Boca $boca = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getNotaCata(): ?string
    {
        return $this->notaCata;
    }

    public function setNotaCata(string $notaCata): static
    {
        $this->notaCata = $notaCata;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): static
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getAzucar(): ?Azucar
    {
        return $this->azucar;
    }

    public function setAzucar(?Azucar $azucar): static
    {
        $this->azucar = $azucar;

        return $this;
    }

    public function getTipoVino(): ?TipoVino
    {
        return $this->tipoVino;
    }

    public function setTipoVino(?TipoVino $tipoVino): static
    {
        $this->tipoVino = $tipoVino;

        return $this;
    }

    public function getMaduracion(): ?Maduracion
    {
        return $this->maduracion;
    }

    public function setMaduracion(?Maduracion $maduracion): static
    {
        $this->maduracion = $maduracion;

        return $this;
    }

    public function getBodega(): ?Bodega
    {
        return $this->bodega;
    }

    public function setBodega(?Bodega $bodega): static
    {
        $this->bodega = $bodega;

        return $this;
    }

    public function getSabor(): ?Sabor
    {
        return $this->sabor;
    }

    public function setSabor(?Sabor $sabor): static
    {
        $this->sabor = $sabor;

        return $this;
    }

    public function getCuerpo(): ?Cuerpo
    {
        return $this->cuerpo;
    }

    public function setCuerpo(?Cuerpo $cuerpo): static
    {
        $this->cuerpo = $cuerpo;

        return $this;
    }

    public function getBoca(): ?Boca
    {
        return $this->boca;
    }

    public function setBoca(?Boca $boca): static
    {
        $this->boca = $boca;

        return $this;
    }
}
