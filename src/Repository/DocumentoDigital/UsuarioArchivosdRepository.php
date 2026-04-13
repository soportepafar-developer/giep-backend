<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\UsuarioArchivosd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsuarioArchivosd|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsuarioArchivosd|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsuarioArchivosd[]    findAll()
 * @method UsuarioArchivosd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioArchivosdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsuarioArchivosd::class);
    }

    // /**
    //  * @return UsuarioArchivosd[] Returns an array of UsuarioArchivos objects
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
    public function findOneBySomeField($value): ?UsuarioArchivosd
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
