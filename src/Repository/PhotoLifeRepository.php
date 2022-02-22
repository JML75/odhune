<?php

namespace App\Repository;

use App\Entity\PhotoLife;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PhotoLife|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhotoLife|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhotoLife[]    findAll()
 * @method PhotoLife[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoLifeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotoLife::class);
    }

    // /**
    //  * @return PhotoLife[] Returns an array of PhotoLife objects
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
    public function findOneBySomeField($value): ?PhotoLife
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
