<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\ProyectoAdjunto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProyectoAdjunto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProyectoAdjunto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProyectoAdjunto[]    findAll()
 * @method ProyectoAdjunto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProyectoAdjuntoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProyectoAdjunto::class);
    }

    // /**
    //  * @return ProyectoAdjunto[] Returns an array of ProyectoAdjunto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProyectoAdjunto
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
