<?php

namespace App\Entity;

use App\Repository\VinoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VinoRepository::class)]
#[ORM\Table(name: 'vino')]
class Vino
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idVino')]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $notaCata = null;

    #[ORM\Column(length: 50)]
    private ?string $imagen = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idColor', referencedColumnName: 'idColor',nullable: false)]
    private ?Color $color = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idAzucar', referencedColumnName: 'idAzucar',nullable: true)]
    private ?Azucar $azucar = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idTipo', referencedColumnName: 'idTipo',nullable: false)]
    private ?TipoVino $tipoVino = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idMaduracion', referencedColumnName: 'idMaduracion',nullable: true)]
    private ?Maduracion $maduracion = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idBodega', referencedColumnName: 'idBodega',nullable: false)]
    private ?Bodega $bodega = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idSabor', referencedColumnName: 'idSabor',nullable: true)]
    private ?Sabor $sabor = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idCuerpo', referencedColumnName: 'idCuerpo',nullable: true)]
    private ?Cuerpo $cuerpo = null;

    #[ORM\ManyToOne(inversedBy: 'vinos')]
    #[ORM\JoinColumn(name: 'idBoca', referencedColumnName: 'idBoca',nullable: true)]
    private ?Boca $boca = null;

    #[ORM\OneToMany(targetEntity: VinoUva::class, mappedBy: 'vino')]
    private Collection $uvas;

    #[ORM\OneToMany(targetEntity: VinoMaridaje::class, mappedBy: 'vino')]
    private Collection $maridajes;

    #[ORM\OneToMany(targetEntity: PuntuacionVino::class, mappedBy: 'vino')]
    private Collection $puntuaciones;

    public function __construct()
    {
        $this->uvas = new ArrayCollection();
        $this->maridajes = new ArrayCollection();
        $this->puntuaciones = new ArrayCollection();
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

    public function getNotaCata(): ?string
    {
        return $this->notaCata;
    }

    public function setNotaCata(string $notaCata): static
    {
        $this->notaCata = $notaCata;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): static
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getAzucar(): ?Azucar
    {
        return $this->azucar;
    }

    public function setAzucar(?Azucar $azucar): static
    {
        $this->azucar = $azucar;

        return $this;
    }

    public function getTipoVino(): ?TipoVino
    {
        return $this->tipoVino;
    }

    public function setTipoVino(?TipoVino $tipoVino): static
    {
        $this->tipoVino = $tipoVino;

        return $this;
    }

    public function getMaduracion(): ?Maduracion
    {
        return $this->maduracion;
    }

    public function setMaduracion(?Maduracion $maduracion): static
    {
        $this->maduracion = $maduracion;

        return $this;
    }

    public function getBodega(): ?Bodega
    {
        return $this->bodega;
    }

    public function setBodega(?Bodega $bodega): static
    {
        $this->bodega = $bodega;

        return $this;
    }

    public function getSabor(): ?Sabor
    {
        return $this->sabor;
    }

    public function setSabor(?Sabor $sabor): static
    {
        $this->sabor = $sabor;

        return $this;
    }

    public function getCuerpo(): ?Cuerpo
    {
        return $this->cuerpo;
    }

    public function setCuerpo(?Cuerpo $cuerpo): static
    {
        $this->cuerpo = $cuerpo;

        return $this;
    }

    public function getBoca(): ?Boca
    {
        return $this->boca;
    }

    public function setBoca(?Boca $boca): static
    {
        $this->boca = $boca;

        return $this;
    }

    /**
     * @return Collection<int, VinoUva>
     */
    public function getUvas(): Collection
    {
        return $this->uvas;
    }

    public function addUva(VinoUva $uva): static
    {
        if (!$this->uvas->contains($uva)) {
            $this->uvas->add($uva);
            $uva->setVino($this);
        }

        return $this;
    }

    public function removeUva(VinoUva $uva): static
    {
        if ($this->uvas->removeElement($uva)) {
            // set the owning side to null (unless already changed)
            if ($uva->getVino() === $this) {
                $uva->setVino(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, VinoMaridaje>
     */
    public function getMaridajes(): Collection
    {
        return $this->maridajes;
    }

    public function addMaridaje(VinoMaridaje $maridaje): static
    {
        if (!$this->maridajes->contains($maridaje)) {
            $this->maridajes->add($maridaje);
            $maridaje->setVino($this);
        }

        return $this;
    }

    public function removeMaridaje(VinoMaridaje $maridaje): static
    {
        if ($this->maridajes->removeElement($maridaje)) {
            // set the owning side to null (unless already changed)
            if ($maridaje->getVino() === $this) {
                $maridaje->setVino(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PuntuacionVino>
     */
    public function getPuntuaciones(): Collection
    {
        return $this->puntuaciones;
    }

    public function addPuntuacion(PuntuacionVino $puntuacion): static
    {
        if (!$this->puntuaciones->contains($puntuacion)) {
            $this->puntuaciones->add($puntuacion);
            $puntuacion->setVino($this);
        }

        return $this;
    }

    public function removePuntuacion(PuntuacionVino $puntuacion): static
    {
        if ($this->puntuaciones->removeElement($puntuacion)) {
            // set the owning side to null (unless already changed)
            if ($puntuacion->getVino() === $this) {
                $puntuacion->setVino(null);
            }
        }

        return $this;
    }
}
