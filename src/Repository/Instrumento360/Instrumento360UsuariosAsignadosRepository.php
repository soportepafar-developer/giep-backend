<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\Instrumento360UsuariosAsignados;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Instrumento360UsuariosAsignados|null find($id, $lockMode = null, $lockVersion = null)
 * @method Instrumento360UsuariosAsignados|null findOneBy(array $criteria, array $orderBy = null)
 * @method Instrumento360UsuariosAsignados[]    findAll()
 * @method Instrumento360UsuariosAsignados[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Instrumento360UsuariosAsignadosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instrumento360UsuariosAsignados::class);
    }

    // /**
    //  * @return Instrumento360UsuariosAsignados[] Returns an array of Instrumento360UsuariosAsignados objects
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
    public function findOneBySomeField($value): ?Instrumento360UsuariosAsignados
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
