<?php

namespace App\Entity;

use App\Repository\FainalisteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FainalisteRepository::class)]
class Fainaliste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $media = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\ManyToOne]
    private ?Commune $commune = null;

    #[ORM\ManyToOne(inversedBy: 'fainalistes')]
    private ?Finale $finale = null;

    #[ORM\OneToMany(mappedBy: 'finaliste', targetEntity: VoteFinale::class)]
    private Collection $voteFinales;

    public function __construct()
    {
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

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): self
    {
        $this->media = $media;

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

    public function getCommune(): ?Commune
    {
        return $this->commune;
    }

    public function setCommune(?Commune $commune): self
    {
        $this->commune = $commune;

        return $this;
    }

    public function getFinale(): ?Finale
    {
        return $this->finale;
    }

    public function setFinale(?Finale $finale): self
    {
        $this->finale = $finale;

        return $this;
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
            $voteFinale->setFinaliste($this);
        }

        return $this;
    }

    public function removeVoteFinale(VoteFinale $voteFinale): self
    {
        if ($this->voteFinales->removeElement($voteFinale)) {
            // set the owning side to null (unless already changed)
            if ($voteFinale->getFinaliste() === $this) {
                $voteFinale->setFinaliste(null);
            }
        }

        return $this;
    }
}
