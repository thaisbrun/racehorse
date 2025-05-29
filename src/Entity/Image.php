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
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lienImage", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $lienimage = 'NULL';

    /**
     * @var \Annonce
     *
     * @ORM\ManyToOne(targetEntity="Annonce", inversedBy="images")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idAnnonceImage", referencedColumnName="idAnnonce")
     * })
     */
    private $annonceimage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLienImage(): ?string
    {
        return $this->lienimage;
    }

    public function setLienImage(?string $lienimage): self
    {
        $this->lienimage = $lienimage;

        return $this;
    }

    public function getAnnonceImage(): \Annonce
    {
        return $this->annonceimage;
    }

    public function setAnnonceImage(?Annonce $annonceimage): self
    {
        $this->annonceimage = $annonceimage;

        return $this;
    }


}
