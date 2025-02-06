<?php

namespace App\Repository\CalendarioPluggin;

use App\Entity\CalendarioPluggin\CalendarUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method CalendarUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method CalendarUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method CalendarUser[]    findAll()
 * @method CalendarUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CalendarUser::class);
    }
}
