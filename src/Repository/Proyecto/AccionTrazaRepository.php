<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\AccionTraza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccionTraza|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccionTraza|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccionTraza[]    findAll()
 * @method AccionTraza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccionTrazaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccionTraza::class);
    }

    // /**
    //  * @return AccionTraza[] Returns an array of AccionTraza objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccionTraza
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
