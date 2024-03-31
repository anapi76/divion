<?php

namespace App\Entity;

use App\Repository\UvaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UvaRepository::class)]
#[ORM\Table(name: 'uva')]
class Uva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idUva')]
    private ?int $id = null;

    #[ORM\Column(length: 25, unique: true)]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(inversedBy: 'uvas')]
    #[ORM\JoinColumn(name: 'idTipo', referencedColumnName: 'idTipo', nullable: false)]
    private ?TipoUva $TipoUva = null;

    #[ORM\OneToMany(targetEntity: UvaDo::class, mappedBy: 'uva')]
    private Collection $denominaciones;

    #[ORM\OneToMany(targetEntity: VinoUva::class, mappedBy: 'uva')]
    private Collection $vinos;

    public function __construct()
    {
        $this->denominaciones = new ArrayCollection();
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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getTipoUva(): ?TipoUva
    {
        return $this->TipoUva;
    }

    public function setTipoUva(?TipoUva $TipoUva): static
    {
        $this->TipoUva = $TipoUva;

        return $this;
    }

    /**
     * @return Collection<int, UvaDo>
     */
    public function getDenominaciones(): Collection
    {
        return $this->denominaciones;
    }

    public function addDenominacion(UvaDo $denominacion): static
    {
        if (!$this->denominaciones->contains($denominacion)) {
            $this->denominaciones->add($denominacion);
            $denominacion->setUva($this);
        }

        return $this;
    }

    public function removeDenominacion(UvaDo $denominacion): static
    {
        if ($this->denominaciones->removeElement($denominacion)) {
            // set the owning side to null (unless already changed)
            if ($denominacion->getUva() === $this) {
                $denominacion->setUva(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, VinoUva>
     */
    public function getVinos(): Collection
    {
        return $this->vinos;
    }

    public function addVino(VinoUva $vino): static
    {
        if (!$this->vinos->contains($vino)) {
            $this->vinos->add($vino);
            $vino->setUva($this);
        }

        return $this;
    }

    public function removeVino(VinoUva $vino): static
    {
        if ($this->vinos->removeElement($vino)) {
            // set the owning side to null (unless already changed)
            if ($vino->getUva() === $this) {
                $vino->setUva(null);
            }
        }

        return $this;
    }
}
