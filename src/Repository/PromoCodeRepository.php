<?php

namespace App\Repository;

use App\Entity\PromoCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PromoCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromoCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromoCode[]    findAll()
 * @method PromoCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromoCode::class);
    }

    // /**
    //  * @return PromoCode[] Returns an array of PromoCode objects
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
    public function findOneBySomeField($value): ?PromoCode
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
