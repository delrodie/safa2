<?php

namespace App\Entity;

use App\Repository\FinaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FinaleRepository::class)]
class Finale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $debut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?bool $statut = null;

    #[ORM\OneToMany(mappedBy: 'finale', targetEntity: Fainaliste::class)]
    private Collection $fainalistes;

    #[ORM\OneToMany(mappedBy: 'finale', targetEntity: VoteFinale::class)]
    private Collection $voteFinales;

    public function __construct()
    {
        $this->fainalistes = new ArrayCollection();
        $this->voteFinales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(?\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(?\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, Fainaliste>
     */
    public function getFainalistes(): Collection
    {
        return $this->fainalistes;
    }

    public function addFainaliste(Fainaliste $fainaliste): self
    {
        if (!$this->fainalistes->contains($fainaliste)) {
            $this->fainalistes->add($fainaliste);
            $fainaliste->setFinale($this);
        }

        return $this;
    }

    public function removeFainaliste(Fainaliste $fainaliste): self
    {
        if ($this->fainalistes->removeElement($fainaliste)) {
            // set the owning side to null (unless already changed)
            if ($fainaliste->getFinale() === $this) {
                $fainaliste->setFinale(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

    /**
     * @return Collection<int, VoteFinale>
     */
    public function getVoteFinales(): Collection
    {
        return $this->voteFinales;
    }

    public function addVoteFinale(VoteFinale $voteFinale): self
    {
        if (!$this->voteFinales->contains($voteFinale)) {
            $this->voteFinales->add($voteFinale);
            $voteFinale->setFinale($this);
        }

        return $this;
    }

    public function removeVoteFinale(VoteFinale $voteFinale): self
    {
        if ($this->voteFinales->removeElement($voteFinale)) {
            // set the owning side to null (unless already changed)
            if ($voteFinale->getFinale() === $this) {
                $voteFinale->setFinale(null);
            }
        }

        return $this;
    }
}
