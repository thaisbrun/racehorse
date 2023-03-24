<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Favoris
 *
 * @ORM\Table(name="favoris")
 * @ORM\Entity(repositoryClass="App\Repository\MyClassRepository")
 */
class Favoris
{
    /**
     * @var int
     *
     * @ORM\Column(name="idUtilisateurFav", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idutilisateurfav;

    /**
     * @var int
     *
     * @ORM\Column(name="idAnnonceFav", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idannoncefav;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateCreation", type="date", nullable=true, options={"default"="NULL"})
     */
    private $datecreation = 'NULL';

    public function getIdutilisateurfav(): ?int
    {
        return $this->idutilisateurfav;
    }

    public function getIdannoncefav(): ?int
    {
        return $this->idannoncefav;
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


}
