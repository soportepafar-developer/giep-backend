<?php

namespace App\Repository\Archivo;

use App\Entity\Archivo\TamanoArchivoPermitido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TamanoArchivoPermitido|null find($id, $lockMode = null, $lockVersion = null)
 * @method TamanoArchivoPermitido|null findOneBy(array $criteria, array $orderBy = null)
 * @method TamanoArchivoPermitido[]    findAll()
 * @method TamanoArchivoPermitido[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TamanoArchivoPermitidoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TamanoArchivoPermitido::class);
    }

    // /**
    //  * @return TamanoArchivoPermitido[] Returns an array of TamanoArchivoPermitido objects
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
    public function findOneBySomeField($value): ?TamanoArchivoPermitido
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
