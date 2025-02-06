<?php

namespace App\Repository\Archivo;

use App\Entity\HistArchivoBloqueado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HistArchivoBloqueado|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistArchivoBloqueado|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistArchivoBloqueado[]    findAll()
 * @method HistArchivoBloqueado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistArchivoBloqueadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistArchivoBloqueado::class);
    }

    // /**
    //  * @return HistArchivoBloqueado[] Returns an array of HistArchivoBloqueado objects
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
    public function findOneBySomeField($value): ?HistArchivoBloqueado
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
