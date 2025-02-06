<?php

namespace App\Repository\Encuesta;

use App\Entity\Encuesta\CategoriaNivelPonderacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoriaNivelPonderacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaNivelPonderacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaNivelPonderacion[]    findAll()
 * @method CategoriaNivelPonderacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaNivelPonderacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaNivelPonderacion::class);
    }

    // /**
    //  * @return CategoriaNivelPonderacion[] Returns an array of CategoriaNivelPonderacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoriaNivelPonderacion
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
