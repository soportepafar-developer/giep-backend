<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\ItemAdjunto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemAdjunto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemAdjunto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemAdjunto[]    findAll()
 * @method ItemAdjunto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemAdjuntoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemAdjunto::class);
    }

    // /**
    //  * @return ItemAdjunto[] Returns an array of ItemAdjunto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemAdjunto
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
