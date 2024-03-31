<?php

namespace App\Entity;

use App\Repository\TipoUvaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipoUvaRepository::class)]
#[ORM\Table(name: 'tipo_uva')]
class TipoUva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idTipo')]
    private ?int $id = null;

    #[ORM\Column(length: 15, unique: true)]
    private ?string $nombre = null;

    #[ORM\OneToMany(targetEntity: Uva::class, mappedBy: 'TipoUva')]
    private Collection $uvas;

    public function __construct()
    {
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

    /**
     * @return Collection<int, Uva>
     */
    public function getUvas(): Collection
    {
        return $this->uvas;
    }

    public function addUva(Uva $uva): static
    {
        if (!$this->uvas->contains($uva)) {
            $this->uvas->add($uva);
            $uva->setTipoUva($this);
        }

        return $this;
    }

    public function removeUva(Uva $uva): static
    {
        if ($this->uvas->removeElement($uva)) {
            // set the owning side to null (unless already changed)
            if ($uva->getTipoUva() === $this) {
                $uva->setTipoUva(null);
            }
        }

        return $this;
    }
}
