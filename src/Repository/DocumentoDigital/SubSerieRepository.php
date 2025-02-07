<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\SubSerie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use App\Dto\DocumentoDigital\SubSerieOutPutDto;

/**
 * @method SubSerie|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubSerie|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubSerie[]    findAll()
 * @method SubSerie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubSerieRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, SubSerie::class);
    }


/**
     * Listar Tipo Almacen id.
     */
    public function findListid($id,$em)
    {
        $dataSubserie=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('subserie')
        ->from(SubSerie::class,'subserie')
        ->where("subserie.id_serie ='".$id."'")
        ->addOrderBy('subserie.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new SubSerieOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->nombre_subserie=$valor->getNombreSubserie();
          //$profesionDto->iddireccionalmacen=($valor->getIddireccionalmacen()!=null)?array("id"=>$valor->getIddireccionalmacen()->getId(),"Nombre"=>$valor->getIddireccionalmacen()->getNombre(),"Direccion"=>$valor->getIddireccionalmacen()->getDireccionzona()):[]; 

          $dataSubserie[]=$profesionDto;
      }
       return array("data"=>$dataSubserie);
    }

    // /**
    //  * @return SubSerie[] Returns an array of SubSerie objects
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
    public function findOneBySomeField($value): ?SubSerie
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
