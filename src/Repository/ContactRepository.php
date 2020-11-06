<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function findAllDesc():array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.created', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function CountAllNotReaded():int
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.readed = :value')->setParameter('value', 0)
            ->orderBy('c.created', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
