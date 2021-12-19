<?php

namespace App\Repository;

use App\Class\Search;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
    * @return Product[] Returns an array of Product objects
    */
    public function FindWithSearch(Search $search,int $page,int $length)
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c','p')
            ->join('p.category','c');
            
            if (!empty($search->categories)) {
                $query = $query
                    ->andWhere('c.id IN (:categories)')
                    ->setParameter('categories',$search->categories);

            }

            if (!empty($search->string)) {
                $query = $query
                    ->andWhere('p.name LIKE :string')
                    ->setParameter('string',"%{$search->string}%");

            }
            return $query
            ->orderBy('p.id','DESC')
            ->setFirstResult(($page - 1) * $length)
            ->setMaxResults($length)
            ->getQuery()->getResult();
    }

    /**
    * @return Product[] Returns an array of Product objects
    */
    public function FindAllWithSearch(Search $search)
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c','p')
            ->join('p.category','c');
            
            if (!empty($search->categories)) {
                $query = $query
                    ->andWhere('c.id IN (:categories)')
                    ->setParameter('categories',$search->categories);

            }

            if (!empty($search->string)) {
                $query = $query
                    ->andWhere('p.name LIKE :string')
                    ->setParameter('string',"%{$search->string}%");

            }
            return $query->getQuery()->getResult();            
    }

    /**
    * @return Product[] Returns an array of Product objects
    */
    public function getPaginatedProducts(int $page,int $length)
    {
        $query = $this->createQueryBuilder('p')
        ->orderBy('p.id','DESC')
        ->setFirstResult(($page - 1) * $length)
        ->setMaxResults($length);

        return $query->getQuery()->getResult();
    }
    
    public function countProductsById()
    {
        return $this->createQueryBuilder('p')
        ->select("COUNT(p.id)")
        ->getQuery()
        ->getSingleScalarResult();
    }

    /*
    public function findOneBySomeField($value): ?Product
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
