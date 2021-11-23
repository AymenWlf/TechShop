<?php

namespace App\Repository;

use App\Entity\ModifPassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModifPassword|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModifPassword|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModifPassword[]    findAll()
 * @method ModifPassword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModifPasswordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModifPassword::class);
    }

    // /**
    //  * @return ModifPassword[] Returns an array of ModifPassword objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ModifPassword
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
