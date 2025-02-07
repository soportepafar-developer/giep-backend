<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\TipoOperacionesd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoOperacionesd|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoOperacionesd|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoOperacionesd[]    findAll()
 * @method TipoOperacionesd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoOperacionesdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoOperacionesd::class);
    }

    // /**
    //  * @return TipoOperacionesd[] Returns an array of TipoOperaciones objects
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
    public function findOneBySomeField($value): ?TipoOperacionesd
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
