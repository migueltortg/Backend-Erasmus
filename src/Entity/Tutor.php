<?php

namespace App\Entity;

use App\Repository\TutorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TutorRepository::class)]
class Tutor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 9)]
    private ?string $dni = null;

    #[ORM\Column(length: 255)]
    private ?string $direccion = null;

    #[ORM\Column(length: 9)]
    private ?string $tfno = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'idTutor')]
    private Collection $idHijos;

    public function __construct()
    {
        $this->idHijos = new ArrayCollection();
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

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getTfno(): ?string
    {
        return $this->tfno;
    }

    public function setTfno(string $tfno): static
    {
        $this->tfno = $tfno;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getIdHijos(): Collection
    {
        return $this->idHijos;
    }

    public function addIdHijo(User $idHijo): static
    {
        if (!$this->idHijos->contains($idHijo)) {
            $this->idHijos->add($idHijo);
            $idHijo->addIdTutor($this);
        }

        return $this;
    }

    public function removeIdHijo(User $idHijo): static
    {
        if ($this->idHijos->removeElement($idHijo)) {
            $idHijo->removeIdTutor($this);
        }

        return $this;
    }
}
