<?php

namespace App\Repository;

use App\Entity\Header;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Header|null find($id, $lockMode = null, $lockVersion = null)
 * @method Header|null findOneBy(array $criteria, array $orderBy = null)
 * @method Header[]    findAll()
 * @method Header[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeaderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Header::class);
    }

    // /**
    //  * @return Header[] Returns an array of Header objects
    //  */
    public function findThreeLastHeaders()
    {
        return $this->createQueryBuilder('h')
            ->orderBy('h.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Header
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
