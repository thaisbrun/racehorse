<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Favoris", mappedBy="idannoncefav")
     */
    private Collection $favoris;
    private array $listImages;
    public function __construct(){
        $this->favoris = new ArrayCollection();
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

    /**
     * @return Collection
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    /**
     * @param Collection $favoris
     */
    public function setFavoris(Collection $favoris): void
    {
        $this->favoris = $favoris;
    }

    /**
     * Permet de savoir si cet utilisateur a liké cette annonce
     * @param Utilisateur $utilisateur
     * @return bool
     */
    public function isLikedByUser(Utilisateur $utilisateur) : bool {
        foreach($this->favoris as $favori) {
            if($favori->getIdUtilisateurFav() === $utilisateur) return true;
        }
        return false;
    }

}
