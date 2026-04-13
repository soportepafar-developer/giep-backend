<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\Regiond;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use App\Dto\DocumentoDigital\RegionOutPutDto;

/**
 * @method Regiond|null find($id, $lockMode = null, $lockVersion = null)
 * @method Regiond|null findOneBy(array $criteria, array $orderBy = null)
 * @method Regiond[]    findAll()
 * @method Regiond[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegiondRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Regiond::class);
    }

     /**
     * Listar Tipos Respuestas.
     */
    public function findList($em)
    {
        $dataArea=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('region')
        ->from(Regiond::class,'region')
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new RegionOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->descregion=$valor->getDescregion();
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
