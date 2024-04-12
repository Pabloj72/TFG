<?php

// src/Entity/User.php
namespace App\Entity;

use App\Repository\UsuariosRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UsuariosRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Usuarios implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true, name: 'email')]
    private ?string $email;

    #[ORM\Column(type: 'string', length: 255, unique: true, name: 'username', nullable: true)]
    private ?string $username;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string', name: 'password')]
    private string $password;

    #[ORM\Column(type: 'string', length: 10, nullable: true)] // 'hombre', 'mujer', etc.
    private ?string $genero;

    #[ORM\Column(type: 'integer', nullable: true)] // Altura en centímetros
    private ?int $altura;

    #[ORM\Column(type: 'float', nullable: true)] // Peso en kilogramos
    private ?float $peso;

    #[ORM\Column(type: 'integer', nullable: true)] // Edad en años
    private ?int $edad;

    #[ORM\Column(type: 'string', length: 20, nullable: true)] // 'sedentario', 'actividad ligera', etc.
    private ?string $intensidadFisica;
    
    #[ORM\OneToMany(mappedBy: "usuario", targetEntity: RegistroPeso::class)]
    private $registroPesos;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(?string $genero): self
    {
        $this->genero = $genero;
        return $this;
    }

    public function getAltura(): ?int
    {
        return $this->altura;
    }

    public function setAltura(?int $altura): self
    {
        $this->altura = $altura;
        return $this;
    }

    public function getPeso(): ?float
    {
        return $this->peso;
    }

    public function setPeso(?float $peso): self
    {
        $this->peso = $peso;
        return $this;
    }

    public function getEdad(): ?int
    {
        return $this->edad;
    }

    public function setEdad(?int $edad): self
    {
        $this->edad = $edad;
        return $this;
    }

    public function getIntensidadFisica(): ?string
    {
        return $this->intensidadFisica;
    }

    public function setIntensidadFisica(?string $intensidadFisica): self
    {
        $this->intensidadFisica = $intensidadFisica;
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
    public function __construct()
    {
        $this->registroPesos = new ArrayCollection();
    }

    /**
     * @return Collection|RegistroPeso[]
     */
    public function getRegistroPesos(): Collection
    {
        return $this->registroPesos;
    }

    public function addRegistroPeso(RegistroPeso $registroPeso): self
    {
        if (!$this->registroPesos->contains($registroPeso)) {
            $this->registroPesos[] = $registroPeso;
            $registroPeso->setUsuario($this);
        }

        return $this;
    }

    public function removeRegistroPeso(RegistroPeso $registroPeso): self
    {
        if ($this->registroPesos->removeElement($registroPeso)) {
            // set the owning side to null (unless already changed)
            if ($registroPeso->getUsuario() === $this) {
                $registroPeso->setUsuario(null);
            }
        }

        return $this;
    }
    public function getLastWeight(): ?float
{
    $registroPesos = $this->getRegistroPesos()->toArray();
    if (!empty($registroPesos)) {
        /** @var RegistroPeso $ultimoRegistro */
        $ultimoRegistro = end($registroPesos);
        return $ultimoRegistro->getPeso();
    }
    return null;
}

}
