<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    const REGISTRO_EXITOSO = 'Se ha registrado exitosamente, ya puede ingresar con sus credenciales.';//creo una constante para enviar un mensaje (es buena práctica de programación hacerlo así cuando son cosas que no van a cambiar)
    const EMAIL_DUPLICADO = 'El email que intenta ingresar pertenece a otro usuario.';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $baneado;


    /**
     * @ORM\Column(type="string")
     */
    private $nombre;


    /* RELACIONES */
     
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comentarios", mappedBy="user")
     */
    private $comentarios;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Posts", mappedBy="user")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Profesion", mappedBy="user")
     */
    private $profesiones;
    
    /**
     * User constructor.
     */
    public function __construct()//Creo un cronstructor que inicie dos variables con valor
    {
        $this->baneado = false;
        $this->roles = ['ROLE_USER'];
    }//Con esto al hacer "new User()", baneado será false y el rol será ROLE_USER
    //Tanto el campo "baneado" como el "roles" los saqué del formulario para que no los ingrese el usuario, pero como no pueden ser null los inicializo acá
    //Es buena práctica de programación hacerlo así y no seteandolos en el RewistroController, como los había hecho 
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


     /**
     * @return mixed
     */
    public function getBaneado()
    {
        return $this->baneado;
    }

    /**
     * @param mixed $baneado
     */
    public function setBaneado($baneado):void
    {
        $this->baneado = $baneado;
    }


    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre):void
    {
        $this->nombre = $nombre;
    }


    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
