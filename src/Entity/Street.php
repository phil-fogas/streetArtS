<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Street
 *
 * @ORM\Table(name="street", indexes={@ORM\Index(name="user_id_street", columns={"id_user"})})
 * @ORM\Entity
 */
class Street
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
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse", type="string", length=200, nullable=true)
     */
    private $adresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo", type="string", length=100, nullable=true)
     */
    private $photo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=0, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateCreation", type="date", nullable=true)
     */
    private $datecreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFiche", type="datetime", nullable=false)
     */
    private $datefiche;

    /**
     * @var int|null
     *
     * @ORM\Column(name="valid", type="integer", nullable=true)
     */
    private $valid;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer", nullable=false)
     */
    private $statut;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var int
     *
     * @ORM\Column(name="categorie", type="integer", nullable=false)
     */
    private $categorie;
   
    /**
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="cate", fetch="EAGER")
     * @ORM\JoinColumn(name="categorie", referencedColumnName="id")
     */
    private $cate;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="user", fetch="EAGER")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $user;

     /**
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Statut", inversedBy="etat", fetch="EAGER")
     * @ORM\JoinColumn(name="statut", referencedColumnName="id")
     */
    private $etat;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $longitude;


    public function getEtat(): ?Statut
    {
        return $this->etat;
    }

    public function setEtat(?Statut $etat): self
    {
        $this->etat= $etat;

        return $this;
    }  

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setuser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }   

    public function getCate(): ?Categorie
    {
        return $this->cate;
    }

    public function setCategory(?Categorie $cate): self
    {
        $this->category = $cate;

        return $this;
    }
  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(?\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getDatefiche(): ?\DateTimeInterface
    {
        return $this->datefiche;
    }

    public function setDatefiche(\DateTimeInterface $datefiche): self
    {
        $this->datefiche = $datefiche;

        return $this;
    }

    public function getValid(): ?int
    {
        return $this->valid;
    }

    public function setValid(?int $valid): self
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

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getCategorie(): ?int
    {
        return $this->categorie;
    }

    public function setCategorie(int $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }


}
