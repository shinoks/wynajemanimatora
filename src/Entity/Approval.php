<?php

namespace App\Entity;

use App\Repository\ApprovalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApprovalRepository::class)
 */
class Approval
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
     * @ORM\Column(type="text")
     */
    private string $text;

    /**
     * @ORM\OneToMany(targetEntity=UserApproval::class, mappedBy="approval")
     */
    private ArrayCollection $userApprovals;

    public function __construct()
    {
        $this->userApprovals = new ArrayCollection();
    }

    public function getId():int
    {
        return $this->id;
    }

    public function getName():string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getText():string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getUserApprovals(): ArrayCollection
    {
        return $this->userApprovals;
    }

    public function addUserApproval(UserApproval $userApproval): void
    {
        if (!$this->userApprovals->contains($userApproval)) {
            $this->userApprovals[] = $userApproval;
            $userApproval->setApproval($this);
        }
    }

    public function removeUserApproval(UserApproval $userApproval): bool
    {
        if ($this->userApprovals->contains($userApproval)) {
            $this->userApprovals->removeElement($userApproval);
            if ($userApproval->getApproval() === $this) {
                $userApproval->setApproval(null);

                return true;
            }else{

                return false;
            }
        }else{
            return false;
        }
    }
}
