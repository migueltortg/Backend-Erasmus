<?php
namespace App\Entity;

use App\Repository\ItemBaremableConvocatoriaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ItemBaremableConvocatoriaRepository::class)]
class ItemBaremableConvocatoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['itemBaremable:list', 'itemBaremable:detail', 'convocatoria:detail'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['itemBaremable:list', 'itemBaremable:detail', 'convocatoria:detail'])]
    private ?bool $obligatorio = null;

    #[ORM\Column]
    #[Groups(['itemBaremable:list', 'itemBaremable:detail', 'convocatoria:detail'])]
    private ?bool $presentaUser = null;

    #[ORM\Column]
    #[Groups(['itemBaremable:list', 'itemBaremable:detail', 'convocatoria:detail'])]
    private ?int $valorMin = null;

    #[ORM\Column]
    #[Groups(['itemBaremable:list', 'itemBaremable:detail', 'convocatoria:detail'])]
    private ?int $valorMax = null;

    #[ORM\ManyToOne(inversedBy: 'itemsBaremables')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['itemBaremable:detail'])]
    private ?Convocatoria $idConvocatoria = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['itemBaremable:list', 'itemBaremable:detail', 'convocatoria:detail'])]
    private ?string $nombre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isObligatorio(): ?bool
    {
        return $this->obligatorio;
    }

    public function setObligatorio(bool $obligatorio): static
    {
        $this->obligatorio = $obligatorio;

        return $this;
    }

    public function isPresentaUser(): ?bool
    {
        return $this->presentaUser;
    }

    public function setPresentaUser(bool $presentaUser): static
    {
        $this->presentaUser = $presentaUser;

        return $this;
    }

    public function getValorMin(): ?int
    {
        return $this->valorMin;
    }

    public function setValorMin(int $valorMin): static
    {
        $this->valorMin = $valorMin;

        return $this;
    }

    public function getValorMax(): ?int
    {
        return $this->valorMax;
    }

    public function setValorMax(int $valorMax): static
    {
        $this->valorMax = $valorMax;

        return $this;
    }

    public function getIdConvocatoria(): ?Convocatoria
    {
        return $this->idConvocatoria;
    }

    public function setIdConvocatoria(?Convocatoria $idConvocatoria): static
    {
        $this->idConvocatoria = $idConvocatoria;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }
}