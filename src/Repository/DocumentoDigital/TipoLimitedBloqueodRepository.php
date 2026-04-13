<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\TipoLimitedBloqueod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoLimitedBloqueod|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoLimitedBloqueod|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoLimitedBloqueod[]    findAll()
 * @method TipoLimitedBloqueod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoLimitedBloqueodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoLimitedBloqueod::class);
    }

    // /**
    //  * @return TipoLimitedBloqueod[] Returns an array of TipoLimitedBloqueo objects
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
    public function findOneBySomeField($value): ?TipoLimitedBloqueod
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
