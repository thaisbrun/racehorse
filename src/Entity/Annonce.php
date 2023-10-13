<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Utilisateur;
use PhpParser\ErrorHandler\Collecting;
use Symfony\Component\HttpFoundation\Session\Session;


/**
 * Annonce
 *
 * @ORM\Table(name="annonce", indexes={@ORM\Index(name="FK_EquideAnnonce", columns={"idEquideA"}), @ORM\Index(name="FK_UtilisateurAnnonce", columns={"idUtilisateurAnnonce"}), @ORM\Index(name="FK_TypeAnnonce", columns={"idTypeA"})})
 * @ORM\Entity(repositoryClass="App\Repository\AnnonceRepository")
 */
class Annonce
{
    /**
     * @var int
     *
     * @ORM\Column(name="idAnnonce", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idannonce;
    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=30, nullable=false)
     */
    private $titre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=true, options={"default"="NULL"})
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer", nullable=false)
     */
    private $prix;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="activation", type="boolean", nullable=true, options={"default"="true"})
     */
    private $activation = 'NULL';

    /**
     * @var \DateTime

     * @ORM\Column(name="dateCreation", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */

    private $datecreation;

    /**
     * @var \Equide
     *
     * @ORM\ManyToOne(targetEntity="Equide")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEquideA", referencedColumnName="idEquide",onDelete={"persist"})
     * })
     */
    private $idequidea;

    /**
     * @var \Typeannonce
     *
     * @ORM\ManyToOne(targetEntity="Typeannonce")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTypeA", referencedColumnName="idTypeAnnonce",onDelete="CASCADE")
     * })
     */
    private $idtypea;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUtilisateurAnnonce", referencedColumnName="idUtilisateur",onDelete="CASCADE")
     * })
     */
    private $idutilisateurannonce;
    private array $listImages;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class)]
    #[JoinTable('favoris')]
    private Collection $likes;

    public function __construct(){
        $this->likes = new ArrayCollection();
    }
    public function getIdannonce(): ?int
    {
        return $this->idannonce;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function isActivation(): ?bool
    {
        return $this->activation;
    }

    public function setActivation(?bool $activation): self
    {
        $this->activation = $activation;

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

    public function getIdequidea(): Equide
    {
        return $this->idequidea;
    }

    public function setIdequidea(?Equide $idequidea): self
    {
        $this->idequidea = $idequidea;

        return $this;
    }

    public function getIdtypea(): ?Typeannonce
    {
        return $this->idtypea;
    }

    public function setIdtypea(?Typeannonce $idtypea): self
    {
        $this->idtypea = $idtypea;

        return $this;
    }

    public function getIdutilisateurannonce(): ?Utilisateur
    {
        return $this->idutilisateurannonce;
    }

    public function setIdutilisateurannonce(?Utilisateur $idutilisateurannonce): self
    {
        $this->idutilisateurannonce = $idutilisateurannonce;

        return $this;
    }

    /**
     * @return array
     */
    public function getListImages(): array
    {
        return $this->listImages;
    }

    /**
     * @param array $listImages
     */
    public function setListImages(array $listImages): void
    {
        $this->listImages = $listImages;
    }

    public function __toString(){
        return $this->getDescription();
    }
}
