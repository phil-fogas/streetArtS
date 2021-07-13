<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/**
 * Droit
 *
 * @ORM\Table(name="droit")
 * @ORM\Entity
 */
class Droit
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
     * @ORM\Column(name="name", type="string", length=20, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="valid", type="integer", nullable=false)
     */
    private $valid;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer", nullable=false)
     */
    private $statut;

    
    /**
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Users", mappedBy="role")
     
     */
    private $role;

    public function __construct() {
        $this->role= new ArrayCollection();
    }

    /**
     * @return Collection|Role[]
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValid(): ?int
    {
        return $this->valid;
    }

    public function setValid(int $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(int $statut): self
    {
        $this->statut = $statut;

        return $this;
    }


}
