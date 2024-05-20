<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favoris
 *
 * @ORM\Table(name="favoris")
 * @ORM\Entity(repositoryClass="App\Repository\FavorisRepository")
 */
class Favoris
{
    /**
     * @var \Utilisateur
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUtilisateurFav", referencedColumnName="idUtilisateur",onDelete="CASCADE")
     * })
     */
    private $utilisateurfav;

    /**
     * @var \Annonce
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\ManyToOne(targetEntity="Annonce")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idAnnonceFav", referencedColumnName="idAnnonce",onDelete="CASCADE")
     * })
     */
    private $annoncefav;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateCreation", type="date", nullable=true, options={"default"="NULL"})
     */
    private $datecreation = 'NULL';

    public function getIdutilisateurfav(): ?Utilisateur
    {
        return $this->utilisateurfav;
    }
    public function setUtilisateurfav(?Utilisateur $utilisateurfav): self
    {
        $this->utilisateurfav = $utilisateurfav;

        return $this;
    }
    public function getAnnoncefav(): ?Annonce
    {
        return $this->annoncefav;
    }
    public function setAnnoncefav(?Annonce $annoncefav): self
    {
        $this->annoncefav = $annoncefav;

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
    public function __toString(){
        return $this->getAnnoncefav().toString();
    }
}
