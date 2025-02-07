<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\TamanoArchivoPermitidod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TamanoArchivoPermitidod|null find($id, $lockMode = null, $lockVersion = null)
 * @method TamanoArchivoPermitidod|null findOneBy(array $criteria, array $orderBy = null)
 * @method TamanoArchivoPermitidod[]    findAll()
 * @method TamanoArchivoPermitidod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TamanoArchivoPermitidodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TamanoArchivoPermitidod::class);
    }

    // /**
    //  * @return TamanoArchivoPermitidod[] Returns an array of TamanoArchivoPermitido objects
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
    public function findOneBySomeField($value): ?TamanoArchivoPermitidod
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
