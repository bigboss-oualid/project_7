<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass
 * @UniqueEntity("email", message="A user already exists with this email address")
 */
class Person
{
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=3, minMessage="First name must contain between 3 and 255 characters!", max=255, maxMessage="First name must contain between 3 and 255 characters")
     * @Assert\NotBlank(message="First name is required!")
     * @Groups({"users_read", "user_post"})
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=3, minMessage="Last name must contain between 3 and 255 characters!", max=255, maxMessage="Last name  must contain between 3 and 255 characters")
     * @Assert\NotBlank(message="Last name is required!")
     * @Groups({"users_read", "user_post"})
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="The email is required!")
     * @Assert\Length(min=5, minMessage="Email must contain between 3 and 255 characters!", max=255, maxMessage="Email must contain between 3 and 255 characters")
     * @Assert\Email(message="The email address must have a valid format!")
     * @Groups({"users_read", "user_post"})
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users_read", "user_post"})
     */
    protected $company;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"users_read"})
     */
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
