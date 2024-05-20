<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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
    private $id;
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
     *   @ORM\JoinColumn(name="idEquideA", referencedColumnName="idEquide",onDelete="CASCADE")
     * })
     */
    private $equide;

    /**
     * @var \Typeannonce
     *
     * @ORM\ManyToOne(targetEntity="Typeannonce")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTypeA", referencedColumnName="idTypeAnnonce",onDelete="CASCADE")
     * })
     */
    private $typeA;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUtilisateurAnnonce", referencedColumnName="idUtilisateur",onDelete="CASCADE")
     * })
     */
    private $utilisateurannonce;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Favoris", mappedBy="idannoncefav")
     */
    private Collection $favoris;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Image", mappedBy="idannonceimage", cascade={"persist"})
     */
    private Collection $images;
    public function __construct(){
        $this->favoris = new ArrayCollection();
        $this->images = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
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
    public function getEquide(): ?Equide
    {
        return $this->equide;
    }
    public function setEquide(?Equide $equide): self
    {
        $this->equideA = $equide;

        return $this;
    }
    public function getTypeA(): ?Typeannonce
    {
        return $this->typeA;
    }
    public function setTypeA(?Typeannonce $typeA): self
    {
        $this->typeA = $typeA;

        return $this;
    }
    public function getUtilisateurAnnonce(): ?Utilisateur
    {
        return $this->utilisateurannonce;
    }
    public function setUtilisateurAnnonce(?Utilisateur $utilisateurannonce): self
    {
        $this->utilisateurannonce = $utilisateurannonce;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param Collection $images
     */
    public function setImages(Collection $images): void
    {
        $this->images = $images;
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
            if($favori->getUtilisateurFav() === $utilisateur) return true;
        }
        return false;
    }

    public function __toString(){
        return $this->getDescription();
    }
    public function addImage(UploadedFile $file): void
    {
        // Générez un nom de fichier unique
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        // Déplacez le fichier vers le répertoire cible
        $file->move(
            'imgAnnonce/',
            $fileName
        );

        // Créez une nouvelle instance de l'entité Image
        $image = new Image();
        $image->setLienImage('imgAnnonce/' . $fileName);
        $image->setAnnonceImage($this);

        // Ajoutez l'image à la collection
        $this->images[] = $image;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            if ($image->getAnnonceImage() === $this) {
                $image->setAnnonceImage(null);
            }
        }
        return $this;
    }
}
