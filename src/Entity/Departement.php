<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Departement
 *
 * @ORM\Table(name="departement", indexes={@ORM\Index(name="FK_RegionDep", columns={"idRegionDep"})})
 * @ORM\Entity(repositoryClass="App\Repository\DepartementRepository")
 */
class Departement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idDepartement", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddepartement;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libelle", type="string", length=30, nullable=true, options={"default"="NULL"})
     */
    private $libelle = 'NULL';

    /**
     * @var \Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idRegionDep", referencedColumnName="idRegion")
     * })
     */
    private $idregiondep;

    public function getIddepartement(): ?int
    {
        return $this->iddepartement;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getIdregiondep(): ?Region
    {
        return $this->idregiondep;
    }

    public function setIdregiondep(?Region $idregiondep): self
    {
        $this->idregiondep = $idregiondep;

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }
}
