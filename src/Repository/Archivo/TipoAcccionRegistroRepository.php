<?php

namespace App\Repository\Archivo;

use App\Entity\Archivo\TipoAcccionRegistro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoAcccionRegistro|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoAcccionRegistro|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoAcccionRegistro[]    findAll()
 * @method TipoAcccionRegistro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoAcccionRegistroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoAcccionRegistro::class);
    }

    // /**
    //  * @return TipoAcccionRegistro[] Returns an array of TipoAcccionRegistro objects
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
    public function findOneBySomeField($value): ?TipoAcccionRegistro
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
