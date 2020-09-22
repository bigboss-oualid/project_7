<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *      "GET"={
 *          "access_control" = "is_granted('IS_AUTHENTICATED_FULLY')",
 *          "normalization_context" = {
 *              "groups"={"users_read"}
 *          }
 *      },
 *      "POST"={
 *          "access_control" = "is_granted('IS_AUTHENTICATED_FULLY')",
 *          "denormalization_context" = {
 *              "groups"={"user_post"}
 *          },
 *          "normalization_context" = {
 *              "groups"={"users_read"}
 *          }
 *      }
 *     },
 *     itemOperations={
 *      "GET"={
 *          "access_control" = "is_granted('IS_AUTHENTICATED_FULLY')",
 *          "normalization_context" = {
 *              "groups"={"users_read"}
 *          }
 *      },
 *      "DELETE"={
 *          "access_control" = "is_granted('IS_AUTHENTICATED_FULLY') and object.getCustomer() === user",
 *          "requirements"={"id"="\d+"}
 *      }
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"firstName":"start", "lastName":"start"})
 * @ApiFilter(OrderFilter::class, properties={"createdAt"})
 */
class User extends Person
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users_read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"users_read_admin"})
     */
    private $customer;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
