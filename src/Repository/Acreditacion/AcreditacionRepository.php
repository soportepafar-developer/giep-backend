<?php

namespace App\Repository\Acreditacion;

use App\Entity\Acreditacion\Acreditacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Acreditacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acreditacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acreditacion[]    findAll()
 * @method Acreditacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcreditacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Acreditacion::class);
    }

    // /**
    //  * @return Acreditacion[] Returns an array of Acreditacion objects
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
    public function findOneBySomeField($value): ?Acreditacion
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
