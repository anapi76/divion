<?php

namespace App\Entity;

use App\Repository\EspumosoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EspumosoRepository::class)]
#[ORM\Table(name: 'espumoso')]
class Espumoso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idEspumoso')]
    private ?int $id = null;

    #[ORM\Column(length: 25,unique:true)]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    #[ORM\OneToMany(targetEntity: Vino::class, mappedBy: 'espumoso')]
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
            $vino->setEspumoso($this);
        }

        return $this;
    }

    public function removeVino(Vino $vino): static
    {
        if ($this->vinos->removeElement($vino)) {
            // set the owning side to null (unless already changed)
            if ($vino->getEspumoso() === $this) {
                $vino->setEspumoso(null);
            }
        }

        return $this;
    }
}
