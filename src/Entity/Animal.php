<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnimalRepository::class)
 */
class Animal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $ddn;

    /**
     * @ORM\Column(type="date")
     */
    private $arrivee;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $depart;

    /**
     * @ORM\Column(type="boolean")
     */
    private $proprietaire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $espece;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sterelise;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $quarantaine;

    /**
     * @ORM\ManyToOne(targetEntity=Enclos::class, inversedBy="animaux")
     */
    private $enclos;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
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

    public function getDdn(): ?\DateTimeInterface
    {
        return $this->ddn;
    }

    public function setDdn(?\DateTimeInterface $ddn): self
    {
        $this->ddn = $ddn;

        return $this;
    }

    public function getArrivee(): ?\DateTimeInterface
    {
        return $this->arrivee;
    }

    public function setArrivee(\DateTimeInterface $arrivee): self
    {
        $this->arrivee = $arrivee;

        return $this;
    }

    public function getDepart(): ?\DateTimeInterface
    {
        return $this->depart;
    }

    public function setDepart(?\DateTimeInterface $depart): self
    {
        $this->depart = $depart;

        return $this;
    }

    public function isProprietaire(): ?bool
    {
        return $this->proprietaire;
    }

    public function setProprietaire(bool $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function isGenre(): ?bool
    {
        return $this->genre;
    }

    public function setGenre(bool $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEspece(): ?string
    {
        return $this->espece;
    }

    public function setEspece(string $espece): self
    {
        $this->espece = $espece;

        return $this;
    }

    public function isSterelise(): ?bool
    {
        return $this->sterelise;
    }

    public function setSterelise(?bool $sterelise): self
    {
        $this->sterelise = $sterelise;

        return $this;
    }

    public function isQuarantaine(): ?bool
    {
        return $this->quarantaine;
    }

    public function setQuarantaine(?bool $quarantaine): self
    {
        $this->quarantaine = $quarantaine;

        return $this;
    }

    public function getEnclos(): ?Enclos
    {
        return $this->Enclos;
    }

    public function setEnclos(?Enclos $Enclos): self
    {
        $this->Enclos = $Enclos;

        return $this;
    }
}
