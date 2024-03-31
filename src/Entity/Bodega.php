<?php

namespace App\Entity;

use App\Repository\BodegaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BodegaRepository::class)]
#[ORM\Table(name: 'bodega')]
class Bodega
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idBodega')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\Column(length: 50)]
    private ?string $direccion = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $poblacion = null;

    #[ORM\Column(length: 25)]
    private ?string $provincia = null;

    #[ORM\Column(name: 'codPostal',length: 5, nullable: true)]
    private ?string $codPostal = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $telefono = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $web = null;

    #[ORM\ManyToOne(inversedBy: 'bodegas')]
    #[ORM\JoinColumn(name: 'idDo', referencedColumnName: 'idDo',nullable: false)]
    private ?Denominacion $denominacion = null;

    #[ORM\OneToMany(targetEntity: Vino::class, mappedBy: 'bodega')]
    private Collection $vinos;

    public function __construct()
    {
        $this->vinos = new ArrayCollection();
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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getPoblacion(): ?string
    {
        return $this->poblacion;
    }

    public function setPoblacion(?string $poblacion): static
    {
        $this->poblacion = $poblacion;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(string $provincia): static
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getCodPostal(): ?string
    {
        return $this->codPostal;
    }

    public function setCodPostal(?string $codPostal): static
    {
        $this->codPostal = $codPostal;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): static
    {
        $this->telefono = $telefono;

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

    public function getDenominacion(): ?Denominacion
    {
        return $this->denominacion;
    }

    public function setDenominacion(?Denominacion $denominacion): static
    {
        $this->denominacion = $denominacion;

        return $this;
    }

    /**
     * @return Collection<int, Vino>
     */
    public function getVinos(): Collection
    {
        return $this->vinos;
    }

    public function addVino(Vino $vino): static
    {
        if (!$this->vinos->contains($vino)) {
            $this->vinos->add($vino);
            $vino->setBodega($this);
        }

        return $this;
    }

    public function removeVino(Vino $vino): static
    {
        if ($this->vinos->removeElement($vino)) {
            // set the owning side to null (unless already changed)
            if ($vino->getBodega() === $this) {
                $vino->setBodega(null);
            }
        }

        return $this;
    }
}
