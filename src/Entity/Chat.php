<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 */
class Chat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Message", mappedBy="idChat")
     */
    private Collection $messages;
    /**
     * @var \DateTime

     * @ORM\Column(name="dateCreation", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */

    private $datecreation;
    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idFirstUser", referencedColumnName="idUtilisateur",onDelete="CASCADE")
     * })
     */
    private $firstUser;

    /**
     * @return \Utilisateur
     */
    public function getFirstUser(): \Utilisateur
    {
        return $this->firstUser;
    }

    /**
     * @param \Utilisateur $firstUser
     */
    public function setFirstUser(\Utilisateur $firstUser): void
    {
        $this->firstUser = $firstUser;
    }
    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idSecondUser", referencedColumnName="idUtilisateur",onDelete="CASCADE")
     * })
     */
    private $secondUser;

    /**
     * @return \Utilisateur
     */
    public function getSecondUser(): \Utilisateur
    {
        return $this->secondUser;
    }

    /**
     * @param \Utilisateur $secondUser
     */
    public function setSecondUser(\Utilisateur $secondUser): void
    {
        $this->secondUser = $secondUser;
    }
    public function __construct(){
        $this->messages = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
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
    /**
     * @return Collection
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    /**
     * @param Collection $messages
     */
    public function setMessages(Collection $messages): void
    {
        $this->messages = $messages;
    }

}
