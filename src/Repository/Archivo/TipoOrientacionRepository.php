<?php

namespace App\Repository\Archivo;

use App\Entity\Archivo\TipoOrientacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoOrientacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoOrientacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoOrientacion[]    findAll()
 * @method TipoOrientacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoOrientacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoOrientacion::class);
    }

    // /**
    //  * @return TipoOrientacion[] Returns an array of TipoOrientacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TipoOrientacion
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
