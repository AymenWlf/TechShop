<?php

namespace App\Repository;

use App\Entity\VariationOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VariationOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method VariationOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method VariationOption[]    findAll()
 * @method VariationOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariationOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VariationOption::class);
    }

    // /**
    //  * @return VariationOption[] Returns an array of VariationOption objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VariationOption
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
