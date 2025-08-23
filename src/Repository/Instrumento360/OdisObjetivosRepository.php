<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\OdisObjetivos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OdisObjetivos|null find($id, $lockMode = null, $lockVersion = null)
 * @method OdisObjetivos|null findOneBy(array $criteria, array $orderBy = null)
 * @method OdisObjetivos[]    findAll()
 * @method OdisObjetivos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OdisObjetivosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OdisObjetivos::class);
    }

    // /**
    //  * @return OdisObjetivos[] Returns an array of OdisObjetivos objects
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
    public function findOneBySomeField($value): ?OdisObjetivos
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
