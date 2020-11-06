<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfigRepository")
 */
class Config
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $phone;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $address;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $logoMain;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $logoAdmin;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $regulationsUrl;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $privacyPolicyUrl;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private string $bankAccount;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private string $keywords;

    /**
     * @ORM\Column(type="text")
     */
    private string $footer;

    public function getId():int
    {
        return $this->id;
    }

    public function setId(int $id):void
    {
        $this->id = $id;
    }

    public function getTitle():string
    {
        return $this->title;
    }

    public function setTitle(string $title):void
    {
        $this->title = $title;
    }

    public function getEmail():string
    {
        return $this->email;
    }

    public function setEmail(string $email):void
    {
        $this->email = $email;
    }

    public function getPhone():string
    {
        return $this->phone;
    }

    public function setPhone(string $phone):void
    {
        $this->phone = $phone;
    }

    public function getAddress():string
    {
        return $this->address;
    }

    public function setAddress(string $address):void
    {
        $this->address = $address;
    }

    public function getRegulationsUrl():string
    {
        return $this->regulationsUrl;
    }

    public function setRegulationsUrl(string $regulationsUrl):void
    {
        $this->regulationsUrl = $regulationsUrl;
    }

    public function getPrivacyPolicyUrl():string
    {
        return $this->privacyPolicyUrl;
    }

    public function setPrivacyPolicyUrl(string $privacyPolicyUrl): void
    {
        $this->privacyPolicyUrl = $privacyPolicyUrl;
    }

    public function getDescription():string
    {
        return $this->description;
    }

    public function setDescription(string $description):void
    {
        $this->description = $description;
    }

    public function getBankAccount():string
    {
        return $this->bankAccount;
    }

    public function setBankAccount(string $bankAccount): void
    {
        $this->bankAccount = $bankAccount;
    }

    public function getLogoMain():string
    {
        return $this->logoMain;
    }

    public function setLogoMain(string $logoMain):void
    {
        $this->logoMain = $logoMain;
    }

    public function getLogoAdmin():string
    {
        return $this->logoAdmin;
    }

    public function setLogoAdmin(string $logoAdmin):void
    {
        $this->logoAdmin = $logoAdmin;
    }

    public function getKeywords():string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords):void
    {
        $this->keywords = $keywords;
    }

    public function getFooter():string
    {
        return $this->footer;
    }

    public function setFooter(string $footer): void
    {
        $this->footer = $footer;
    }
}
