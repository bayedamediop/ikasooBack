<?php

namespace App\Repository;

use App\Entity\AdminHotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdminHotel|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminHotel|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminHotel[]    findAll()
 * @method AdminHotel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminHotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminHotel::class);
    }

    // /**
    //  * @return AdminHotel[] Returns an array of AdminHotel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdminHotel
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
