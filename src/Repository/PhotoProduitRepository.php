<?php

namespace App\Repository;

use App\Entity\PhotoProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PhotoProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhotoProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhotoProduit[]    findAll()
 * @method PhotoProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotoProduit::class);
    }

    // /**
    //  * @return PhotoProduit[] Returns an array of PhotoProduit objects
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
    public function findOneBySomeField($value): ?PhotoProduit
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
