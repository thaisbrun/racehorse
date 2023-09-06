<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="image", indexes={@ORM\Index(name="FK_ImageAnnonce", columns={"idAnnonceImage"})})
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="idImage", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idimage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lienImage", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $lienimage = 'NULL';

    /**
     * @var \Annonce
     *
     * @ORM\ManyToOne(targetEntity="Annonce")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idAnnonceImage", referencedColumnName="idAnnonce")
     * })
     */
    private $idannonceimage;

    public function getIdimage(): ?int
    {
        return $this->idimage;
    }

    public function getLienimage(): ?string
    {
        return $this->lienimage;
    }

    public function setLienimage(?string $lienimage): self
    {
        $this->lienimage = $lienimage;

        return $this;
    }

    public function getIdannonceimage(): ?Annonce
    {
        return $this->idannonceimage;
    }

    public function setIdannonceimage(?Annonce $idannonceimage): self
    {
        $this->idannonceimage = $idannonceimage;

        return $this;
    }


}
