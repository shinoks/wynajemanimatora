<?php

namespace App\Entity;

use App\Repository\RegistryCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegistryCategoryRepository::class)
 */
class RegistryCategory
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
     * @ORM\Column(type="boolean")
     */
    private bool $enabled;

    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="Registry", mappedBy="registryCategories")
     */
    private ArrayCollection $registry;

    public function __construct()
    {
        $this->registry = new ArrayCollection();
    }

    public function __toString():string
    {
        return $this->getName();
    }

    public function getId():int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isEnabled():bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getRegistry(): ?ArrayCollection
    {
        return $this->registry;
    }

    public function setRegistry(ArrayCollection $registry): void
    {
        $this->registry = $registry;
    }

    public function getRegistryCount(): int
    {
        return count($this->registry);
    }
}
