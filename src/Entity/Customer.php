<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @UniqueEntity("username", message="A user already exists with this username")
 * @ApiResource(
 *     collectionOperations={
 *      "GET"={"security" = "is_granted('ROLE_SUPERADMIN')"},
 *      "POST"={"security" = "is_granted('ROLE_SUPERADMIN')"}
 *     },
 *     itemOperations={
 *      "GET"={"security" = "is_granted('ROLE_SUPERADMIN')"},
 *      "PUT"={"security" = "is_granted('ROLE_SUPERADMIN')"},
 *      "DELETE"={
 *          "security"="is_granted('ROLE_SUPERADMIN') and object !== user",
 *          "requirements"={"id"="\d+"}
 *      }
 *     }
 * )
 */
class Customer extends Person implements UserInterface
{
    const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';
    const ROLE_USER = 'ROLE_USER';

    const DEFAULT_ROLES = [self::ROLE_USER];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users_read", "user_post"})
     */
    private $username;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="The field is required!")
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="Password must be at least 7 characters long and contain at least one digit, one specific character one upper & lower case letter"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="customer", cascade={"persist"}, orphanRemoval=true)
     */
    private $users;

    public function __construct()
    {
        parent::__construct();
        $this->users = new ArrayCollection();
        $this->roles = self::DEFAULT_ROLES;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string The encoded password if any
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param mixed $password
     *
     *@return Customer
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCustomer($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCustomer() === $this) {
                $user->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?String
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }
}
