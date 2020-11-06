<?php

namespace App\Entity;

use App\Repository\RegistryRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegistryRepository::class)
 */
class Registry
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private string $phone;

    /**
     * @ORM\Column(type="string", length=100, nullable=true, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private string $city;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private string $zipCode;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdDate;

    /**
     * @ORM\Column(type="string", length=150, unique=true, )
     */
    private string $hash;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $enabled;

    /**
     * @ORM\ManyToMany(targetEntity="RegistryCategory", inversedBy="registry")
     * @@ORM\JoinTable(name="registry_categories")
     */
    private ArrayCollection $registryCategories;

    public function __construct()
    {
        $this->createdDate = new DateTime();
        $this->enabled = 0;
        $this->hash = uniqid(md5($this->getName()).'.');
        $this->registryCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode($zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCreatedDate(): DateTime
    {
        return $this->createdDate;
    }

    public function setCreatedDate(DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function isEnabled():bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getRegistryCategories(): ArrayCollection
    {
        return $this->registryCategories;
    }

    public function setRegistryCategories(ArrayCollection $registryCategories): void
    {
        $this->registryCategories = $registryCategories;
    }
}
