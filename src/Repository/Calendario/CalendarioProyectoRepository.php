<?php

namespace App\Repository\Calendario;

use App\Entity\Calendario\CalendarioProyecto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
Use App\Entity\Proyecto\Proyecto;
use App\Dto\Calendario\CalendarioProyectoOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method CalendarioProyecto|null find($id, $lockMode = null, $lockVersion = null)
 * @method CalendarioProyecto|null findOneBy(array $criteria, array $orderBy = null)
 * @method CalendarioProyecto[]    findAll()
 * @method CalendarioProyecto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarioProyectoRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, CalendarioProyecto::class);
    }


      /**
     * Create Calendario Proyecto.
     */
    public function post($data,$validator,$helper): JsonResponse  {

        //var_dump($data);die;
        $entityManager = $this->getEntityManager();
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
        
         $idproyc = $data["projectId"];

        $queryresp2 = $entityManager->createQueryBuilder();
        $allAppointmentsQuery = $queryresp2->select('calendario_proyecto')
        ->from(CalendarioProyecto::class,'calendario_proyecto')
        ->where("calendario_proyecto.id_proyecto =$idproyc")
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
                $fech_inicocompara = strtotime($valor2->getFechaInicioNolaboral()->format("Y-m-d"));
                $fech_fincompara = strtotime($valor2->getFechaFinNolaboral()->format("Y-m-d"));
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
            
            $entity=$helper->setParametersToEntity(new CalendarioProyecto(),$data);
            $errors = $validator->validate($entity);
            if($errors->count() > 0){
                $errorsString = (string) $errors;
                return new JsonResponse(['msg'=>$errorsString],500);
            }else{
                $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                 //***************************************************** */
                 //foreach($data['arrayofnonworkingdays'] as $options){
                     $entity13 =$entityManager->getRepository(Proyecto::class)->find($idproyc);
                     if (!$entity13) {
                         return new JsonResponse(['msg'=>'No existen el Proyecto con el idproyecto: '.$idproyc],404);  
                     }
                    $opciones = new CalendarioProyecto();
                    $opciones->setIdProyecto($entity13);
                    $opciones->setFechaInicioNolaboral(!is_null($data['startDate'])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data['startDate'] )))):null);
                    $opciones->setFechaFinNolaboral(!is_null($data['endDate'])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data['endDate'] )))):null);
                    $opciones->setSwactivo(1);
                    $opciones->setCreateBy($currentUser->getUserName());
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                       $opciones->setIdempresa($empresa); 
                    $entityManager->persist($opciones);
                    $entityManager->flush();    
                //}
    
                return new JsonResponse(['msg'=>'Registro Creado','id'=>$opciones->getId()],200);
            }  

        }else{
            return new JsonResponse(['msg'=>'Verifique el rango de fecha ya esta dentro de un rango existente : '.$fechrango],409);  
        }
  }


                /**
     * Listar Calendario Proyecto.
     */
    public function findList($Calculos)
    {
        $entityManager = $this->getEntityManager();
        $data= $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $totdias=0;
        $dataCalendarioProyecto=[];
        foreach($data as $clave=>$valor){
            $CalendarioProyectoDto =new CalendarioProyectoOutPutDto();
            $CalendarioProyectoDto->id=$valor->getId();
            $Proyecto = $entityManager->getRepository(Proyecto::class)->find($valor->getIdProyecto());
            $CalendarioProyectoDto->id_proyecto=$Proyecto->getId();
            $CalendarioProyectoDto->nombre=($Proyecto->getNombre());
            $CalendarioProyectoDto->fecha_inicio_nolaboral=!is_null($valor->getFechaInicioNolaboral())?$valor->getFechaInicioNolaboral()->format("Y-m-d"):null;
            $CalendarioProyectoDto->fecha_fin_nolaboral=!is_null($valor->getFechaFinNolaboral())?$valor->getFechaFinNolaboral()->format("Y-m-d"):null;

            $fech_fincompara1 = strtotime($valor->getFechaInicioNolaboral()->format("Y-m-d"));
            $startDate = date('Y-m-d', $fech_fincompara1);
            $fech_fincompara2 = strtotime($valor->getFechaFinNolaboral()->format("Y-m-d"));
            $endDate = date('Y-m-d', $fech_fincompara2);

           // $Tiempdifrute=$this->dias_pasados($startDate,$endDate); 

            $Tiempdifrute= $Calculos->dias_pasados_sin_fin_semana($startDate,$endDate);

            $CalendarioProyectoDto->totaldias=$Tiempdifrute;


            $dataCalendarioProyecto[]=$CalendarioProyectoDto;
            
        }
       return array("data"=>$dataCalendarioProyecto);
 
    }

        /**
     * Update Calendario Proyecto.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(CalendarioProyecto::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $entity=$helper->setParametersToEntity($entity,$data);
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity->setUpdateBy($currentUser->getUserName());
        $entity->setUpdateAt(new \DateTime());
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return new JsonResponse($messages,500);
        }else{
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa); 
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }

    }

    public function getCalendarioById($id,$Calculos){

        $entityManager = $this->getEntityManager();
        $data= $this->createQueryBuilder('c')
            ->where("c.swactivo =1")
            ->andWhere('c.id_proyecto='.$id)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $dataCalendarioProyecto=[];
        $totdias=0;
        foreach($data as $clave=>$valor){
            $CalendarioProyectoDto =new CalendarioProyectoOutPutDto();
            $CalendarioProyectoDto->id=$valor->getId();
            $Proyecto = $entityManager->getRepository(Proyecto::class)->find($valor->getIdProyecto());
            $CalendarioProyectoDto->id_proyecto=$Proyecto->getId();
            $CalendarioProyectoDto->nombre_proyecto=($Proyecto->getNombre());
            $CalendarioProyectoDto->fecha_inicio_nolaboral=!is_null($valor->getFechaInicioNolaboral())?$valor->getFechaInicioNolaboral()->format("Y-m-d"):null;
            $CalendarioProyectoDto->fecha_fin_nolaboral=!is_null($valor->getFechaFinNolaboral())?$valor->getFechaFinNolaboral()->format("Y-m-d"):null;

            $fech_fincompara1 = strtotime($valor->getFechaInicioNolaboral()->format("Y-m-d"));
            $startDate = date('Y-m-d', $fech_fincompara1);
            $fech_fincompara2 = strtotime($valor->getFechaFinNolaboral()->format("Y-m-d"));
            $endDate = date('Y-m-d', $fech_fincompara2);
            $Tiempdifrute= $Calculos->dias_pasados_sin_fin_semana($startDate,$endDate);
            $CalendarioProyectoDto->dias=$Tiempdifrute;
            $totdias = $totdias + $Tiempdifrute;

            $dataCalendarioProyecto[]=$CalendarioProyectoDto;
        }
       return array("data"=>$dataCalendarioProyecto,"totaldias"=>$totdias);
    }

    /**
     * Delete Calendario Proyecto.
     */
    public function putCalendarioProyecto($data,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $sql2 = "update calendario_proyecto set swactivo=0 where id =".$data["idCalendarioProyecto"]." ";
        $conn2 = $this->getEntityManager()->getConnection();
        $stmt2 = $conn2->prepare($sql2);
        $stmt2->execute();  
       return new JsonResponse(['msg'=>'Registro Eliminado: '.$data["idCalendarioProyecto"]],200);
    }


    // /**
    //  * @return CalendarioProyecto[] Returns an array of CalendarioProyecto objects
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
    public function findOneBySomeField($value): ?CalendarioProyecto
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
