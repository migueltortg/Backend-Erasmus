<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['user:list'])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['user:list'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 9)]
    #[Groups(['user:list'])]
    private ?string $anoEscolar = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:list'])]
    private ?string $nombre = null;

    #[ORM\Column(length: 9)]
    #[Groups(['user:list'])]
    private ?string $dni = null;

    #[ORM\Column(length: 9)]
    #[Groups(['user:list'])]
    private ?string $tfno = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:list'])]
    private ?string $direccion = null;

    /**
     * @var Collection<int, Tutor>
     */
    #[ORM\ManyToMany(targetEntity: Tutor::class, inversedBy: 'idHijos')]
    private Collection $idTutor;

    /**
     * @var Collection<int, Solicitud>
     */
    #[ORM\OneToMany(targetEntity: Solicitud::class, mappedBy: 'idUser')]
    private Collection $idSolicitudes;

    #[ORM\Column(length: 255)]
    #[Groups(['user:list'])]
    private ?string $apellido = null;

    public function __construct()
    {
        $this->idTutor = new ArrayCollection();
        $this->idSolicitudes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAnoEscolar(): ?string
    {
        return $this->anoEscolar;
    }

    public function setAnoEscolar(string $anoEscolar): static
    {
        $this->anoEscolar = $anoEscolar;

        return $this;
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

    public function getTfno(): ?string
    {
        return $this->tfno;
    }

    public function setTfno(string $tfno): static
    {
        $this->tfno = $tfno;

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

    /**
     * @return Collection<int, Tutor>
     */
    public function getIdTutor(): Collection
    {
        return $this->idTutor;
    }

    public function addIdTutor(Tutor $idTutor): static
    {
        if (!$this->idTutor->contains($idTutor)) {
            $this->idTutor->add($idTutor);
        }

        return $this;
    }

    public function removeIdTutor(Tutor $idTutor): static
    {
        $this->idTutor->removeElement($idTutor);

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
            $idSolicitude->setIdUser($this);
        }

        return $this;
    }

    public function removeIdSolicitude(Solicitud $idSolicitude): static
    {
        if ($this->idSolicitudes->removeElement($idSolicitude)) {
            // set the owning side to null (unless already changed)
            if ($idSolicitude->getIdUser() === $this) {
                $idSolicitude->setIdUser(null);
            }
        }

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
    }
}
