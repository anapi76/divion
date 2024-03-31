<?php

namespace App\Entity;

use App\Repository\UvaDoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UvaDoRepository::class)]
#[ORM\Table(name: 'uva_do')]
class UvaDo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'denominaciones')]
    #[ORM\JoinColumn(name: 'idUva', referencedColumnName: 'idUva',nullable: false)]
    private ?Uva $uva = null;

    #[ORM\ManyToOne(inversedBy: 'uvas')]
    #[ORM\JoinColumn(name: 'idDo', referencedColumnName: 'idDo',nullable: false)]
    private ?Denominacion $denominacion = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDenominacion(): ?Denominacion
    {
        return $this->denominacion;
    }

    public function setDenominacion(?Denominacion $denominacion): static
    {
        $this->denominacion = $denominacion;

        return $this;
    }
}
