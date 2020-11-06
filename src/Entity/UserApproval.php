<?php

namespace App\Entity;

use App\Repository\UserApprovalRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserApprovalRepository::class)
 */
class UserApproval
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $approval_text;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $dateOfAcceptance;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $dateOfWithdraval;

    /**
     * @ORM\ManyToOne(targetEntity=Approval::class, inversedBy="userApprovals",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Approval $approval;

    /**
     * @ORM\ManyToOne(targetEntity=Animator::class, inversedBy="userApprovals",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Animator $animator;

    public function __construct()
    {
        $this->dateOfAcceptance = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApprovalText(): ?string
    {
        return $this->approval_text;
    }

    public function setApprovalText(string $approval_text):void
    {
        $this->approval_text = $approval_text;
    }

    public function getDateOfAcceptance(): ?DateTimeInterface
    {
        return $this->dateOfAcceptance;
    }

    public function setDateOfAcceptance(DateTimeInterface $dateOfAcceptance):void
    {
        $this->dateOfAcceptance = $dateOfAcceptance;
    }

    public function getDateOfWithdraval(): ?DateTimeInterface
    {
        return $this->dateOfWithdraval;
    }

    public function setDateOfWithdraval(?DateTimeInterface $dateOfWithdraval):void
    {
        $this->dateOfWithdraval = $dateOfWithdraval;

    }

    public function getApproval(): ?Approval
    {
        return $this->approval;
    }

    public function setApproval(?Approval $approval):void
    {
        $this->approval = $approval;
    }

    public function getUser(): ?Animator
    {
        return $this->animator;
    }

    public function setUser(?Animator $animator):void
    {
        $this->animator = $animator;
    }
}
