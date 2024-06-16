<?php

namespace App\Entity;

use App\Repository\SolicitudRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SolicitudRepository::class)]
class Solicitud
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['solicitud:list', 'solicitud:detail'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'idSolicitudes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['solicitud:list', 'solicitud:detail'])]
    private ?User $idUser = null;

    #[ORM\ManyToOne(inversedBy: 'idSolicitudes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['solicitud:list', 'solicitud:detail'])]
    private ?Convocatoria $idConvocatoria = null;

    #[ORM\Column(length: 255)]
    #[Groups(['solicitud:list', 'solicitud:detail'])]
    private ?string $status = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['solicitud:list', 'solicitud:detail'])]
    private ?Grupo $idGrupo = null;

    #[ORM\Column]
    private ?int $nota = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdConvocatoria(): ?Convocatoria
    {
        return $this->idConvocatoria;
    }

    public function setIdConvocatoria(?Convocatoria $idConvocatoria): static
    {
        $this->idConvocatoria = $idConvocatoria;

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

    public function getIdGrupo(): ?Grupo
    {
        return $this->idGrupo;
    }

    public function setIdGrupo(?Grupo $idGrupo): static
    {
        $this->idGrupo = $idGrupo;

        return $this;
    }

    public function getNota(): ?int
    {
        return $this->nota;
    }

    public function setNota(int $nota): static
    {
        $this->nota = $nota;

        return $this;
    }
}
