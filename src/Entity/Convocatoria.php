<?php

namespace App\Entity;

use App\Repository\ConvocatoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ConvocatoriaRepository::class)]
class Convocatoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?string $tipoMovilidad = null;

    #[ORM\Column]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?int $numMovilidades = null;

    #[ORM\Column]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private array $paises = [];

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?\DateTimeInterface $fechaInicio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?\DateTimeInterface $fechaFin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?\DateTimeInterface $fechaInscripcionInicio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?\DateTimeInterface $fechaInscripcionFin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?\DateTimeInterface $fechaListaProvisional = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?\DateTimeInterface $fechaApelacionesInicio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?\DateTimeInterface $fechaApelacionesFin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?\DateTimeInterface $fechaListaFinal = null;

    #[ORM\OneToMany(targetEntity: Solicitud::class, mappedBy: 'idConvocatoria', orphanRemoval: true)]
    #[Groups(['convocatoria:detail'])]
    private Collection $idSolicitudes;

    #[ORM\ManyToOne(inversedBy: 'idConvocatorias')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?User $idCoordinador = null;

    /**
     * @var Collection<int, ItemBaremableConvocatoria>
     */
    #[ORM\OneToMany(targetEntity: ItemBaremableConvocatoria::class, mappedBy: 'idConvocatoria')]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private Collection $itemsBaremables;

    #[ORM\Column(length: 255)]
    #[Groups(['convocatoria:list', 'convocatoria:detail'])]
    private ?string $status = null;

    public function __construct()
    {
        $this->idSolicitudes = new ArrayCollection();
        $this->itemsBaremables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;
        return $this;
    }

    public function getTipoMovilidad(): ?string
    {
        return $this->tipoMovilidad;
    }

    public function setTipoMovilidad(string $tipoMovilidad): static
    {
        $this->tipoMovilidad = $tipoMovilidad;
        return $this;
    }

    public function getNumMovilidades(): ?int
    {
        return $this->numMovilidades;
    }

    public function setNumMovilidades(int $numMovilidades): static
    {
        $this->numMovilidades = $numMovilidades;
        return $this;
    }

    public function getPaises(): array
    {
        return $this->paises;
    }

    public function setPaises(array $paises): static
    {
        $this->paises = $paises;
        return $this;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTimeInterface $fechaInicio): static
    {
        $this->fechaInicio = $fechaInicio;
        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(\DateTimeInterface $fechaFin): static
    {
        $this->fechaFin = $fechaFin;
        return $this;
    }

    public function getFechaInscripcionInicio(): ?\DateTimeInterface
    {
        return $this->fechaInscripcionInicio;
    }

    public function setFechaInscripcionInicio(\DateTimeInterface $fechaInscripcionInicio): static
    {
        $this->fechaInscripcionInicio = $fechaInscripcionInicio;
        return $this;
    }

    public function getFechaInscripcionFin(): ?\DateTimeInterface
    {
        return $this->fechaInscripcionFin;
    }

    public function setFechaInscripcionFin(\DateTimeInterface $fechaInscripcionFin): static
    {
        $this->fechaInscripcionFin = $fechaInscripcionFin;
        return $this;
    }

    public function getFechaListaProvisional(): ?\DateTimeInterface
    {
        return $this->fechaListaProvisional;
    }

    public function setFechaListaProvisional(\DateTimeInterface $fechaListaProvisional): static
    {
        $this->fechaListaProvisional = $fechaListaProvisional;
        return $this;
    }

    public function getFechaApelacionesInicio(): ?\DateTimeInterface
    {
        return $this->fechaApelacionesInicio;
    }

    public function setFechaApelacionesInicio(\DateTimeInterface $fechaApelacionesInicio): static
    {
        $this->fechaApelacionesInicio = $fechaApelacionesInicio;
        return $this;
    }

    public function getFechaApelacionesFin(): ?\DateTimeInterface
    {
        return $this->fechaApelacionesFin;
    }

    public function setFechaApelacionesFin(\DateTimeInterface $fechaApelacionesFin): static
    {
        $this->fechaApelacionesFin = $fechaApelacionesFin;
        return $this;
    }

    public function getFechaListaFinal(): ?\DateTimeInterface
    {
        return $this->fechaListaFinal;
    }

    public function setFechaListaFinal(\DateTimeInterface $fechaListaFinal): static
    {
        $this->fechaListaFinal = $fechaListaFinal;
        return $this;
    }

    /**
     * @return Collection<int, Solicitud>
     */
    public function getIdSolicitudes(): Collection
    {
        return $this->idSolicitudes;
    }

    public function addIdSolicitude(Solicitud $idSolicitude): static
    {
        if (!$this->idSolicitudes->contains($idSolicitude)) {
            $this->idSolicitudes->add($idSolicitude);
            $idSolicitude->setIdConvocatoria($this);
        }
        return $this;
    }

    public function removeIdSolicitude(Solicitud $idSolicitude): static
    {
        if ($this->idSolicitudes->removeElement($idSolicitude)) {
            if ($idSolicitude->getIdConvocatoria() === $this) {
                $idSolicitude->setIdConvocatoria(null);
            }
        }
        return $this;
    }

    public function getIdCoordinador(): ?User
    {
        return $this->idCoordinador;
    }

    public function setIdCoordinador(?User $idCoordinador): static
    {
        $this->idCoordinador = $idCoordinador;
        return $this;
    }

    /**
     * @return Collection<int, ItemBaremableConvocatoria>
     */
    public function getItemsBaremables(): Collection
    {
        return $this->itemsBaremables;
    }

    public function addItemsBaremable(ItemBaremableConvocatoria $itemsBaremable): static
    {
        if (!$this->itemsBaremables->contains($itemsBaremable)) {
            $this->itemsBaremables->add($itemsBaremable);
            $itemsBaremable->setIdConvocatoria($this);
        }

        return $this;
    }

    public function removeItemsBaremable(ItemBaremableConvocatoria $itemsBaremable): static
    {
        if ($this->itemsBaremables->removeElement($itemsBaremable)) {
            // set the owning side to null (unless already changed)
            if ($itemsBaremable->getIdConvocatoria() === $this) {
                $itemsBaremable->setIdConvocatoria(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
