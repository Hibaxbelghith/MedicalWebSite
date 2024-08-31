<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank( message: 'email est obligatoire',)]
    #[Assert\Email( message: 'Format email non valide',)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length(
        min: 6,
        minMessage: 'Le mdp doit contenir au minimum {{ limit }} caractères',
    )]
    #[Assert\NotBlank( message: 'Le mot de passe est obligatoire',)]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Le prénom doit contenir au minimum {{ limit }} caractères',
        maxMessage: 'Le prénom peut contenir au maximum {{ limit }} caractères',
    )]
    #[Assert\NotBlank( message: 'Le prénom est obligatoire',)]
    #[Assert\Regex(
        pattern: '/\d/',
        message: 'Le prénom ne doit pas contenir des chiffres',
        match: false,
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Le nom doit contenir au minimum {{ limit }} caractères',
        maxMessage: 'Le nom peut contenir au maximum {{ limit }} caractères',
    )]
    #[Assert\NotBlank( message: 'Le nom est obligatoire')]
    #[Assert\Regex(
        pattern: '/\d/',
        message: 'Le nom ne doit pas contenir des chiffres',
        match: false,
    )]
    private ?string $lastName = null;

    #[ORM\Column(length: 100)]
    private ?string $role = 'ROLE_ADMIN';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resetToken;

    /**
     * @var Collection<int, Adresse>
     */
    #[ORM\OneToMany(targetEntity: Adresse::class, mappedBy: 'user')]
    private Collection $adresses;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';


        return array_unique($roles);

    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }



    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): static
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses->add($adress);
            $adress->setUser($this);
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): static
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getUser() === $this) {
                $adress->setUser(null);
            }
        }

        return $this;
    }


}
