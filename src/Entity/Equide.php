<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Equide
 *
 * @ORM\Table(name="equide", indexes={@ORM\Index(name="FK_EquideDep", columns={"idDep"}), @ORM\Index(name="FK_EquideProprio", columns={"idProprio"}), @ORM\Index(name="FK_TypeEquide", columns={"idTypeEq"})})
 * @ORM\Entity(repositoryClass="App\Repository\EquideRepository")
 */
class Equide
{
    /**
     * @var int
     *
     * @ORM\Column(name="idEquide", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idequide;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=40, nullable=true, options={"default"="NULL"})
     */
    private $nom = 'NULL';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateNaiss", type="datetime", nullable=true, options={"default"="NULL"})
     */
    private $datenaiss = NULL;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Robe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="robe", referencedColumnName="id")
     * })
     */
    private $robe = NULL;

    /**
     *
     *
     *  @ORM\ManyToOne(targetEntity="Race")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="race", referencedColumnName="id")
     * })
     */
    private $race = NULL;

    /**
     * @var int|null
     *
     * @ORM\Column(name="taille", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $taille = NULL;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lienHN", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $lienhn = 'NULL';

    /**
     * @var \Typeequide
     *
     * @ORM\ManyToOne(targetEntity="Typeequide")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTypeEq", referencedColumnName="idTypeEquide")
     * })
     */
    private $idtypeeq;

    /**
     * @var \Departement
     *
     * @ORM\ManyToOne(targetEntity="Departement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idDep", referencedColumnName="idDepartement")
     * })
     */
    private $iddep;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idProprio", referencedColumnName="idUtilisateur")
     * })
     */
    private $idproprio;

    public function getIdequide(): ?int
    {
        return $this->idequide;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDatenaiss(): ?\DateTime
    {
        return $this->datenaiss;
    }

    public function setDatenaiss(?\DateTime $datenaiss): self
    {
        $this->datenaiss = $datenaiss;

        return $this;
    }

    public function getRobe(): ?Robe
    {
        return $this->robe;
    }

    public function setRobe(?Robe $robe): self
    {
        $this->robe = $robe;

        return $this;
    }

    public function getRace(): ?Race
    {
        return $this->race;
    }

    public function setRace(?Race $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function setTaille(?int $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getLienhn(): ?string
    {
        return $this->lienhn;
    }

    public function setLienhn(?string $lienhn): self
    {
        $this->lienhn = $lienhn;

        return $this;
    }

    public function getIdtypeeq(): ?Typeequide
    {
        return $this->idtypeeq;
    }

    public function setIdtypeeq(?Typeequide $idtypeeq): self
    {
        $this->idtypeeq = $idtypeeq;

        return $this;
    }

    public function getIddep(): ?Departement
    {
        return $this->iddep;
    }

    public function setIddep(?Departement $iddep): self
    {
        $this->iddep = $iddep;

        return $this;
    }

    public function getIdproprio(): ?Utilisateur
    {
        return $this->idproprio;
    }

    public function setIdproprio(?Utilisateur $idproprio): self
    {
        $this->idproprio = $idproprio;

        return $this;
    }

}
