<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\HistArchivoBloqueadod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HistArchivoBloqueadod|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistArchivoBloqueadod|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistArchivoBloqueadod[]    findAll()
 * @method HistArchivoBloqueadod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistArchivoBloqueadodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistArchivoBloqueadod::class);
    }

    // /**
    //  * @return HistArchivoBloqueadod[] Returns an array of HistArchivoBloqueado objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistArchivoBloqueadod
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
