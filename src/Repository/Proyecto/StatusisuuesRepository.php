<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\Statusisuues;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Dto\Proyecto\StatusisuuesOutPutDto;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Statusisuues|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statusisuues|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statusisuues[]    findAll()
 * @method Statusisuues[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusisuuesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statusisuues::class);
    }


    /**
     * Lista Status.
     */
    public function findStatusList()
    {
        $data= $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $datastatus=array();
        foreach($data as $clave=>$valor){
            $statusDto =new StatusisuuesOutPutDto();
            $statusDto->id=$valor->getId();
            $statusDto->nombre=$valor->getPrioridad();
            $datastatus[]=$statusDto;
        }
       return array("data"=>$datastatus);
 
    } 

    // /**
    //  * @return Statusisuues[] Returns an array of Statusisuues objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Statusisuues
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
