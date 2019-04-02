<?php

namespace App\Repository;

use App\Entity\Property;
use App\Entity\PropertySearch;
//use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
        * @param PropertySearch $search
        * @return Query
        */
    public function findAllVisibleQuery(PropertySearch $search): Query
       {
           $query = $this->findVisibleQuery();

           if ($search->getMaxPrice()){
               $query = $query
                   ->andWhere('p.price <= :maxprice')
                   ->setParameter('maxprice', $search->getMaxPrice());
           }

           if ($search->getMinSurface()){
               $query = $query
                   ->andWhere('p.surface >= :minsurface')
                   ->setParameter('minsurface', $search->getMinSurface());
           }

           if ($search->getTags()->count() > 0) {
               $k = 0;
               foreach ($search->getTags() as $k => $tag){
                   $k++;
                   $query = $query
                       ->andWhere(":tag$k MEMBER OF p.tags")
                       ->setParameter("tag$k", $tag)
                   ;
               }
           }

           return $query->getQuery();
       }

    /**
    * @param PropertySearch $search
     * @return Property[]
     */
    public function findLatest(PropertySearch $search): array
    {
      $k = 0;
      $k = $search->getMaxPrice();


        return $this->findVisibleQuery($k)
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.sold = false');
            ;
    }

    /*
    private function findVisibleQuery($k): QueryBuilder
    {
        return $this->createQueryBuilder('p')

            ->Where('p.price <= :maxprice')
            ->setParameter('maxprice',$k )
            ;
    }
    */
    //
    /*
    public function findOneBySomeField($value): ?Property
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
