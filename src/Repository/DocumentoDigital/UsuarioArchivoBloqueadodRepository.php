<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\UsuarioArchivoBloqueadod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsuarioArchivoBloqueadod|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsuarioArchivoBloqueadod|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsuarioArchivoBloqueadod[]    findAll()
 * @method UsuarioArchivoBloqueadod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioArchivoBloqueadodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsuarioArchivoBloqueadod::class);
    }

    // /**
    //  * @return UsuarioArchivoBloqueadod[] Returns an array of UsuarioArchivoBloqueado objects
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
    public function findOneBySomeField($value): ?UsuarioArchivoBloqueadod
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
