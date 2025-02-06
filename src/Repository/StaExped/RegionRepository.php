<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Dto\StaExped\RegionOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Region|null find($id, $lockMode = null, $lockVersion = null)
 * @method Region|null findOneBy(array $criteria, array $orderBy = null)
 * @method Region[]    findAll()
 * @method Region[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegionRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Region::class);
    }

     /**
     * Listar Tipos Respuestas.
     */
    public function findList($em)
    {
        $dataArea=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('region')
        ->from(Region::class,'region')
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new RegionOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->region=$valor->getDescregion();
          $dataArea[]=$profesionDto;
      }

      

       return array("data"=>$dataArea);
    }


    // /**
    //  * @return Region[] Returns an array of Region objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Region
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
