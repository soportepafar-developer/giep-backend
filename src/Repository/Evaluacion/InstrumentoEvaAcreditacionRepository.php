<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\InstrumentoEvaAcreditacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InstrumentoEvaAcreditacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstrumentoEvaAcreditacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstrumentoEvaAcreditacion[]    findAll()
 * @method InstrumentoEvaAcreditacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstrumentoEvaAcreditacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstrumentoEvaAcreditacion::class);
    }

    // /**
    //  * @return InstrumentoEvaAcreditacion[] Returns an array of InstrumentoEvaAcreditacion objects
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
    public function findOneBySomeField($value): ?InstrumentoEvaAcreditacion
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
