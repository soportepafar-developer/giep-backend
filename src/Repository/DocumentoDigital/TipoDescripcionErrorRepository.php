<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\TipoDescripcionError;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoDescripcionError|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoDescripcionError|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoDescripcionError[]    findAll()
 * @method TipoDescripcionError[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoDescripcionErrorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoDescripcionError::class);
    }

    // /**
    //  * @return TipoDescripcionError[] Returns an array of TipoDescripcionError objects
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
    public function findOneBySomeField($value): ?TipoDescripcionError
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
