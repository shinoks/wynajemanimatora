<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     */
    private string $message;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private string $send;

    /**
     * @ORM\Column(type="integer")
     */
    private bool $readed;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Animator")
     * @ORM\JoinColumn(nullable=true)
     */
    private Animator $sender;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $created;

    public function __construct()
    {
        $this->created = new DateTime("now");
        $this->readed = 0;
    }

    public function getId():int
    {
        return $this->id;
    }

    public function getTitle():string
    {
        return $this->title;
    }

    public function setTitle(string $title):void
    {
        $this->title = $title;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message):void
    {
        $this->message = $message;
    }

    public function getSend():string
    {
        return $this->send;
    }

    public function setSend(string $send):void
    {
        $this->send = $send;
    }

    public function getReaded():int
    {
        return $this->readed;
    }

    public function setReaded(string $readed):void
    {
        $this->readed = $readed;
    }

    public function getSender():string
    {
        return $this->sender;
    }

    public function setSender(Animator $sender):void
    {
        $this->sender = $sender;
    }

    public function getCreated():DateTime
    {
        return $this->created;
    }

    public function setCreated(DateTime $created):void
    {
        $this->created = $created;
    }
}
