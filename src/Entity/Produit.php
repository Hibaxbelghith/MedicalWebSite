<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank( message: 'Le nom est obligatoire')]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;


    #[ORM\Column(length: 255)]
    private ?string $imageProduit = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Assert\NotBlank( message: 'Le prix est obligatoire')]
    #[ORM\Column]
    private ?float $prix = null;

    #[Assert\NotBlank( message: 'Le catégorie est obligatoire')]
    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Category $categorie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImageProduit(): ?string
    {
        return $this->imageProduit;
    }

    public function setImageProduit(string $imageProduit): static
    {
        $this->imageProduit = $imageProduit;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCategorie(): ?Category
    {
        return $this->categorie;
    }

    public function setCategorie(?Category $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
}
