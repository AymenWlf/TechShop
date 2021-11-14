<?php

namespace App\Repository;

use App\Entity\Confirmation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Confirmation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Confirmation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Confirmation[]    findAll()
 * @method Confirmation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfirmationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Confirmation::class);
    }

    // /**
    //  * @return Confirmation[] Returns an array of Confirmation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Confirmation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
