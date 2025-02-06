<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\ItemsHorasTrabajadas;
use App\Entity\Proyecto\Spring;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Proyecto\TrazaRepository;
Use App\Entity\User;
Use App\Entity\Proyecto\Items;
use App\Entity\Proyecto\Empresa;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Asset\Packages;

/**
 * @method ItemsHorasTrabajadas|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemsHorasTrabajadas|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemsHorasTrabajadas[]    findAll()
 * @method ItemsHorasTrabajadas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemsHorasTrabajadasRepository extends ServiceEntityRepository
{
    private $assetPackage;
    private $security;
    private $traza;
    public function __construct(ManagerRegistry $registry,Security $security, TrazaRepository $traza,Packages $assetPackage)
    {
        $this->traza=$traza;
        $this->assetPackage=$assetPackage;
        $this->security = $security;
        parent::__construct($registry, ItemsHorasTrabajadas::class);
    }  

    /**
     * Create Items Horas Trabajadas.
     */
    public function post_itemshorastrabajadas($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
 
        $totsexpectedRemaining = 0;

        $sql = " SELECT a.`id` as id_items,a.`id_user_id`,a.`id_backlog_padre_id`,a.`titulo`,a.`descripcion`,a.`peso`,a.`swactivo`
        ,b.id_spring_id, b.id_item_id, b.id as idspring_item
        ,c.idproyecto_id,c.id as id_spring,c.fechainicio,c.fechafin,c.nombre 
        FROM `items` a INNER JOIN sprint_item b on a.id = b.id_item_id INNER JOIN spring c on c.id = b.id_spring_id 
        where a.`id`=".$data["id"]." "; 
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $datarevs=$stmt->fetchAll();
        $idspring = $datarevs[0]["id_spring_id"];
        $idproyct = $datarevs[0]["idproyecto_id"]; 
        $fechaes = date("Y-m-d");

        $fech_incspr = strtotime($datarevs[0]["fechainicio"]);
        $fech_inicio_spring = date('Y-m-d', $fech_incspr);
        $fech_compara = strtotime($fech_inicio_spring);
        $fecha = strtotime($fechaes);
    if (($fecha >= $fech_compara)){

        $sql = " SELECT a.`id` as id_items,a.`id_user_id`,a.`id_backlog_padre_id`,a.`titulo`,a.`descripcion`,a.`peso`,a.`swactivo`,a.idnivelboardpanel_id 
        ,b.id_spring_id, b.id_item_id, b.id as idspring_item
        ,c.idproyecto_id,c.id as id_spring,c.fechainicio,c.fechafin,c.nombre
        FROM `items` a INNER JOIN sprint_item b on a.id = b.id_item_id INNER JOIN spring c on c.id = b.id_spring_id where b.id_spring_id=".$idspring." and a.id_backlog_padre_id is not null and a.peso is not null order by b.id_item_id"; 
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $dataitems=$stmt->fetchAll();    
        $sumapeso=0;
        $startSpring = date('Y-m-d');
        $totalHorasMaxACM=0;
        foreach($dataitems as $clave=>$valor){
             //$totalHorasMaxACM = $totalHorasMaxACM + $valor["peso"];
             $sumapeso = $sumapeso + $valor["peso"];
        }
    

        //Buscar si el items ya esta dentro de la tabla ItemsHorasTrabajadas
        //$entityItemshorastrabajadas =$entityManager->getRepository(ItemsHorasTrabajadas::class)->findBy(array("iditems"=>$data["IdItems"],"iduser"=>$data["IdRecurso"]));
        //$entityItemshorastrabajadas =$entityManager->getRepository(ItemsHorasTrabajadas::class)->findBy(array("idspring"=>$idspring,"fecha"=>$fechaes));

        $sql3 = " SELECT *  FROM items_horas_trabajadas where idspring=".$idspring." and DATE(fecha) between "." '".$fechaes."' AND '".$fechaes."'  order by id ASC"; 
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql3);
        $stmt->execute();
        $entityItemshorastrabajadas=$stmt->fetchAll();

        $iditemshorastrabajadas=0;
        $pesoremanente=0;
        $remanente=0;
        foreach($entityItemshorastrabajadas as $telef){               
          /* $iditemshorastrabajadas = $telef->getId();
          $pesoremanente= $telef->getPesotrabajado(); */
          $iditemshorastrabajadas = $telef["id"];
          $pesoremanente= $telef["pesotrabajado"];
        }
          
          /* if ($pesoremanente > $data['peso']) {
              $remanente = ($pesoremanente - $data['peso']);
          }else{     
              $remanente = 0;
          } */
  
        if ($entityItemshorastrabajadas) {
              $entityg =$entityManager->getRepository(ItemsHorasTrabajadas::class)->find($iditemshorastrabajadas);
              //$pesoremanente = $sumapeso + $data['peso'];
              //$pesoremanente = $pesoremanente + $data['peso'];
              $entityg->setPesotrabajado($sumapeso);
              //$entityg->setPesotrabajado($data['peso']);
              //$entityg->setRemanente($data['remanente']);
              $entityg->setRemanente($remanente);
  
              $currentUser2 =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
              $entityg->setUpdateBy($currentUser2->getUserName());
              $entityg->setUpdateAt(new \DateTime('now'));

              //Buscar cuanto vale el punto de la linea esperada para este dia
              $totsexpectedRemaining = $this->expectedRemaining($idspring,$fechaes);

              $entityg->setEsperadorestante($totsexpectedRemaining);

              $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
              if($empresa)
                 $entityg->setIdempresa($empresa); 
              //************************************************************/
              $entityManager->persist($entityg);
              $entityManager->flush();


            $sql3 = " SELECT *  FROM proyecto where id=".$idproyct." "; 
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql3);
            $stmt->execute();
            $entityProyecto=$stmt->fetchAll(); 
            if ($entityProyecto) {
                $idstatus =  $entityProyecto[0]["idstatuscalendarioproyecto_id"];               
                if ($idstatus==2) {
                    $sql2 = "update proyecto set idstatuscalendarioproyecto_id=1 where id=".$idproyct."";
                    $conn2 = $this->getEntityManager()->getConnection();
                    $stmt2 = $conn2->prepare($sql2);
                    $stmt2->execute();
                }
            }


              return new JsonResponse(['msg'=>'Registro Actualizado','id'=>$entityg->getId()],200);
        }else{
  
          $entityUser =$entityManager->getRepository(User::class)->find($data['id_user_id']);
          if (!$entityUser) {
              return new JsonResponse(['msg'=>'No existen Registros de Recursos (IdRecurso) con el id: '.$data['id_user_id']],404);  
          }
  
          /* $entityItems =$entityManager->getRepository(Spring::class)->find($idspring);
          if (!$entityItems) {
              return new JsonResponse(['msg'=>'No existen Registros de Items con el id: '.$data['id']],404);  
          } */
  
          $entity = new ItemsHorasTrabajadas();
          //$entity=$helper->setParametersToEntity(new ItemsHorasTrabajadas(),$data);
          $entity->setIdspring($idspring);
          $entity->setPesotrabajado($sumapeso);
          //$entity->setPesotrabajado($data['peso']);
          $entity->setRemanente($remanente);
          $entity->setTotalRemaningMax($remanente);
          $entity->setFecha(new \DateTime('now'));
          /* $currentUser1 =$entityManager->getRepository(User::class)->find($data['id_user_id']);
          $entity->setIdUser($currentUser1);  */
          $currentUser2 =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
          $entity->setCreateBy($currentUser2->getUserName());
          $entity->setCreateAt(new \DateTime('now'));
          $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
              if($empresa)
                 $entity->setIdempresa($empresa); 

          //Buscar cuanto vale el punto de la linea esperada para este dia
          $totsexpectedRemaining = $this->expectedRemaining($idspring,$fechaes);
          //************************************************************/
          $entity->setEsperadorestante($totsexpectedRemaining);

          $errors = $validator->validate($entity);
          if($errors->count() > 0){
              $errorsString = (string) $errors;
              return new JsonResponse(['msg'=>$errorsString],500);
          }else{
              $entityManager->persist($entity);
              $entityManager->flush();

              $sql3 = " SELECT *  FROM proyecto where id=".$idproyct." "; 
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql3);
            $stmt->execute();
            $entityProyecto=$stmt->fetchAll(); 
            if ($entityProyecto) {
                $idstatus =  $entityProyecto[0]["idstatuscalendarioproyecto_id"];               
                if ($idstatus==2) {
                    $sql2 = "update proyecto set idstatuscalendarioproyecto_id=1 where id=".$idproyct."";
                    $conn2 = $this->getEntityManager()->getConnection();
                    $stmt2 = $conn2->prepare($sql2);
                    $stmt2->execute();
                }
            }


              return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
          }  
        }  
    }else{
        return new JsonResponse(['msg'=>'La fecha del spring no a iniciado','id'=>$idspring],200);
    }
   }     
      
       /**
     * Create Items Horas Estimadas.
     */
    public function post_itemshorasestimadas($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $totalRemaningMax = 0;
        $contdescuentohoras=0;
        $actdescuentosHoras=[];
        $CreatotalRemaningMax = false;
        $sql = " SELECT *  FROM spring where id=".$data["idSpring"]." order by id ASC"; 
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $dataspring=$stmt->fetchAll();
        /* $fechaInicio=strtotime($valor["fechainicio"]);
        $fechaFin=strtotime($valor["fechafin"]); */
        $fechaInicio=strtotime($dataspring[0]['fechainicio']);
        $fechaFin=strtotime($dataspring[0]['fechafin']);
        $sumadiahabiles=0;
        $cantRecursos=0;
        $sumapeso=0;
        $canthoras=0;
        $resltEsfuerzoIdeal=0;
        $totalRemaningMax=0;
        $i=0;

       
        $fechaes = date("Y-m-d");

        $fech_incspr = strtotime($dataspring[0]['fechainicio']);
        $fech_inicio_spring = date('Y-m-d', $fech_incspr);
        $fech_compara = strtotime($fech_inicio_spring);
        $fecha = strtotime($fechaes);
        if (($fecha >= $fech_compara)){


        $sqlbus1 = " SELECT *  FROM calendario_proyecto where id_proyecto_id=".$dataspring[0]["idproyecto_id"]."  order by id ASC"; 
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlbus1);
        $stmt->execute();
        $databus1=$stmt->fetchAll();
        $esDiaFeriado=false;
        for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
            $fechaes = date("Y-m-d", $i);
            $dia=date("w", strtotime($fechaes));
            //SELECT * FROM `calendario_proyecto` WHERE `fecha_inicio_nolaboral`>='2023-10-17' and `fecha_fin_nolaboral`<='2023-10-23';
            foreach($databus1 as $claveResult2=>$valorResultbusc){
                $fech_fincompara1 = strtotime($valorResultbusc["fecha_inicio_nolaboral"]);
                $fech_inicocompara = date('Y-m-d', $fech_fincompara1);
                $fech_fincompara1 = strtotime($fech_inicocompara);
                $fech_fincompara2 = strtotime($valorResultbusc["fecha_fin_nolaboral"]);
                $fech_fincompara = date('Y-m-d', $fech_fincompara2);
                $fech_fincompara2 = strtotime($fech_fincompara);
                $fecha1 = $fechaes;
                $fecha = strtotime($fecha1);
                if (($fecha == $fech_fincompara1)){
                    $esDiaFeriado=true; 
                }
                if (($fecha == $fech_fincompara2)){
                    $esDiaFeriado=true; 
                }
                /* if (($fecha >= $fech_fincompara1) && ($fecha <= $fech_fincompara2)){
                    $esDiaFeriado=true; 
                } */

            }

            if($dia=="0" or $dia=="6"){ //si es diferente sabado y domingo
            }else{
                if($esDiaFeriado==false){ //si es diferente sabado y domingo
                   $sumadiahabiles++;
                }
                $esDiaFeriado=false;
            }
        }

        //incorporar validaci��n de dias no habiles 


        $esDiaFeriado=false;
        if(count($dataspring)>0){
            $idproyct = $dataspring[0]["idproyecto_id"];
            $sql = " SELECT *  FROM recursos_proyecto where idproyecto_id=".$dataspring[0]["idproyecto_id"]." order by id ASC"; 
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $datarecurproyecto=$stmt->fetchAll();

            foreach($datarecurproyecto as $clave=>$valorrecsproyct){
                $sqlrecr = " SELECT r.id as idrecursos,r.idproyecto_id,r.idrecurso_id, c.fechainicio,c.fechafin FROM
               `recursos_proyecto` r INNER JOIN calendario_recursos_proyecto c on r.id = c.idrecursosproyecto_id 
                where r.idproyecto_id=".$dataspring[0]["idproyecto_id"]." and r.idrecurso_id=".$valorrecsproyct["idrecurso_id"]." ";
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sqlrecr);
                $stmt->execute();
                $datarecursos=$stmt->fetchAll();

                foreach($datarecursos as $claveResult2=>$valorResultrecurs){

                    $fech_fincompara1 = strtotime($valorResultrecurs["fechainicio"]);
                    $fech_inicocompara = date('Y-m-d', $fech_fincompara1);
                    $fech_fincompara1 = strtotime($fech_inicocompara);
                    $fech_fincompara2 = strtotime($valorResultrecurs["fechafin"]);
                    $fech_fincompara = date('Y-m-d', $fech_fincompara2);
                    $fech_fincompara2 = strtotime($fech_fincompara);

                    $fechaInicio=strtotime($dataspring[0]['fechainicio']);
                    $fechaFin=strtotime($dataspring[0]['fechafin']);
                      //comparar aqui con la fecha de los spring  
                    //for($i=$fech_fincompara1; $i<=$fech_fincompara2; $i+=86400){
                        $contgb=0;
                    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){   

                        $fechaes = date("Y-m-d", $i);
                        $fechcompspring=date(strtotime($fechaes));

                       //Fechas del recursos 
                        $fechaver = date($fech_inicocompara, $i);
                        $fecha = strtotime($fechaver);

                        $fechaverfin = date($fech_fincompara, $i);
                        $fechaverfin = strtotime($fechaverfin);

                        //public function compara_dias($fecha_inicial_recurso,$fecha_final_recurso,$fecha_spring){

                         $esDiaFeriado= $this->compara_dias($fecha,$fechaverfin,$fechcompspring);


                        /*$fechainiciospring = date($valorResultrecurs["fechainicio"], $i);
                        $fechafinspring = date($valorResultrecurs["fechafin"], $i); */

                        //Comparar la fecha de inicio de calendario_recursos_proyecto con las del spring para ver si hay coincidencias 
                        /* if (($fecha == $fechcompspring)){
                            $esDiaFeriado=true; 
                        } */
                        /* if (($fecha == $fechaFin)){
                            $esDiaFeriado=true; 
                        } */

                        if ($esDiaFeriado==true){
                            $contgb++; 
                            $contdescuentohoras++;
                            $actdescuentosHoras[]=array("descuentosHoras"=>$valorrecsproyct["horasdedicacion"]);
                        }
                        /* if (($fechaverfin == $fechaFin)){
                            $esDiaFeriado=true; 
                        } */

                    }
    
                }


                

                $cantRecursos++;
                //if($esDiaFeriado==false){ //si es diferente sabado y domingo
                //if($contgb==0){ //si es diferente sabado y domingo
                  $canthoras = $canthoras + $valorrecsproyct["horasdedicacion"];
                //}
                $esDiaFeriado=false;
                
             }
               

                //************************************************************************
                
            }

        

        $i=0;
        for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
            $fechaes = date("Y-m-d", $i);
            $dia=date("w", strtotime($fechaes));
            if($dia=="0" or $dia=="6"){ //si es diferente sabado y domingo
            }else{
                //$resltEsfuerzoIdeal = ($cantRecursos * $canthoras * $sumadiahabiles);
                $resltEsfuerzoIdeal = ($canthoras * $sumadiahabiles);
                $totalRemaningMax = $totalRemaningMax + $resltEsfuerzoIdeal;
                break;
            }
        }
        
        
      //**** hasta aqui **********



        if($contdescuentohoras>0){ //validar si hay que descontar horas de recursos que estan libres
            //contdescuentohoras
            foreach($actdescuentosHoras as $clave=>$valordeschoras){
                $cantdesc= $valordeschoras["descuentosHoras"];
                $totalRemaningMax = $totalRemaningMax - $cantdesc;
             }
        }
       
        $i=0;
        $cont=0;
        $resltEsfuerzoIdeal =0;
        $dataBurndown=[];
        $esperadoRestante1 =0;
        $esperadoRestante2 =0;
        $horasreales =0;
        $acmtotRealRemaining=0;

        $acmanteriorferiado=0;


        for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
            $fechaes = date("Y-m-d", $i);
            $dia=date("w", strtotime($fechaes));

            $sql3 = " SELECT *  FROM items_horas_trabajadas where idspring=".$data["idSpring"]." and DATE(fecha) between "." '".$fechaes."' AND '".$fechaes."'  order by id ASC"; 
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql3);
            $stmt->execute();
            $dataitetrabajo=$stmt->fetchAll();
            $iditemshorastrabajadas=0;
            $pesoremanente=0;
            $remanente=0;
            $horasreales=0;
            $acmexpectedremainingbd=0;

            if(count($dataitetrabajo)<0){
                return new JsonResponse(['msg'=>'El spring le faltan confuguraciones de horas en las tareas: '.$data["idSpring"]],409);  
            }

            foreach($dataitetrabajo as $clave=>$valoritemhorastrabj){
                //$horasreales = $horasreales + $valoritemhorastrabj["pesotrabajado"];
                $horasreales = $valoritemhorastrabj["pesotrabajado"];
                $acmexpectedremainingbd = $valoritemhorastrabj["esperadorestante"];
            }

            if($dia=="0" or $dia=="6"){ //si es diferente sabado y domingo
            }else{
               $cont++;
               //$resltEsfuerzoIdeal = ($cantRecursos * $canthoras * $sumadiahabiles);
               $resltEsfuerzoIdeal = ($canthoras * $sumadiahabiles);
               $totRealRemaining = $totalRemaningMax;

               if($cont==1){ 

                $esperadoRestante1 = $totalRemaningMax - $canthoras;


                if($horasreales!=0){
                    $totRealRemaining = $horasreales;
                    //$totRealRemaining = $totRealRemaining - $horasreales;
                    $acmtotRealRemaining = $totRealRemaining;
                }else{
                    $totRealRemaining = $totalRemaningMax;
                    $acmtotRealRemaining = $totalRemaningMax;
                }

                if($acmexpectedremainingbd!=0){
                    $dataBurndown[]=array("date"=>$fechaes,"expectedRemaining"=>$acmexpectedremainingbd,"realRemaining"=>$totRealRemaining);
                    $acmanteriorferiado= $acmexpectedremainingbd;
                }else{
                    $dataBurndown[]=array("date"=>$fechaes,"expectedRemaining"=>$esperadoRestante1,"realRemaining"=>$totRealRemaining);
                    $acmanteriorferiado= $esperadoRestante1;
                }
                

             }else{


                $sqlbus1 = " SELECT *  FROM calendario_proyecto where id_proyecto_id=".$dataspring[0]["idproyecto_id"]."  order by id ASC"; 
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sqlbus1);
                $stmt->execute();
                $databus1=$stmt->fetchAll();
                $esDiaFeriado=false;
                $ip=0;
                for($ip=$fechaInicio; $ip<=$fechaFin; $ip+=86400){
                    //$fechaes = date("Y-m-d", $i);
                    $dia=date("w", strtotime($fechaes));
                    foreach($databus1 as $claveResult2=>$valorResultbusc){
                        $fech_fincompara1 = strtotime($valorResultbusc["fecha_inicio_nolaboral"]);
                        $fech_inicocompara = date('Y-m-d', $fech_fincompara1);
                        $fech_fincompara1 = strtotime($fech_inicocompara);
                        $fech_fincompara2 = strtotime($valorResultbusc["fecha_fin_nolaboral"]);
                        $fech_fincompara = date('Y-m-d', $fech_fincompara2);
                        $fech_fincompara2 = strtotime($fech_fincompara);
                        $fecha1 = $fechaes;
                        $fecha = strtotime($fecha1);
                        if (($fecha == $fech_fincompara1)){
                            $esDiaFeriado=true; 
                        }
                        if (($fecha == $fech_fincompara2)){
                            $esDiaFeriado=true; 
                        }
                    }
        
                    if($dia=="0" or $dia=="6"){ //si es diferente sabado y domingo
                    }else{
                        if($esDiaFeriado==true){ //si es diferente sabado y domingo
                           break;
                        }
                        //$esDiaFeriado=false;
                    }
                }   


                if($esDiaFeriado==false){ //si es diferente sabado y domingo
                    $esperadoRestante2 = $esperadoRestante1 - $canthoras;
                }else{
                    
                    //$esperadoRestante2 = $esperadoRestante1;
                    //$esperadoRestante2 = $esperadoRestante1 + $canthoras;
                    $esperadoRestante2 = $acmanteriorferiado;
                }
               
                if($horasreales==0){ 
                    //$totRealRemaining = 0;
                    $totRealRemaining = $acmtotRealRemaining;
                }else{
                    //$totRealRemaining = $acmtotRealRemaining - $horasreales;
                    $totRealRemaining = $horasreales;
                }

                if($acmexpectedremainingbd!=0){
                    $dataBurndown[]=array("date"=>$fechaes,"expectedRemaining"=>$acmexpectedremainingbd,"realRemaining"=>$totRealRemaining);
                    $acmanteriorferiado= $acmexpectedremainingbd;
                }else{
                    $dataBurndown[]=array("date"=>$fechaes,"expectedRemaining"=>$esperadoRestante2,"realRemaining"=>$totRealRemaining);
                    $acmanteriorferiado= $esperadoRestante2;
                }




                $esperadoRestante1 = $esperadoRestante2;
                $acmtotRealRemaining = $totRealRemaining; 

               }

            }
        }

        $datat=array("springId"=>$dataspring[0]['id'],"springName"=>$dataspring[0]['nombre'],"startDate"=>$dataspring[0]['fechainicio'],"endDate"=>$dataspring[0]['fechafin'],"totalRemaningMax"=>$totalRemaningMax,"dataBurndown"=>$dataBurndown);
        return new JsonResponse($datat,200);
        //return new JsonResponse(['Respuesta'=>$datat],200);
    }else{
        return new JsonResponse(['msg'=>'El spring no ha iniciado','id'=>$data["idSpring"]],409);
    }

    }


    public function compara_dias($fecha_inicial_recurso,$fecha_final_recurso,$fecha_spring){
        $esDiaFeriado=false;
        for($i=$fecha_inicial_recurso; $i<=$fecha_final_recurso; $i+=86400){   
            $fechaes = date("Y-m-d", $i);
            $fecha=date(strtotime($fechaes));
            if (($fecha == $fecha_spring)){
                $esDiaFeriado=true; 
            }
        }
        return $esDiaFeriado;
    }



    /**
     * Create Items Horas Estimadas.
     */
    public function expectedRemaining($idspring,$fechasalida){
        $entityManager = $this->getEntityManager();
       
        $totexpectedremaining=0;

        $totalRemaningMax = 0;
        $contdescuentohoras=0;
        $actdescuentosHoras=[];
        $CreatotalRemaningMax = false;
        $sql = " SELECT *  FROM spring where id=".$idspring." order by id ASC"; 
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $dataspring=$stmt->fetchAll();
        /* $fechaInicio=strtotime($valor["fechainicio"]);
        $fechaFin=strtotime($valor["fechafin"]); */
        $fechaInicio=strtotime($dataspring[0]['fechainicio']);
        $fechaFin=strtotime($dataspring[0]['fechafin']);
        $sumadiahabiles=0;
        $cantRecursos=0;
        $sumapeso=0;
        $canthoras=0;
        $resltEsfuerzoIdeal=0;
        $totalRemaningMax=0;
        $i=0;

        $sqlbus1 = " SELECT *  FROM calendario_proyecto where id_proyecto_id=".$dataspring[0]["idproyecto_id"]."  order by id ASC"; 
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlbus1);
        $stmt->execute();
        $databus1=$stmt->fetchAll();
        $esDiaFeriado=false;
        for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
            $fechaes = date("Y-m-d", $i);
            $dia=date("w", strtotime($fechaes));
            //SELECT * FROM `calendario_proyecto` WHERE `fecha_inicio_nolaboral`>='2023-10-17' and `fecha_fin_nolaboral`<='2023-10-23';
            foreach($databus1 as $claveResult2=>$valorResultbusc){
                $fech_fincompara1 = strtotime($valorResultbusc["fecha_inicio_nolaboral"]);
                $fech_inicocompara = date('Y-m-d', $fech_fincompara1);
                $fech_fincompara1 = strtotime($fech_inicocompara);
                $fech_fincompara2 = strtotime($valorResultbusc["fecha_fin_nolaboral"]);
                $fech_fincompara = date('Y-m-d', $fech_fincompara2);
                $fech_fincompara2 = strtotime($fech_fincompara);
                $fecha1 = $fechaes;
                $fecha = strtotime($fecha1);
                if (($fecha == $fech_fincompara1)){
                    $esDiaFeriado=true; 
                }
                if (($fecha == $fech_fincompara2)){
                    $esDiaFeriado=true; 
                }
                /* if (($fecha >= $fech_fincompara1) && ($fecha <= $fech_fincompara2)){
                    $esDiaFeriado=true; 
                } */

            }

            if($dia=="0" or $dia=="6"){ //si es diferente sabado y domingo
            }else{
                if($esDiaFeriado==false){ //si es diferente sabado y domingo
                   $sumadiahabiles++;
                }
                $esDiaFeriado=false;
            }
        }

        //incorporar validación de dias no habiles 


        $esDiaFeriado=false;
        if(count($dataspring)>0){
            $idproyct = $dataspring[0]["idproyecto_id"];
            $sql = " SELECT *  FROM recursos_proyecto where idproyecto_id=".$dataspring[0]["idproyecto_id"]." order by id ASC"; 
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $datarecurproyecto=$stmt->fetchAll();

            foreach($datarecurproyecto as $clave=>$valorrecsproyct){
                $sqlrecr = " SELECT r.id as idrecursos,r.idproyecto_id,r.idrecurso_id, c.fechainicio,c.fechafin FROM
               `recursos_proyecto` r INNER JOIN calendario_recursos_proyecto c on r.id = c.idrecursosproyecto_id 
                where r.idproyecto_id=".$dataspring[0]["idproyecto_id"]." and r.idrecurso_id=".$valorrecsproyct["idrecurso_id"]." ";
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sqlrecr);
                $stmt->execute();
                $datarecursos=$stmt->fetchAll();

                foreach($datarecursos as $claveResult2=>$valorResultrecurs){

                    $fech_fincompara1 = strtotime($valorResultrecurs["fechainicio"]);
                    $fech_inicocompara = date('Y-m-d', $fech_fincompara1);
                    $fech_fincompara1 = strtotime($fech_inicocompara);
                    $fech_fincompara2 = strtotime($valorResultrecurs["fechafin"]);
                    $fech_fincompara = date('Y-m-d', $fech_fincompara2);
                    $fech_fincompara2 = strtotime($fech_fincompara);

                    $fechaInicio=strtotime($dataspring[0]['fechainicio']);
                    $fechaFin=strtotime($dataspring[0]['fechafin']);
                      //comparar aqui con la fecha de los spring  
                    //for($i=$fech_fincompara1; $i<=$fech_fincompara2; $i+=86400){
                        $contgb=0;
                    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){   

                        $fechaes = date("Y-m-d", $i);
                        $fechcompspring=date(strtotime($fechaes));

                       //Fechas del recursos 
                        $fechaver = date($fech_inicocompara, $i);
                        $fecha = strtotime($fechaver);

                        $fechaverfin = date($fech_fincompara, $i);
                        $fechaverfin = strtotime($fechaverfin);

                        //public function compara_dias($fecha_inicial_recurso,$fecha_final_recurso,$fecha_spring){

                         $esDiaFeriado= $this->compara_dias($fecha,$fechaverfin,$fechcompspring);


                        /*$fechainiciospring = date($valorResultrecurs["fechainicio"], $i);
                        $fechafinspring = date($valorResultrecurs["fechafin"], $i); */

                        //Comparar la fecha de inicio de calendario_recursos_proyecto con las del spring para ver si hay coincidencias 
                        /* if (($fecha == $fechcompspring)){
                            $esDiaFeriado=true; 
                        } */
                        /* if (($fecha == $fechaFin)){
                            $esDiaFeriado=true; 
                        } */

                        if ($esDiaFeriado==true){
                            $contgb++; 
                            $contdescuentohoras++;
                            $actdescuentosHoras[]=array("descuentosHoras"=>$valorrecsproyct["horasdedicacion"]);
                        }
                        /* if (($fechaverfin == $fechaFin)){
                            $esDiaFeriado=true; 
                        } */

                    }
    
                }


                

                $cantRecursos++;
                //if($esDiaFeriado==false){ //si es diferente sabado y domingo
                //if($contgb==0){ //si es diferente sabado y domingo
                  $canthoras = $canthoras + $valorrecsproyct["horasdedicacion"];
                //}
                $esDiaFeriado=false;
                
             }
               

                //************************************************************************
                
            }

        

        $i=0;
        for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
            $fechaes = date("Y-m-d", $i);
            $dia=date("w", strtotime($fechaes));
            if($dia=="0" or $dia=="6"){ //si es diferente sabado y domingo
            }else{
                //$resltEsfuerzoIdeal = ($cantRecursos * $canthoras * $sumadiahabiles);
                $resltEsfuerzoIdeal = ($canthoras * $sumadiahabiles);
                $totalRemaningMax = $totalRemaningMax + $resltEsfuerzoIdeal;
                break;
            }
        }
        
        if($contdescuentohoras>0){ //validar si hay que descontar horas de recursos que estan libres
            //contdescuentohoras
            foreach($actdescuentosHoras as $clave=>$valordeschoras){
                $cantdesc= $valordeschoras["descuentosHoras"];
                $totalRemaningMax = $totalRemaningMax - $cantdesc;
             }
        }
       
        $i=0;
        $cont=0;
        $resltEsfuerzoIdeal =0;
        $dataBurndown=[];
        $esperadoRestante1 =0;
        $esperadoRestante2 =0;
        $horasreales =0;
        $acmtotRealRemaining=0;

        for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
            $fechaes = date("Y-m-d", $i);
            $dia=date("w", strtotime($fechaes));

            $sql3 = " SELECT *  FROM items_horas_trabajadas where idspring=".$idspring." and DATE(fecha) between "." '".$fechaes."' AND '".$fechaes."'  order by id ASC"; 
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql3);
            $stmt->execute();
            $dataitetrabajo=$stmt->fetchAll();
            $iditemshorastrabajadas=0;
            $pesoremanente=0;
            $remanente=0;
            $horasreales=0;

            if(count($dataitetrabajo)<0){
                return new JsonResponse(['msg'=>'El spring le faltan confuguraciones de horas en las tareas: '.$idspring],409);  
            }

            foreach($dataitetrabajo as $clave=>$valoritemhorastrabj){
                //$horasreales = $horasreales + $valoritemhorastrabj["pesotrabajado"];
                $horasreales = $valoritemhorastrabj["pesotrabajado"];
            }

            if($dia=="0" or $dia=="6"){ //si es diferente sabado y domingo
            }else{
               $cont++;
               //$resltEsfuerzoIdeal = ($cantRecursos * $canthoras * $sumadiahabiles);
               $resltEsfuerzoIdeal = ($canthoras * $sumadiahabiles);
               $totRealRemaining = $totalRemaningMax;

               if($cont==1){ 

                $esperadoRestante1 = $totalRemaningMax - $canthoras;
                
                $totexpectedremaining = $esperadoRestante1;

                if($horasreales!=0){
                    $totRealRemaining = $horasreales;
                    //$totRealRemaining = $totRealRemaining - $horasreales;
                    $acmtotRealRemaining = $totRealRemaining;
                }else{
                    $totRealRemaining = $totalRemaningMax;
                    $acmtotRealRemaining = $totalRemaningMax;
                }

                $dataBurndown[]=array("date"=>$fechaes,"expectedRemaining"=>$esperadoRestante1,"realRemaining"=>$totRealRemaining);

             }else{


                $sqlbus1 = " SELECT *  FROM calendario_proyecto where id_proyecto_id=".$dataspring[0]["idproyecto_id"]."  order by id ASC"; 
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sqlbus1);
                $stmt->execute();
                $databus1=$stmt->fetchAll();
                $esDiaFeriado=false;
                $ip=0;
                for($ip=$fechaInicio; $ip<=$fechaFin; $ip+=86400){
                    //$fechaes = date("Y-m-d", $i);
                    $dia=date("w", strtotime($fechaes));
                    foreach($databus1 as $claveResult2=>$valorResultbusc){
                        $fech_fincompara1 = strtotime($valorResultbusc["fecha_inicio_nolaboral"]);
                        $fech_inicocompara = date('Y-m-d', $fech_fincompara1);
                        $fech_fincompara1 = strtotime($fech_inicocompara);
                        $fech_fincompara2 = strtotime($valorResultbusc["fecha_fin_nolaboral"]);
                        $fech_fincompara = date('Y-m-d', $fech_fincompara2);
                        $fech_fincompara2 = strtotime($fech_fincompara);
                        $fecha1 = $fechaes;
                        $fecha = strtotime($fecha1);
                        if (($fecha == $fech_fincompara1)){
                            $esDiaFeriado=true; 
                        }
                        if (($fecha == $fech_fincompara2)){
                            $esDiaFeriado=true; 
                        }
                    }
        
                    if($dia=="0" or $dia=="6"){ //si es diferente sabado y domingo
                    }else{
                        if($esDiaFeriado==true){ //si es diferente sabado y domingo
                           break;
                        }
                        //$esDiaFeriado=false;
                    }
                }   


                if($esDiaFeriado==false){ //si es diferente sabado y domingo
                    $esperadoRestante2 = $esperadoRestante1 - $canthoras;
                }else{
                    $esperadoRestante2 = $esperadoRestante1;
                }

                $totexpectedremaining = $esperadoRestante2;
               
                if($horasreales==0){ 
                    //$totRealRemaining = 0;
                    $totRealRemaining = $acmtotRealRemaining;
                }else{
                    //$totRealRemaining = $acmtotRealRemaining - $horasreales;
                    $totRealRemaining = $horasreales;
                }

                $dataBurndown[]=array("date"=>$fechaes,"expectedRemaining"=>$esperadoRestante2,"realRemaining"=>$totRealRemaining);
                $esperadoRestante1 = $esperadoRestante2;
                $acmtotRealRemaining = $totRealRemaining; 

               }

            }

            if (($fechasalida == $fechaes)){
                break;
            }


        }

        //$datat=array("springId"=>$dataspring[0]['id'],"springName"=>$dataspring[0]['nombre'],"startDate"=>$dataspring[0]['fechainicio'],"endDate"=>$dataspring[0]['fechafin'],"totalRemaningMax"=>$totalRemaningMax,"dataBurndown"=>$dataBurndown);
        //return new JsonResponse($datat,200);
        return $totexpectedremaining;
        //return new JsonResponse(['Respuesta'=>$datat],200);

    }

    // /**
    //  * @return ItemsHorasTrabajadas[] Returns an array of ItemsHorasTrabajadas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemsHorasTrabajadas
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
