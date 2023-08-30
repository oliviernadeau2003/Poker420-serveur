<?php

namespace App\Entity;

use App\Repository\PartieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartieRepository::class)]
class Partie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $idGagnant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $debut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fin = null;

    #[ORM\OneToMany(mappedBy: 'partie', targetEntity: JoueurPartie::class)]
    private Collection $joueurParties;

    public function __construct()
    {
        $this->joueurParties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdGagnant(): ?int
    {
        return $this->idGagnant;
    }

    public function setIdGagnant(?int $idGagnant): static
    {
        $this->idGagnant = $idGagnant;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): static
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(?\DateTimeInterface $fin): static
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * @return Collection<int, JoueurPartie>
     */
    public function getJoueurParties(): Collection
    {
        return $this->joueurParties;
    }

    public function addJoueurParty(JoueurPartie $joueurParty): static
    {
        if (!$this->joueurParties->contains($joueurParty)) {
            $this->joueurParties->add($joueurParty);
            $joueurParty->setPartie($this);
        }

        return $this;
    }

    public function removeJoueurParty(JoueurPartie $joueurParty): static
    {
        if ($this->joueurParties->removeElement($joueurParty)) {
            // set the owning side to null (unless already changed)
            if ($joueurParty->getPartie() === $this) {
                $joueurParty->setPartie(null);
            }
        }

        return $this;
    }
}
