<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[ORM\Table(name: 'region')]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idRegion')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\OneToMany(targetEntity: Denominacion::class, mappedBy: 'region')]
    private Collection $denominaciones;

    public function __construct()
    {
        $this->denominaciones = new ArrayCollection();
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

    /**
     * @return Collection<int, Denominacion>
     */
    public function getDenominaciones(): Collection
    {
        return $this->denominaciones;
    }

    public function addDenominacion(Denominacion $denominacion): static
    {
        if (!$this->denominaciones->contains($denominacion)) {
            $this->denominaciones->add($denominacion);
            $denominacion->setRegion($this);
        }

        return $this;
    }

    public function removeDenominacion(Denominacion $denominacion): static
    {
        if ($this->denominaciones->removeElement($denominacion)) {
            // set the owning side to null (unless already changed)
            if ($denominacion->getRegion() === $this) {
                $denominacion->setRegion(null);
            }
        }

        return $this;
    }
}
