<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\TipoOrientaciond;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoOrientaciond|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoOrientaciond|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoOrientaciond[]    findAll()
 * @method TipoOrientaciond[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoOrientaciondRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoOrientaciond::class);
    }

    // /**
    //  * @return TipoOrientaciond[] Returns an array of TipoOrientacion objects
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
    public function findOneBySomeField($value): ?TipoOrientaciond
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
