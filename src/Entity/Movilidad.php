<?php

namespace App\Entity;

use App\Repository\MovilidadRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MovilidadRepository::class)]
class Movilidad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['movilidad:list', 'movilidad:detail'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['movilidad:list', 'movilidad:detail'])]
    private ?Convocatoria $idConvocatoria = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['movilidad:list', 'movilidad:detail'])]
    private ?User $idUser = null;

    #[ORM\ManyToOne]
    private ?User $idCoordinador = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

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
}
