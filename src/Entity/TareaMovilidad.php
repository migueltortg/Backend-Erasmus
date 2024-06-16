<?php

namespace App\Entity;

use App\Repository\TareaMovilidadRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TareaMovilidadRepository::class)]
class TareaMovilidad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tareaMovilidad:list', 'tareaMovilidad:detail'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['tareaMovilidad:list', 'tareaMovilidad:detail'])]
    private ?Tarea $idTarea = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Movilidad $idMovilidad = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tareaMovilidad:list', 'tareaMovilidad:detail'])]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['tareaMovilidad:list', 'tareaMovilidad:detail'])]
    private ?string $url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdTarea(): ?Tarea
    {
        return $this->idTarea;
    }

    public function setIdTarea(?Tarea $idTarea): static
    {
        $this->idTarea = $idTarea;

        return $this;
    }

    public function getIdMovilidad(): ?Movilidad
    {
        return $this->idMovilidad;
    }

    public function setIdMovilidad(?Movilidad $idMovilidad): static
    {
        $this->idMovilidad = $idMovilidad;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }
}
