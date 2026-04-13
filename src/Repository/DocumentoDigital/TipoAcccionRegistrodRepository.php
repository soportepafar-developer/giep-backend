<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\TipoAcccionRegistrod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoAcccionRegistrod|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoAcccionRegistrod|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoAcccionRegistrod[]    findAll()
 * @method TipoAcccionRegistrod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoAcccionRegistrodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoAcccionRegistrod::class);
    }

    // /**
    //  * @return TipoAcccionRegistrod[] Returns an array of TipoAcccionRegistro objects
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
    public function findOneBySomeField($value): ?TipoAcccionRegistrod
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
