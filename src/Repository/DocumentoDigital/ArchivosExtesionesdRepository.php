<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\ArchivosExtesionesd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArchivosExtesionesd|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArchivosExtesionesd|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArchivosExtesionesd[]    findAll()
 * @method ArchivosExtesionesd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchivosExtesionesdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArchivosExtesionesd::class);
    }

    // /**
    //  * @return ArchivosExtesionesd[] Returns an array of ArchivosExtesiones objects
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
    public function findOneBySomeField($value): ?ArchivosExtesionesd
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
