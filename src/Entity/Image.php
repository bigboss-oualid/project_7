<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *      "GET"={"security" = "is_granted('ROLE_SUPERADMIN')"},
 *      "POST"={"security" = "is_granted('ROLE_SUPERADMIN')"}
 *     },
 *     itemOperations={
 *      "GET"={"security" = "is_granted('ROLE_SUPERADMIN')"},
 *      "PUT"={"security" = "is_granted('ROLE_SUPERADMIN')"},
 *      "DELETE"={"security" = "is_granted('ROLE_SUPERADMIN')"}
 *     }
 * )
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"products_read"})
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"products_read"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
