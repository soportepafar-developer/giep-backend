<?php

namespace App\Repository\Archivo;

use App\Entity\Archivo\ArchivosExtesiones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArchivosExtesiones|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArchivosExtesiones|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArchivosExtesiones[]    findAll()
 * @method ArchivosExtesiones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchivosExtesionesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArchivosExtesiones::class);
    }

    // /**
    //  * @return ArchivosExtesiones[] Returns an array of ArchivosExtesiones objects
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
    public function findOneBySomeField($value): ?ArchivosExtesiones
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
