<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Typeequide
 *
 * @ORM\Table(name="typeequide")
 * @ORM\Entity(repositoryClass="App\Repository\TypeEquideRepository")
 */
class Typeequide
{
    /**
     * @var int
     *
     * @ORM\Column(name="idTypeEquide", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtypeequide;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libelle", type="string", length=20, nullable=true, options={"default"="NULL"})
     */
    private $libelle = 'NULL';

    public function getIdtypeequide(): ?int
    {
        return $this->idtypeequide;
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
    public function __toString()
    {
        return $this->getLibelle();
    }

}
