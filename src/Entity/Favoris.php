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
    private $idutilisateurfav;

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
    private $idannoncefav;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateCreation", type="date", nullable=true, options={"default"="NULL"})
     */
    private $datecreation = 'NULL';

    public function getIdutilisateurfav(): ?Utilisateur
    {
        return $this->idutilisateurfav;
    }
    public function setIdutilisateurfav(?Utilisateur $idutilisateurfav): self
    {
        $this->idutilisateurfav = $idutilisateurfav;

        return $this;
    }
    public function getIdannoncefav(): ?Annonce
    {
        return $this->idannoncefav;
    }
    public function setIdannoncefav(?Annonce $idannoncefav): self
    {
        $this->idannoncefav = $idannoncefav;

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
        return $this->getIdannoncefav().toString();
    }
}
