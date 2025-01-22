<?php

namespace App\Repository;

use App\Entity\Bonbons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bonbons>
 */
class BonbonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bonbons::class);
    }

    public function findAllBonbons(): array
    {
        return $this->findAll();
    }

   public function findBySearchTermAndCategoriesAndMarques($searchTerm, $selectedCategories, $selectedMarques): array
   {

       $qb = $this->createQueryBuilder('b');
       
       if (!empty($searchTerm)) {
           $qb->where('b.nom LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%');
       }


       if (!empty($selectedCategories)) {
           $qb->innerJoin('b.categories', 'c')
               ->andWhere('c.id IN (:categories)')
               ->setParameter('categories', $selectedCategories);
       }

       if (!empty($selectedMarques)) {
            $qb->innerJoin('b.marque', 'm')
                ->andWhere('m.id IN (:marques)')
                ->setParameter('marques', $selectedMarques);
        }
   
       return $qb->getQuery()->getResult();
   }
   

   public function findBonbonById(int $id): ?Bonbons
   {
       return $this->find($id);
   }
   
}
