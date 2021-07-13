<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Validation
 *
 * @ORM\Table(name="validation", indexes={@ORM\Index(name="street_id_valid", columns={"id_street"}), @ORM\Index(name="user_id_valid", columns={"id_user"})})
 * @ORM\Entity
 */
class Validation
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
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var int
     *
     * @ORM\Column(name="id_street", type="integer", nullable=false)
     */
    private $idStreet;

    /**
     * @var string
     *
     * @ORM\Column(name="chose", type="text", length=0, nullable=false)
     */
    private $chose;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdStreet(): ?int
    {
        return $this->idStreet;
    }

    public function setIdStreet(int $idStreet): self
    {
        $this->idStreet = $idStreet;

        return $this;
    }

    public function getChose(): ?string
    {
        return $this->chose;
    }

    public function setChose(string $chose): self
    {
        $this->chose = $chose;

        return $this;
    }


}
