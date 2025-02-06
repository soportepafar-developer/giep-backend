<?php

namespace App\Repository\Encuesta;

use App\Entity\Encuesta\CategoriaCargoEscala;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoriaCargoEscala|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaCargoEscala|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaCargoEscala[]    findAll()
 * @method CategoriaCargoEscala[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaCargoEscalaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaCargoEscala::class);
    }

    // /**
    //  * @return CategoriaCargoEscala[] Returns an array of CategoriaCargoEscala objects
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
    public function findOneBySomeField($value): ?CategoriaCargoEscala
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
