<?php

namespace App\Entity;

use App\Repository\PuntuacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PuntuacionRepository::class)]
#[ORM\Table(name: 'puntuacion')]
class Puntuacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idPuntuacion')]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private ?int $puntos = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    #[ORM\OneToMany(targetEntity: PuntuacionVino::class, mappedBy: 'puntuacion')]
    private Collection $vinos;

    public function __construct()
    {
        $this->vinos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPuntos(): ?int
    {
        return $this->puntos;
    }

    public function setPuntos(?int $puntos): static
    {
        $this->puntos = $puntos;

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

    /**
     * @return Collection<int, PuntuacionVino>
     */
    public function getVinos(): Collection
    {
        return $this->vinos;
    }

    public function addVino(PuntuacionVino $vino): static
    {
        if (!$this->vinos->contains($vino)) {
            $this->vinos->add($vino);
            $vino->setPuntuacion($this);
        }

        return $this;
    }

    public function removeVino(PuntuacionVino $vino): static
    {
        if ($this->vinos->removeElement($vino)) {
            // set the owning side to null (unless already changed)
            if ($vino->getPuntuacion() === $this) {
                $vino->setPuntuacion(null);
            }
        }

        return $this;
    }
}
