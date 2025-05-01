<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\Instrumento360Evaluaciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Instrumento360Evaluaciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Instrumento360Evaluaciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Instrumento360Evaluaciones[]    findAll()
 * @method Instrumento360Evaluaciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Instrumento360EvaluacionesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instrumento360Evaluaciones::class);
    }

    // /**
    //  * @return Instrumento360Evaluaciones[] Returns an array of Instrumento360Evaluaciones objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Instrumento360Evaluaciones
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
