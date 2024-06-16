<?php

namespace App\Entity;

use App\Repository\TareaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TareaRepository::class)]
class Tarea
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tareas:list', 'tareas:detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tareas:list', 'tareas:detail'])]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tareas:list', 'tareas:detail'])]
    private ?string $descripcion = null;

    #[ORM\Column]
    #[Groups(['tareas:list', 'tareas:detail'])]
    private ?bool $archivo = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Convocatoria $idConvocatoria = null;

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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function isArchivo(): ?bool
    {
        return $this->archivo;
    }

    public function setArchivo(bool $archivo): static
    {
        $this->archivo = $archivo;

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
}
