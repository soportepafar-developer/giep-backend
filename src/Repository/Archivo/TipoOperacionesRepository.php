<?php

namespace App\Repository\Archivo;

use App\Entity\Archivo\TipoOperaciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoOperaciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoOperaciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoOperaciones[]    findAll()
 * @method TipoOperaciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoOperacionesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoOperaciones::class);
    }

    // /**
    //  * @return TipoOperaciones[] Returns an array of TipoOperaciones objects
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
    public function findOneBySomeField($value): ?TipoOperaciones
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
