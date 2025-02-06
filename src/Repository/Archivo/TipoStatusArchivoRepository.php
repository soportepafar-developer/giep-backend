<?php

namespace App\Repository\Archivo;

use App\Entity\Archivo\TipoStatusArchivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoStatusArchivo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoStatusArchivo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoStatusArchivo[]    findAll()
 * @method TipoStatusArchivo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoStatusArchivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoStatusArchivo::class);
    }

    // /**
    //  * @return TipoStatusArchivo[] Returns an array of TipoStatusArchivo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TipoStatusArchivo
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
