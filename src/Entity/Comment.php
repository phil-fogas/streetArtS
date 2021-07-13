<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment", indexes={@ORM\Index(name="street_id", columns={"id_street"}), @ORM\Index(name="user_id", columns={"id_users"})})
 * @ORM\Entity
 */
class Comment
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
     * @ORM\Column(name="text", type="text", length=0, nullable=false)
     */
    private $text;

    /**
     * @var int
     *
     * @ORM\Column(name="id_users", type="integer", nullable=false)
     */
    private $idUsers;

    /**
     * @var int
     *
     * @ORM\Column(name="id_street", type="integer", nullable=false)
     */
    private $idStreet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ad", type="datetime", nullable=false)
     */
    private $dateAd;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getIdUsers(): ?int
    {
        return $this->idUsers;
    }

    public function setIdUsers(int $idUsers): self
    {
        $this->idUsers = $idUsers;

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

    public function getDateAd(): ?\DateTimeInterface
    {
        return $this->dateAd;
    }

    public function setDateAd(\DateTimeInterface $dateAd): self
    {
        $this->dateAd = $dateAd;

        return $this;
    }


}
