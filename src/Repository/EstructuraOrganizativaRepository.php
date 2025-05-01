<?php

namespace App\Repository;

use App\Entity\EstructuraOrganizativa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;

/**
 * @method EstructuraOrganizativa|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstructuraOrganizativa|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstructuraOrganizativa[]    findAll()
 * @method EstructuraOrganizativa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstructuraOrganizativaRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, EstructuraOrganizativa::class);
    }

        
    // /**
    //  * @return EstructuraOrganizativa[] Returns an array of EstructuraOrganizativa objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EstructuraOrganizativa
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
