<?php

namespace App\Entity;

use App\Repository\DenominacionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DenominacionRepository::class)]
#[ORM\Table(name: 'denominacion')]
class Denominacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idDo')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $certificada = false;

    #[ORM\Column(nullable: true)]
    private ?int $creacion = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $web = null;

    #[ORM\Column(length: 50)]
    private ?string $imagen = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $historia = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $vinos = null;

    #[ORM\ManyToOne(inversedBy: 'denominaciones')]
    #[ORM\JoinColumn(name: 'idRegion', referencedColumnName: 'idRegion',nullable: false)]
    private ?region $region = null;

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

    public function isCertificada(): ?bool
    {
        return $this->certificada;
    }

    public function setCertificada(bool $certificada): static
    {
        $this->certificada = $certificada;

        return $this;
    }

    public function getCreacion(): ?int
    {
        return $this->creacion;
    }

    public function setCreacion(?int $creacion): static
    {
        $this->creacion = $creacion;

        return $this;
    }

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function setWeb(?string $web): static
    {
        $this->web = $web;

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

    public function getHistoria(): ?string
    {
        return $this->historia;
    }

    public function setHistoria(string $historia): static
    {
        $this->historia = $historia;

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

    public function getVinos(): ?string
    {
        return $this->vinos;
    }

    public function setVinos(string $vinos): static
    {
        $this->vinos = $vinos;

        return $this;
    }

    public function getRegion(): ?region
    {
        return $this->region;
    }

    public function setRegion(?region $region): static
    {
        $this->region = $region;

        return $this;
    }
}
