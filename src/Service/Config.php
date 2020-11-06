<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Config as conf;
use App\Entity\Contact;

class Config
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerinterface $em)
    {
        $this->em = $em;
    }

    public function getConfig()
    {
        return $this->em->getRepository(conf::class)->findOneBy(['id'=>'1']);
    }

    public function getNotReadedMessages(): ?array
    {
        return $this->em->getRepository(Contact::class)->CountAllNotReaded();
    }
}
