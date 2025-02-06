<?php

namespace App\Repository\Archivo;

use App\Entity\Archivo\TipoLimitedBloqueo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoLimitedBloqueo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoLimitedBloqueo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoLimitedBloqueo[]    findAll()
 * @method TipoLimitedBloqueo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoLimitedBloqueoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoLimitedBloqueo::class);
    }

    // /**
    //  * @return TipoLimitedBloqueo[] Returns an array of TipoLimitedBloqueo objects
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
    public function findOneBySomeField($value): ?TipoLimitedBloqueo
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
