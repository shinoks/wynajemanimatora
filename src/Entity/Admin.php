<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Null_;
use phpDocumentor\Reflection\Types\Nullable;
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @ORM\Table(name="admins")
 * @ORM\Entity(repositoryClass="App\Repository\AdminRepository")
 */
class Admin implements UserInterface, Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private string $lastName;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private bool $isActive;

    public function __construct()
    {
        $this->isActive = true;
    }

    public function __toString():string
    {

        return $this->getFirstname().' '.$this->getLastName();
    }

    public function getId():int
    {
        return $this->id;
    }

    public function getUsername():string
    {
        return $this->username;
    }

    public function setUsername(string $username):void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
    }

    public function getSalt():void
    {
    }

    public function getPassword():string
    {
        return $this->password;
    }

    public function setPassword(string $password):void
    {
        $this->password = $password;
    }

    public function getFirstName():string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName):void
    {
        $this->firstName = $firstName;
    }

    public function getLastName():string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName):void
    {
        $this->lastName = $lastName;
    }

    public function getRoles():array
    {
        return $this->roles;
    }

    public function setRoles(array $roles):void
    {
        $this->roles = $roles;
    }

    public function eraseCredentials():void
    {
    }
    public function isAccountNonExpired(): bool
    {
        return true;
    }

    public function isAccountNonLocked(): bool
    {
        return true;
    }

    public function isCredentialsNonExpired(): bool
    {
        return true;
    }

    public function isEnabled(): bool
    {
        return $this->isActive;
    }

    public function serialize():string
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->firstName,
            $this->lastName,
            $this->password,
            $this->email,
            $this->isActive
        ));
    }

    public function unserialize($serialized):void
    {
        list (
            $this->id,
            $this->username,
            $this->firstName,
            $this->lastName,
            $this->password,
            $this->email,
            $this->isActive
            ) = unserialize($serialized);
    }
}
