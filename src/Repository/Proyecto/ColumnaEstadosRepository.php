<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\ColumnaEstados;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ColumnaEstados|null find($id, $lockMode = null, $lockVersion = null)
 * @method ColumnaEstados|null findOneBy(array $criteria, array $orderBy = null)
 * @method ColumnaEstados[]    findAll()
 * @method ColumnaEstados[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColumnaEstadosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ColumnaEstados::class);
    }

    // /**
    //  * @return ColumnaEstados[] Returns an array of ColumnaEstados objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ColumnaEstados
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
