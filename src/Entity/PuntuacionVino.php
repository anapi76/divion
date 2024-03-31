<?php

namespace App\Entity;

use App\Repository\PuntuacionVinoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PuntuacionVinoRepository::class)]
#[ORM\Table(name: 'puntuacion_vino')]
class PuntuacionVino
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idPuntuacion', referencedColumnName: 'idPuntuacion',nullable: false)]
    private ?Puntuacion $puntuacion = null;

    #[ORM\ManyToOne(inversedBy: 'puntuaciones')]
    #[ORM\JoinColumn(name: 'idVino', referencedColumnName: 'idVino',nullable: false)]
    private ?Vino $vino = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $usuario = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comentarios = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPuntuacion(): ?Puntuacion
    {
        return $this->puntuacion;
    }

    public function setPuntuacion(?Puntuacion $puntuacion): static
    {
        $this->puntuacion = $puntuacion;

        return $this;
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

    public function getUsuario(): ?string
    {
        return $this->usuario;
    }

    public function setUsuario(?string $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getComentarios(): ?string
    {
        return $this->comentarios;
    }

    public function setComentarios(?string $comentarios): static
    {
        $this->comentarios = $comentarios;

        return $this;
    }
}
