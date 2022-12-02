<?php

namespace App\Entity;

use App\Repository\VoteFinaleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoteFinaleRepository::class)]
class VoteFinale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'voteFinales')]
    private ?Fainaliste $finaliste = null;

    #[ORM\ManyToOne(inversedBy: 'voteFinales')]
    private ?Finale $finale = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(?int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFinaliste(): ?Fainaliste
    {
        return $this->finaliste;
    }

    public function setFinaliste(?Fainaliste $finaliste): self
    {
        $this->finaliste = $finaliste;

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
}
