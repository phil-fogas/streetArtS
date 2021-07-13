<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class Users
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=50, nullable=false)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=60, nullable=false)
     */
    private $password;

    /**
     * @var int
     *
     * @ORM\Column(name="droit", type="integer", nullable=false)
     */
    private $droit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateVisi", type="datetime", nullable=true)
     */
    private $datevisi;

    /**
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Street", mappedBy="users")
     */
    private $user;

      /**
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Droit", inversedBy="role", fetch="EAGER")
     * @ORM\JoinColumn(name="droit", referencedColumnName="id")
     */
    private $role;

    public function __construct() {
        $this->user= new ArrayCollection();
       
    }    

    public function getRole(): ?Droit
    {
        return $this->role;
    }

    public function setRole(?Droit $role): self
    {
        $this->role= $role;

        return $this;
    } 
    public function getUser(): ?string
    {
        return $this->user;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDroit(): ?int
    {
        return $this->droit;
    }

    public function setDroit(int $droit): self
    {
        $this->droit = $droit;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDatevisi(): ?\DateTimeInterface
    {
        return $this->datevisi;
    }

    public function setDatevisi(?\DateTimeInterface $datevisi): self
    {
        $this->datevisi = $datevisi;

        return $this;
    }


}
