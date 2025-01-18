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

    public function findBySearchTerm(string $searchTerm): array
    {
        $qb = $this->createQueryBuilder('b');

        if (!empty($searchTerm)) {
            $qb->where('b.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }
        

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Bonbons[] Returns an array of Bonbons objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Bonbons
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
