<?php

namespace App\Entity;

use App\Repository\DenominacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?bool $calificada = false;

    #[ORM\Column(nullable: true)]
    private ?int $creacion = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $web = null;

    #[ORM\Column(length: 50)]
    private ?string $imagen = null;

    #[ORM\Column(name:'imagen_historia',length: 50)]
    private ?string $imagenHistoria = null;

    #[ORM\Column(name:'imagen_uva',length: 50)]
    private ?string $imagenUva = null;

    #[ORM\Column(length: 50)]
    private ?string $logo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $historia = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    #[ORM\Column(name:'descripcionVinos', type: Types::TEXT)]
    private ?string $descripcionVinos = null;

    #[ORM\Column(length: 50)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'denominaciones')]
    #[ORM\JoinColumn(name: 'idRegion', referencedColumnName: 'idRegion',nullable: false)]
    private ?Region $region = null;

    #[ORM\OneToMany(targetEntity: Bodega::class, mappedBy: 'denominacion')]
    private Collection $bodegas;

    #[ORM\OneToMany(targetEntity: UvaDo::class, mappedBy: 'denominacion')]
    private Collection $uvas;


    public function __construct()
    {
        $this->bodegas = new ArrayCollection();
        $this->uvas = new ArrayCollection();
    }

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

    public function isCalificada(): ?bool
    {
        return $this->calificada;
    }

    public function setCalificada(bool $calificada): static
    {
        $this->calificada = $calificada;

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

    public function getLogo(): ?string
    {
        return $this->logo;
    }


    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getImagenHistoria(): ?string
    {
        return $this->imagenHistoria;
    }

    public function setImagenHistoria(?string $imagenHistoria): self
    {
        $this->imagenHistoria = $imagenHistoria;

        return $this;
    }

    public function getImagenUva(): ?string
    {
        return $this->imagenUva;
    }

    public function setImagenUva(?string $imagenUva): self
    {
        $this->imagenUva = $imagenUva;

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

    public function getDescripcionVinos(): ?string
    {
        return $this->descripcionVinos;
    }

    public function setDescripcionVinos(string $descripcionVinos): static
    {
        $this->descripcionVinos = $descripcionVinos;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

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

    /**
     * @return Collection<int, Bodega>
     */
    public function getBodegas(): Collection
    {
        return $this->bodegas;
    }

    public function addBodega(Bodega $bodega): static
    {
        if (!$this->bodegas->contains($bodega)) {
            $this->bodegas->add($bodega);
            $bodega->setDenominacion($this);
        }

        return $this;
    }

    public function removeBodega(Bodega $bodega): static
    {
        if ($this->bodegas->removeElement($bodega)) {
            // set the owning side to null (unless already changed)
            if ($bodega->getDenominacion() === $this) {
                $bodega->setDenominacion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UvaDo>
     */
    public function getUvas(): Collection
    {
        return $this->uvas;
    }

    public function addUva(UvaDo $uva): static
    {
        if (!$this->uvas->contains($uva)) {
            $this->uvas->add($uva);
            $uva->setDenominacion($this);
        }

        return $this;
    }

    public function removeUva(UvaDo $uva): static
    {
        if ($this->uvas->removeElement($uva)) {
            // set the owning side to null (unless already changed)
            if ($uva->getDenominacion() === $this) {
                $uva->setDenominacion(null);
            }
        }

        return $this;
    }

}
