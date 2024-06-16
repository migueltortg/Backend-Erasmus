<?php

namespace App\Entity;

use App\Repository\ItemBaremableSolicitudRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemBaremableSolicitudRepository::class)]
class ItemBaremableSolicitud
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Solicitud $idSolicitud = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ItemBaremableConvocatoria $idItemBaremable = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSolicitud(): ?Solicitud
    {
        return $this->idSolicitud;
    }

    public function setIdSolicitud(?Solicitud $idSolicitud): static
    {
        $this->idSolicitud = $idSolicitud;

        return $this;
    }

    public function getIdItemBaremable(): ?ItemBaremableConvocatoria
    {
        return $this->idItemBaremable;
    }

    public function setIdItemBaremable(?ItemBaremableConvocatoria $idItemBaremable): static
    {
        $this->idItemBaremable = $idItemBaremable;

        return $this;
    }
}
