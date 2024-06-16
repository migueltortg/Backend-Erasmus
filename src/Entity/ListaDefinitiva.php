<?php

namespace App\Entity;

use App\Repository\ListaDefinitivaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListaDefinitivaRepository::class)]
class ListaDefinitiva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Convocatoria $idConvocatoria = null;

    #[ORM\Column]
    private array $listado = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdConvocatoria(): ?Convocatoria
    {
        return $this->idConvocatoria;
    }

    public function setIdConvocatoria(Convocatoria $idConvocatoria): static
    {
        $this->idConvocatoria = $idConvocatoria;

        return $this;
    }

    public function getListado(): array
    {
        return $this->listado;
    }

    public function setListado(array $listado): static
    {
        $this->listado = $listado;

        return $this;
    }
}
