<?php

namespace App\Entity;

use App\Repository\AnimatorRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=AnimatorRepository::class)
 */
class Animator implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $emailVerified;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isEnabled;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private string $hash;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdDate;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\OneToMany(targetEntity=UserApproval::class, mappedBy="animator", orphanRemoval=true)
     */
    private ArrayCollection $userApprovals;

    public function __construct()
    {
        $this->createdDate = new DateTime();
        $this->isEnabled = 0;
        $this->userApprovals = new ArrayCollection();
    }

    public function __toString():string
    {
        return $this->getEmail();
    }

    public function getId():int
    {
        return $this->id;
    }

    public function getEmail():string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }


    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isEmailVerified():bool
    {
        return $this->emailVerified;
    }

    public function setEmailVerified(bool $emailVerified): void
    {
        $this->emailVerified = $emailVerified;
    }

    public function getIsEnabled(): int
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(int $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }

    public function getHash():string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function getCreatedDate(): DateTime
    {
        return $this->createdDate;
    }

    public function setCreatedDate(DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }

    public function getUserApprovals(): ArrayCollection
    {
        return $this->userApprovals;
    }

    public function addUserApproval(UserApproval $userApproval): bool
    {
        if (!$this->userApprovals->contains($userApproval)) {
            $this->userApprovals[] = $userApproval;
            $userApproval->setUser($this);

            return true;
        }else{

            return false;
        }
    }

    public function removeUserApproval(UserApproval $userApproval): bool
    {
        if ($this->userApprovals->contains($userApproval)) {
            $this->userApprovals->removeElement($userApproval);
            if ($userApproval->getUser() === $this) {
                $userApproval->setUser(null);

                return true;
            }else{

                return false;
            }
        }else{
            return false;
        }
    }
}
