<?php

namespace App\Repository;

use App\Entity\PaiementMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaiementMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaiementMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaiementMethod[]    findAll()
 * @method PaiementMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaiementMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaiementMethod::class);
    }

    // /**
    //  * @return PaiementMethod[] Returns an array of PaiementMethod objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaiementMethod
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
