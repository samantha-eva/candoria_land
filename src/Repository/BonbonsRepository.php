<?php

namespace App\Repository;

use App\Entity\Bonbons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

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

    public function findBySearchTermAndCategoriesAndMarques($searchTerm, $selectedCategories, $selectedMarques, $page = 1, $limit = 8): Paginator
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
    
        $qb->andWhere('b.isPromotion = :isPromotion')
           ->setParameter('isPromotion', false);
    
        // Gestion de la pagination
        $qb->setFirstResult(($page - 1) * $limit) 
           ->setMaxResults($limit);
    
        return new Paginator($qb->getQuery(), true);
    }
    

   public function findBySearchTermAndCategoriesAndMarquesAndPromotion($searchTerm, $selectedCategories, $selectedMarques): array
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

        // Filter where isPromotion is true
    $qb->andWhere('b.isPromotion = :isPromotion')
    ->setParameter('isPromotion', true);

   
       return $qb->getQuery()->getResult();
   }
   

   public function findBonbonById(int $id): ?Bonbons
   {
       return $this->find($id);
   }
   
}
