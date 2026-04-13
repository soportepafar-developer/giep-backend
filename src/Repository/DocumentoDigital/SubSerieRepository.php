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


    public function findSubSerieActualizar($em){
            $sqlbusnotf = " SELECT * FROM `serie_sub_serie` ORDER BY id ASC";
            $conn123 =  $em->getConnection();
            $stmtnotf = $conn123->prepare($sqlbusnotf);
            $stmtnotf->execute();
            $result=$stmtnotf->fetchAll();

        $dataTotal=array();
        $colorprogress='';
        $nivelactual=0;
        $cedula=0;
        foreach($result as $claveResult=>$valorResult){
             $serie= $valorResult["serie"];
             $id_series= $valorResult["id_series"];

             $sub_series= $valorResult["sub_serie"];

        if ($serie) { 
            if ($valorResult["sub_serie"]) {
                //echo "El valor existe y no es NULL.";
                $sqlbusserie = " SELECT * FROM `serie` where nombre='$serie'; ";
                $connserie =  $em->getConnection();
                $stmtserie = $connserie->prepare($sqlbusserie);
                $stmtserie->execute();
                $resultserie=$stmtserie->fetchAll();
                $id_serie=$resultserie[0]["id"];

                $sql3 = "update serie_sub_serie set id_serie_f='".$id_serie."' where id_series ='".$id_series."' ";
                $conn3 = $em->getConnection();
                $stmt3 = $conn3->prepare($sql3);
                $stmt3->execute(); 
            }

         }

        }
        return new JsonResponse(['msg'=>'Fin de la actualización satisfactoriamente: '],200);
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
