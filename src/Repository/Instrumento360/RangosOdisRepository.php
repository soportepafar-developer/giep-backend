<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\RangosOdis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RangosOdis|null find($id, $lockMode = null, $lockVersion = null)
 * @method RangosOdis|null findOneBy(array $criteria, array $orderBy = null)
 * @method RangosOdis[]    findAll()
 * @method RangosOdis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RangosOdisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RangosOdis::class);
    }

    // /**
    //  * @return RangosOdis[] Returns an array of RangosOdis objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RangosOdis
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
