<?php

namespace App\Repository\CalendarioPluggin;

use App\Entity\CalendarioPluggin\CalendarEventCorreoNotif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CalendarEventCorreoNotif|null find($id, $lockMode = null, $lockVersion = null)
 * @method CalendarEventCorreoNotif|null findOneBy(array $criteria, array $orderBy = null)
 * @method CalendarEventCorreoNotif[]    findAll()
 * @method CalendarEventCorreoNotif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarEventCorreoNotifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CalendarEventCorreoNotif::class);
    }

    // /**
    //  * @return CalendarEventCorreoNotif[] Returns an array of CalendarEventCorreoNotif objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CalendarEventCorreoNotif
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
