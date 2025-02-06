<?php

namespace App\Repository\Archivo;

use App\Entity\Archivo\UsuarioArchivoBloqueado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsuarioArchivoBloqueado|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsuarioArchivoBloqueado|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsuarioArchivoBloqueado[]    findAll()
 * @method UsuarioArchivoBloqueado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioArchivoBloqueadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsuarioArchivoBloqueado::class);
    }

    // /**
    //  * @return UsuarioArchivoBloqueado[] Returns an array of UsuarioArchivoBloqueado objects
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
    public function findOneBySomeField($value): ?UsuarioArchivoBloqueado
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
