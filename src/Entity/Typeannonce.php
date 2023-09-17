<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Typeannonce
 *
 * @ORM\Table(name="typeannonce")
 * @ORM\Entity(repositoryClass="App\Repository\TypeAnnonceRepository")
 */
class Typeannonce
{
    /**
     * @var int
     *
     * @ORM\Column(name="idTypeAnnonce", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtypeannonce;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libelle", type="string", length=20, nullable=true, options={"default"="NULL"})
     */
    private $libelle = 'NULL';

    public function getIdtypeannonce(): ?int
    {
        return $this->idtypeannonce;
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

    public function __toString(){
        $idString = strval($this->getIdtypeannonce());
        return $idString;
    }

}
