<?php

namespace App\Repository\Archivo;

use App\Entity\Archivo\UsuarioArchivos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsuarioArchivos|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsuarioArchivos|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsuarioArchivos[]    findAll()
 * @method UsuarioArchivos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioArchivosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsuarioArchivos::class);
    }

    // /**
    //  * @return UsuarioArchivos[] Returns an array of UsuarioArchivos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsuarioArchivos
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
