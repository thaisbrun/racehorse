<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Requete
 *
 * @ORM\Table(name="requete", indexes={@ORM\Index(name="FK_Requete", columns={"idAuteurRequete"})})
 * @ORM\Entity(repositoryClass="App\Repository\RequeteRepository")
 */
class Requete
{
    /**
     * @var int
     *
     * @ORM\Column(name="idRequete", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idrequete;

    /**
     * @var string|null
     *
     * @ORM\Column(name="objet", type="string", length=30, nullable=true, options={"default"="NULL"})
     */
    private $objet ;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $description;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idAuteurRequete", referencedColumnName="idUtilisateur",onDelete="CASCADE")
     * })
     */
    private $idauteurrequete;

    public function getIdrequete(): ?int
    {
        return $this->idrequete;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(?string $objet): self
    {
        $this->objet = $objet;

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

    public function getIdauteurrequete(): ?Utilisateur
    {
        return $this->idauteurrequete;
    }

    public function setIdauteurrequete(?Utilisateur $idauteurrequete): self
    {
        $this->idauteurrequete = $idauteurrequete;

        return $this;
    }


}
