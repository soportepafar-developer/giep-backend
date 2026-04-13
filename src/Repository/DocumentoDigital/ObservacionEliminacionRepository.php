<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\ObservacionEliminacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ObservacionEliminacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObservacionEliminacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObservacionEliminacion[]    findAll()
 * @method ObservacionEliminacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObservacionEliminacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObservacionEliminacion::class);
    }

    // /**
    //  * @return ObservacionEliminacion[] Returns an array of ObservacionEliminacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ObservacionEliminacion
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
