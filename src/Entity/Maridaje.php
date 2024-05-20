<?php

namespace App\Entity;

use App\Repository\MaridajeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaridajeRepository::class)]
#[ORM\Table(name: 'maridaje')]
class Maridaje
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idMaridaje')]
    private ?int $id = null;

    #[ORM\Column(length: 50,unique:true)]
    private ?string $nombre = null;

    #[ORM\OneToMany(targetEntity: VinoMaridaje::class, mappedBy: 'maridaje')]
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

    /**
     * @return Collection<int, VinoMaridaje>
     */
    public function getVinos(): Collection
    {
        return $this->vinos;
    }

    public function addVino(VinoMaridaje $vino): static
    {
        if (!$this->vinos->contains($vino)) {
            $this->vinos->add($vino);
            $vino->setMaridaje($this);
        }

        return $this;
    }

    public function removeVino(VinoMaridaje $vino): static
    {
        if ($this->vinos->removeElement($vino)) {
            if ($vino->getMaridaje() === $this) {
                $vino->setMaridaje(null);
            }
        }

        return $this;
    }
}
