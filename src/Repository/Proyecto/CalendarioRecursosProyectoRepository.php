<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\CalendarioRecursosProyecto;
use App\Entity\Proyecto\RecursosProyecto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
Use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Asset\Packages;
use App\Repository\Proyecto\TrazaRepository;
use App\Entity\Proyecto\Empresa;
/**
 * @method CalendarioRecursosProyecto|null find($id, $lockMode = null, $lockVersion = null)
 * @method CalendarioRecursosProyecto|null findOneBy(array $criteria, array $orderBy = null)
 * @method CalendarioRecursosProyecto[]    findAll()
 * @method CalendarioRecursosProyecto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarioRecursosProyectoRepository extends ServiceEntityRepository
{

    private $security;
    private $traza;
    public function __construct(ManagerRegistry $registry,Security $security,TrazaRepository $traza)
    {
        $this->security = $security;
        $this->traza=$traza;
        parent::__construct($registry, CalendarioRecursosProyecto::class);
    }

     /**
     * Create Calendario Recursos Proyecto.
     */
    public function postCalendarioRecursosProyecto($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
       
        $userId = $data["userId"];
        $projectId =$data['projectId'];
        $queryresp = $entityManager->createQueryBuilder();
        $allAppointmentsQuery = $queryresp->select('recursos_proyecto')
        ->from(RecursosProyecto::class,'recursos_proyecto')
        ->where("recursos_proyecto.idrecurso =$userId")
        ->andWhere("recursos_proyecto.idproyecto=$projectId")
        ->getQuery();
        $queryrespdata = $queryresp->getQuery();
        $dataresp =  $queryrespdata->execute();
        if (count($dataresp)>0) { 
            foreach($dataresp as $clave=>$valor){
                $idRecursosProyecto = $valor->getId();
            }
        }else{
            return new JsonResponse(['msg'=>'No existen Registros de Recursos con el idrecurso: '.$userId],409);  
        }

        $fech_fincompara_startDate = strtotime($data['startDate']);
        $fech_fincompara_endDate = strtotime($data['endDate']);
        $fecha1 = date("Y-m-d");
        $fecha = strtotime($fecha1);
        if(($fech_fincompara_startDate < $fecha)) {
            return new JsonResponse(['msg'=>'Verifique el rango de fecha es incorrecto debe ser mayor o igual : '.$fecha1],409);  
        } 
        if(( $fech_fincompara_endDate < $fecha )) {
            return new JsonResponse(['msg'=>'Verifique el rango de fecha es incorrecto debe ser mayor o igual : '.$fecha1],409);  
        }



        $queryresp2 = $entityManager->createQueryBuilder();
        $allAppointmentsQuery = $queryresp2->select('calendario_recursos_proyecto')
        ->from(CalendarioRecursosProyecto::class,'calendario_recursos_proyecto')
        ->where("calendario_recursos_proyecto.idrecursosproyecto =$idRecursosProyecto")
        ->getQuery();
        $queryrespdata2 = $queryresp2->getQuery();
        $dataresp2 =  $queryrespdata2->execute();
        $contenct=0;
        $fechrango = "";
        $swactv=0;
        if (count($dataresp2)>0) { 
            $encontroUserId=true;
            foreach($dataresp2 as $clave=>$valor2){
                //$idRecursosProyecto = $valor2->getId();
                $fech_inicocompara = strtotime($valor2->getFechainicio()->format("Y-m-d"));
                $fech_fincompara = strtotime($valor2->getFechafin()->format("Y-m-d"));
                //$fech_fincomparaf = date('Y-m-d', $fech_fincompara);
                $fecha = $data['startDate'];
                $fecha = strtotime($fecha);
                if (($fecha >= $fech_inicocompara) && ($fecha <= $fech_fincompara)){
                    $fechrango=$data['startDate'];
                    $swactv = $valor2->getSwactivo();
                    $contenct++;
                }
                $fecha = $data['endDate'];
                $fecha = strtotime($fecha);
                if (($fecha >= $fech_inicocompara) && ($fecha <= $fech_fincompara)){
                    $fechrango=$data['endDate'];
                    $swactv = $valor2->getSwactivo();
                    $contenct++;
                }

                /* if(($fecha <= $fech_fincompara)) {
                    //existe
                    $contenct++;
                    $encontroUserId=false;
                } else {
                    //no existe
                    $encontroUserId=true;
                } */

            }
            
        }else{
            $encontroUserId=true;
        }
        if(($contenct > 0 )) {
            //existe
            $encontroUserId=false;
        }else{
            $encontroUserId=true;
        }

        if(($contenct > 0 && $swactv == 0 && $encontroUserId==false)) {
            $encontroUserId=true;
        }


      if ($encontroUserId==true) {   
            $entity = new CalendarioRecursosProyecto();
            $entity1 =$entityManager->getRepository(RecursosProyecto::class)->find($idRecursosProyecto);
            if (!$entity1) {
                return new JsonResponse(['msg'=>'No existen el Proyecto con el idproyecto: '.$data['projectId']],404);  
            }
            $entity->setIdrecursosproyecto($entity1);
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setCreateAt(new \DateTime());
            $entity->setFechainicio(!is_null($data['startDate'])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data['startDate'] )))):null);
            $entity->setFechafin(!is_null($data['endDate'])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data['endDate'] )))):null);
            $entity->setSwactivo(1);
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);
            $entityManager->persist($entity);
            $entityManager->flush();   
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
            
      }else{
        return new JsonResponse(['msg'=>'Verifique el rango de fecha ya esta dentro de un rango existente : '.$fechrango],409);  
      }

    }



     /**
     * Get Calendario Recursos Proyecto.
     */
    public function postCalendarioRecursosProy($data,$validator,$helper,$Calculos){
        $entityManager = $this->getEntityManager();
        $userId = $data["userId"];
        $projectId =$data['projectId'];
        $totdias=0;
        $sql = " SELECT a.idproyecto_id,a.idrecurso_id,b.idrecursosproyecto_id ,b.id,b.swactivo,b.fechainicio,b.fechafin
         FROM `recursos_proyecto` a inner join calendario_recursos_proyecto b on a.id = b.idrecursosproyecto_id
         where a.idproyecto_id=".$data['projectId']."  AND a.idrecurso_id=".$data["userId"]." AND 
         b.swactivo=1"; 
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            $dataRecursos=array();
            foreach($result as $claveResult=>$valorResult){
                $fech_fincompara1 = strtotime($valorResult["fechainicio"]);
                $startDate = date('Y-m-d', $fech_fincompara1);
                $fech_fincompara2 = strtotime($valorResult["fechafin"]);
                $endDate = date('Y-m-d', $fech_fincompara2);
                $Tiempdifrute= $Calculos->dias_pasados_sin_fin_semana($startDate,$endDate);
                $totdias = $totdias + $Tiempdifrute;
                $dataRecursos[]=array("id"=>$valorResult["id"],"startDate"=>$startDate,"endDate"=>$endDate,"dias"=>$Tiempdifrute);
            }   
            //$dataRecursos[]=array("total"=>$totdias);
            return ($dataRecursos);
    }
    
    /**
     * Delete Calendario Recursos Proyecto.
     */
    public function putCalendarioRecursosProyect($data,$validator,$helper): JsonResponse  {

        $entityManager = $this->getEntityManager();
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity =$entityManager->getRepository(CalendarioRecursosProyecto::class)->find($data["calendarioRecursosId"]);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $calendarioRecursosId = $data["calendarioRecursosId"];
        if (!$data["calendarioRecursosId"]) {
            return new JsonResponse(['msg'=>'La calendarioRecursosId no puede estar en blanco'],200);  
        } 
         $sql2 = "update calendario_recursos_proyecto set swactivo=0 where id=".$data["calendarioRecursosId"]."";
         $conn2 = $this->getEntityManager()->getConnection();
         $stmt2 = $conn2->prepare($sql2);
         $stmt2->execute();

         $data=array("tipoEntidad"=>"Eliminar Calendario Recurso Proyecto","idEntidad"=>$entity->getId(),"createdBy"=>$currentUser->getId(),"sqlInstruction"=>"Delete","accion"=>6);
        $this->traza->post($data,$validator,$helper);

         return new JsonResponse(['msg'=>'Registro Eliminado: '.$calendarioRecursosId],200);
    }


    // /**
    //  * @return CalendarioRecursosProyecto[] Returns an array of CalendarioRecursosProyecto objects
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
    public function findOneBySomeField($value): ?CalendarioRecursosProyecto
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
