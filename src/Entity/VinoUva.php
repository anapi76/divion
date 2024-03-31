<?php

namespace App\Entity;

use App\Repository\VinoUvaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VinoUvaRepository::class)]
#[ORM\Table(name: 'vino_uva')]
class VinoUva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'uvas')]
    #[ORM\JoinColumn(name: 'idVino', referencedColumnName: 'idVino', nullable: false)]
    private ?Vino $vino = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idUva', referencedColumnName: 'idUva', nullable: false)]
    private ?Uva $uva = null;

    #[ORM\Column(nullable: true)]
    private ?int $porcentaje = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVino(): ?Vino
    {
        return $this->vino;
    }

    public function setVino(?Vino $vino): static
    {
        $this->vino = $vino;

        return $this;
    }

    public function getUva(): ?Uva
    {
        return $this->uva;
    }

    public function setUva(?Uva $uva): static
    {
        $this->uva = $uva;

        return $this;
    }

    public function getPorcentaje(): ?int
    {
        return $this->porcentaje;
    }

    public function setPorcentaje(?int $porcentaje): static
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }
}
