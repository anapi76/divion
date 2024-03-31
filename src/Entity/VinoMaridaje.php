<?php

namespace App\Entity;

use App\Repository\VinoMaridajeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VinoMaridajeRepository::class)]
#[ORM\Table(name: 'vino_maridaje')]
class VinoMaridaje
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'maridajes')]
    #[ORM\JoinColumn(name: 'idVino', referencedColumnName: 'idVino',nullable: false)]
    private ?Vino $vino = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idMaridaje', referencedColumnName: 'idMaridaje',nullable: false)]
    private ?Maridaje $maridaje = null;

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

    public function getMaridaje(): ?Maridaje
    {
        return $this->maridaje;
    }

    public function setMaridaje(?Maridaje $maridaje): static
    {
        $this->maridaje = $maridaje;

        return $this;
    }
}
