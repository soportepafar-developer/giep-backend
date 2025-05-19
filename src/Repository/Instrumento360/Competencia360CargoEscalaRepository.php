<?php

namespace App\Repository\Instrumento360;

use App\Entity\Encuesta\CategoriaCargoEscala;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoriaCargoEscala|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaCargoEscala|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaCargoEscala[]    findAll()
 * @method CategoriaCargoEscala[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Competencia360CargoEscalaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaCargoEscala::class);
    }
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
