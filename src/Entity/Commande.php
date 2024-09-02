<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    #[ORM\Column(length: 255)]
    private ?string $NomCarrier = null;

    #[ORM\Column]
    private ?float $PrixCarrier = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $delivery = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, DetailsCommande>
     */
    #[ORM\OneToMany(targetEntity: DetailsCommande::class, mappedBy: 'commande')]
    private Collection $produit;

    #[ORM\Column]
    private ?float $totaleCommande = null;

    #[ORM\Column]
    private ?bool $isPaid = null;

    public function __construct()
    {
        $this->produit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getNomCarrier(): ?string
    {
        return $this->NomCarrier;
    }

    public function setNomCarrier(string $NomCarrier): static
    {
        $this->NomCarrier = $NomCarrier;

        return $this;
    }

    public function getPrixCarrier(): ?float
    {
        return $this->PrixCarrier;
    }

    public function setPrixCarrier(float $PrixCarrier): static
    {
        $this->PrixCarrier = $PrixCarrier;

        return $this;
    }

    public function getDelivery(): ?string
    {
        return $this->delivery;
    }

    public function setDelivery(string $delivery): static
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, DetailsCommande>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(DetailsCommande $produit): static
    {
        if (!$this->produit->contains($produit)) {
            $this->produit->add($produit);
            $produit->setCommande($this);
        }

        return $this;
    }

    public function removeProduit(DetailsCommande $produit): static
    {
        if ($this->produit->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCommande() === $this) {
                $produit->setCommande(null);
            }
        }

        return $this;
    }

    public function getTotaleCommande(): ?float
    {
        return $this->totaleCommande;
    }

    public function setTotaleCommande(float $totaleCommande): static
    {
        $this->totaleCommande = $totaleCommande;

        return $this;
    }

    public function isPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setPaid(bool $isPaid): static
    {
        $this->isPaid = $isPaid;

        return $this;
    }
}
