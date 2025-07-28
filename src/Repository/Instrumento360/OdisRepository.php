<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\Odis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Odis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Odis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Odis[]    findAll()
 * @method Odis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OdisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Odis::class);
    }

    // /**
    //  * @return Odis[] Returns an array of Odis objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Odis
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
