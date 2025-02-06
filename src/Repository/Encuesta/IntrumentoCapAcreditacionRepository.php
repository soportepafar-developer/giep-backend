<?php

namespace App\Repository\Encuesta;

use App\Entity\Encuesta\IntrumentoCapAcreditacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IntrumentoCapAcreditacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method IntrumentoCapAcreditacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method IntrumentoCapAcreditacion[]    findAll()
 * @method IntrumentoCapAcreditacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntrumentoCapAcreditacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IntrumentoCapAcreditacion::class);
    }

    // /**
    //  * @return IntrumentoCapAcreditacion[] Returns an array of IntrumentoCapAcreditacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IntrumentoCapAcreditacion
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
