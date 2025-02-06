<?php

namespace App\Repository\Proyecto;
use App\Repository\Proyecto\TrazaRepository;
use App\Entity\Proyecto\Proyecto;
use App\Entity\Proyecto\RecursosProyecto;
use App\Entity\Proyecto\CalendarioRecursosProyecto;
use App\Dto\Proyecto\ProyectoOutPutDto;
use App\Entity\Calendario\Statuscalendarioproyecto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
Use App\Entity\User;
Use App\Entity\Proyecto\SprintItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Asset\Packages;
use App\Entity\Proyecto\Empresa;

/**
 * @method Proyecto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proyecto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proyecto[]    findAll()
 * @method Proyecto[]    findSpringRecursos()   
 * @method Proyecto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProyectoRepository extends ServiceEntityRepository
{
    private $security;
    private $traza;
    private $assetPackage;
    public function __construct(ManagerRegistry $registry,Security $security,Packages $assetPackage,TrazaRepository $traza)
    {
        $this->traza=$traza;
        $this->security = $security;
        $this->assetPackage=$assetPackage;
        parent::__construct($registry, Proyecto::class);
    }
     

    public function findAllPage($data){

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
       
        $query= $this->createQueryBuilder('a');
        $query->orderBy('a.id', 'ASC');
        if($data['word']!=null){
            $query->where("a.nombre like '%".$data['word']."%' ");
        }

        $query->orderBy('a.id', 'ASC');   
        $query->getQuery();

        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery()	
      	->setFirstResult($data['rowByPage'] *($data['page']-1))	
      	->setMaxResults($data['rowByPage']);	
        $dataUser=array();
        $hijos=[];
        $rolesUser=[];

        $entityManager = $this->getEntityManager();
            /* $sql = " SELECT sum(i.peso) as total FROM `proyecto` a 
            inner join spring b on a.id = b.idproyecto_id 
            inner join sprint_item x on b.id = x.id_spring_id 
            inner join items i on x.id_item_id = i.id_backlog_padre_id;";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            $dataTotal=array();
            foreach($result as $claveResult=>$valorResult){
                $dataTotal[]=array("Total"=>$valorResult["total"]);
            }    */

        $dataproyecto=array();
        foreach($paginator as $clave=>$valor){
            $idproyecto = $valor->getId();
            /* $proyectoDto =new ProyectoOutPutDto();
            $proyectoDto->id=$valor->getId();
            $proyectoDto->nombre=$valor->getNombre();
            $proyectoDto->idempresa=($valor->getIdempresa()!=null)?array("id"=>$valor->getIdempresa()->getId(),"Nombre"=>$valor->getIdempresa()->getNombre()):[];         */
            
            /* $sql = " SELECT sum(i.peso) as total, a.idstatuscalendarioproyecto_id FROM `proyecto` a 
            inner join spring b on a.id = b.idproyecto_id 
            inner join sprint_item x on b.id = x.id_spring_id 
            inner join items i on x.id_item_id = i.id_backlog_padre_id where a.id=".$valor->getId().";"; */


            /* $sql = " SELECT a.horaestimadas,a.idstatuscalendarioproyecto_id, b.id as idspring FROM `proyecto` a 
            inner join spring b on a.id = b.idproyecto_id 
            inner join sprint_item x on b.id = x.id_spring_id 
            inner join items i on x.id_item_id = i.id_backlog_padre_id where a.id=".$valor->getId().";"; */

            $sql = " SELECT DISTINCT b.id as idspring, a.idstatuscalendarioproyecto_id, a.horaestimadas FROM `proyecto` a 
            inner join spring b on a.id = b.idproyecto_id 
            inner join sprint_item x on b.id = x.id_spring_id 
            inner join items i on x.id_item_id = i.id_backlog_padre_id where a.id=".$valor->getId().";";

            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            $dataTotal=array();
            $colorprogress='';
            $acmpesospringtot=0;
            $acmpesospringfinaltot=0;
            $horasestimadasproyecto = 0;

            if ($result) {
                $tothorapromedioproyecto = $this->promediohorasproyecto($result[0]["idspring"]);    
            }

            foreach($result as $claveResult=>$valorResult){
                //$dataTotal[]=array("Total"=>$valorResult["total"] );
                
                $sql3 = " SELECT *  FROM items_horas_trabajadas where idspring=".$valorResult["idspring"]."  order by id ASC"; 
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql3);
                $stmt->execute();
                $dataitetrabajo=$stmt->fetchAll();
                $iditemshorastrabajadas=0;
                $pesoremanente=0;
                $remanente=0;
                $horasreales=0;
                $progreso=0;

                $contdiaspring=0;
                $horasrealesiniciodiasspring=0;
                $horasrealesfindiasspring=0;

                foreach($dataitetrabajo as $clave=>$valoritemhorastrabj){
                    $contdiaspring++;
                    if($contdiaspring==1){
                        $horasrealesiniciodiasspring = $valoritemhorastrabj["pesotrabajado"];
                    }else{
                        $horasrealesfindiasspring= $valoritemhorastrabj["pesotrabajado"];
                    }
                    //$horasreales = $horasreales  + $valoritemhorastrabj["pesotrabajado"];
                }

                if($contdiaspring==1){
                    $horasreales = $horasrealesiniciodiasspring;
                    //$acmpesospringtot = $horasreales;
                    $acmpesospringtot = $acmpesospringtot + $horasreales;
                }else{
                        if( $horasrealesiniciodiasspring > $horasrealesfindiasspring ){
                            $horasreales = $horasrealesiniciodiasspring - $horasrealesfindiasspring;
                        }else{
                            $horasreales = $horasrealesfindiasspring - $horasrealesiniciodiasspring;
                        }
                    $acmpesospringtot = $acmpesospringtot + $horasreales;
                }

                /* if($horasreales!=0){
                    $progreso = ($horasreales/$valorResult["horaestimadas"])*100;
                    $progreso = number_format($progreso, 2);
                    //$progreso = number_format($progreso, 2, '.', ',');
                } */

                if($valorResult["idstatuscalendarioproyecto_id"]==3){
                    //Rojo
                    $colorprogress='danger';
                }else if($valorResult["idstatuscalendarioproyecto_id"]==4){
                    //Naranja
                    $colorprogress='active';

                }else if($valorResult["idstatuscalendarioproyecto_id"]==2){
                    //amarrillo 
                    $colorprogress='warning';
                }else if($valorResult["idstatuscalendarioproyecto_id"]==1){
                    //verde
                    $colorprogress='success';
                }
                $horasestimadasproyecto = $valorResult["horaestimadas"];

                //$dataTotal[]=array("totalprogress"=>!is_null($valorResult["total"])?$valorResult["total"]:"0","colorprogress"=>$colorprogress);

                //$dataTotal[]=array("totalprogress"=>$progreso,"colorprogress"=>$colorprogress);
                //break;   
            }   

            //$acmpesospringfinaltot = $acmpesospringfinaltot + $acmpesospringtot;

            
            if(count($result)>0){
                //$progreso = ($acmpesospringtot/$horasestimadasproyecto)*100;
                if($tothorapromedioproyecto==0){
                    $progreso = 0;
                }else{
                    $progreso = ($acmpesospringtot/$tothorapromedioproyecto)*100;
                }
                
                //$progreso = ($horasreales/$valorResult["horaestimadas"])*100;
                $progreso = number_format($progreso, 2);
                $dataTotal[]=array("totalprogress"=>$progreso,"colorprogress"=>$colorprogress);
            }else{
                $dataTotal[]=array("totalprogress"=>0,"colorprogress"=>$colorprogress);
            }




            $proyectoDto =new ProyectoOutPutDto();
            $proyectoDto->id=$valor->getId();
            $proyectoDto->nombre=$valor->getNombre();
            $proyectoDto->descripcion=$valor->getDescripcion();
            //$proyectoDto->fechainicio=!is_null($valor->getFechainicio())?$valor->getFechainicio()->format("d-m-Y"):null;
            //$proyectoDto->fechafin=!is_null($valor->getFechafin())?$valor->getFechafin()->format("d-m-Y"):null;

            $proyectoDto->fechaInicio=!is_null($valor->getFechainicio())?$valor->getFechainicio()->format("Y-m-d"):null;
            $proyectoDto->fechaFin=!is_null($valor->getFechafin())?$valor->getFechafin()->format("Y-m-d"):null;
            $proyectoDto->horaestimadas=$valor->getHoraestimadas();
          
            

            $idUserpmovald=0;
            if ($valor->getIdUserPmo()) {
                $idUserpmovald = $valor->getIdUserPmo()->getId();
            }
            //if($valor->getIdUserPmo()->getId()==null){

            if($idUserpmovald==null){
                $proyectoDto->userPmo=null;
            }else{
                $proyectoDto->userPmo=($valor->getIdUserPmo()!=null)?array("id"=>$valor->getIdUserPmo()->getId(),"primerNombre"=>$valor->getIdUserPmo()->getPrimerNombre(),"primerApellido"=>$valor->getIdUserPmo()->getPrimerApellido(), "email"=>$valor->getIdUserPmo()->getEmail()):null;
            }
             
            $proyectoDto->empresa=($valor->getIdempresa()!=null)?array("id"=>$valor->getIdempresa()->getId(),"nombre"=>$valor->getIdempresa()->getNombre()):[];        
            
            $Proyecto = $entityManager->getRepository(Statuscalendarioproyecto::class)->find($valor->getIdstatuscalendarioproyecto());
            //$proyectoDto->idstatuscalendarioproyecto=$Proyecto->getId();
            $proyectoDto->estadoId=$Proyecto->getId();
            $proyectoDto->estado=($Proyecto->getEstado());
            $proyectoDto->color=($Proyecto->getColor());

            if (!$result) {
                if($Proyecto->getId()==3){
                    //Rojo
                    $colorprogress='danger';
                }else if($Proyecto->getId()==4){
                    //Naranja
                    $colorprogress='active';

                }else if($Proyecto->getId()==2){
                    //amarrillo 
                    $colorprogress='warning';
                }else if($Proyecto->getId()==1){
                    //verde
                    $colorprogress='success';
                }
                $dataTotal[]=array("totalprogress"=>0,"colorprogress"=>$colorprogress);
            }



            /* $rolesProyecto=[];
            $recursosProyecto=[];
            if($valor->getRoles()!=null){
                foreach($valor->getRoles()as $roles){
                    $rolesProyecto[]=array("id"=>$roles->getId(),"rol"=>$roles->getDescripcion());
                }
            }   
            $recursoshoras =$entityManager->getRepository(RecursosProyecto::class)->findBy([
                'idproyecto' => $valor->getId()
            ]); */

            /* foreach($recursoshoras as $claveResult=>$recursoshorasdedicadas){
                $recursosProyecto[]=array("id"=>$recursoshorasdedicadas->getIdrecurso()->getId(),
                    "primerNombre"=>$recursoshorasdedicadas->getIdrecurso()->getPrimerNombre(),
                    "primerApellido"=>$recursoshorasdedicadas->getIdrecurso()->getPrimerApellido(),
                    "email"=>$recursoshorasdedicadas->getIdrecurso()->getEmail(),
                    "horasdedicadas"=>$recursoshorasdedicadas->getHorasdedicacion(),
                );
            } */
            /* if($valor->getRecursos()!=null){
                foreach($valor->getRecursos()as $recurso){
                    $recursosProyecto[]=array("id"=>$recurso->getId(),
                    "primerNombre"=>$recurso->getUsers()->getPrimerNombre(),
                    "primerApellido"=>$recurso->getUsers()->getPrimerApellido(),
                    "email"=>$recurso->getUsers()->getEmail());
                }
            }   */
            $Adjuntos=[];
            if($valor->getProyectoAdjuntos()!=null){
                foreach($valor->getProyectoAdjuntos()as $proyectoAdjuntos){
                    $Adjuntos[]=array("id"=>$proyectoAdjuntos->getId(),
                    "user"=>$proyectoAdjuntos->getIdUser()->getPrimerNombre()." ".$proyectoAdjuntos->getIdUser()->getPrimerApellido(),
                    "path"=>$proyectoAdjuntos->getPath());
                }
            }  

            /* $recursosSprings=[];             
            if($valor->getSprings()!=null){
                foreach($valor->getSprings()as $spring){

                    $recursosSprings[]=array("id"=>$spring->getId(),
                    "fechaInicio"=>!is_null($spring->getFechainicio())?$spring->getFechainicio()->format("Y-m-d"):null,
                    "fechaFin"=>!is_null($spring->getFechafin())?$spring->getFechafin()->format("Y-m-d"):null,
                    "nombreSpring"=>$spring-getNombre(),
                    "actividades"=> array($entityManager->getRepository(SprintItem::class)->findItemsByIdSprint($spring->getId())));

                }
            }    */
            
            //$proyectoDto->roles= $rolesProyecto;
            //$proyectoDto->recursos= $recursosProyecto;
            //$proyectoDto->springs= $recursosSprings;
            $proyectoDto->adjuntos=$Adjuntos;
            $proyectoDto->total=$dataTotal;

            $recursosProyecto=[];
            $rolesProyecto=[];
            $Adjuntos=[];
            $dataProyecto[]=$proyectoDto;



            $dataproyecto[]=$proyectoDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$dataproyecto);
 
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
     * buscar promedio horas proyecto spring.
     */
    public function promediohorasproyecto($idspring){
        $entityManager = $this->getEntityManager();
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
        
        return $resltEsfuerzoIdeal;
      //**** hasta aqui **********


    }



/**
     * Lista Proyecto.
     */
    public function findList()
    {
        
        $data= $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $entityManager = $this->getEntityManager();  
        $dataproyecto=array();
        foreach($data as $clave=>$valor){
        
            $sql = " SELECT sum(i.peso) as total FROM `proyecto` a 
            inner join spring b on a.id = b.idproyecto_id 
            inner join sprint_item x on b.id = x.id_spring_id 
            inner join items i on x.id_item_id = i.id_backlog_padre_id where a.id=".$valor->getId().";";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            $dataTotal=array();
            $colorprogress='';
            foreach($result as $claveResult=>$valorResult){
                //$dataTotal[]=array("Total"=>$valorResult["total"] );
                if($valorResult["total"]<=5){
                    //negro
                    $colorprogress='dark';
                }else if($valorResult["total"]>=6 and $valorResult["total"]<=40){
                    //Rojo
                    $colorprogress='danger';
                }else if($valorResult["total"]>=41 and $valorResult["total"]<=70){
                    //Naranja
                    $colorprogress='active';

                }else if($valorResult["total"]>=71 and $valorResult["total"]<=90){
                    //amarrillo 
                    $colorprogress='warning';
                }else if($valorResult["total"]>=91 and $valorResult["total"]<=100){
                    //verde
                    $colorprogress='success';
                }
 
                $dataTotal[]=array("totalprogress"=>!is_null($valorResult["total"])?$valorResult["total"]:"0","colorprogress"=>$colorprogress);
            }   

            $proyectoDto =new ProyectoOutPutDto();
            $proyectoDto->id=$valor->getId();
            $proyectoDto->nombre=$valor->getNombre();
            $proyectoDto->descripcion=$valor->getDescripcion();
            //$proyectoDto->fechainicio=!is_null($valor->getFechainicio())?$valor->getFechainicio()->format("d-m-Y"):null;
            //$proyectoDto->fechafin=!is_null($valor->getFechafin())?$valor->getFechafin()->format("d-m-Y"):null;

            $proyectoDto->fechaInicio=!is_null($valor->getFechainicio())?$valor->getFechainicio()->format("Y-m-d"):null;
            $proyectoDto->fechaFin=!is_null($valor->getFechafin())?$valor->getFechafin()->format("Y-m-d"):null;

            $proyectoDto->horaestimadas=$valor->getHoraestimadas();
                       

            if($valor->getIdUserPmo()==null){
                $proyectoDto->userPmo=null;
            }else{
                $proyectoDto->userPmo=($valor->getIdUserPmo()!=null)?array("id"=>$valor->getIdUserPmo()->getId(),"primerNombre"=>$valor->getIdUserPmo()->getPrimerNombre(),"primerApellido"=>$valor->getIdUserPmo()->getPrimerApellido(), "email"=>$valor->getIdUserPmo()->getEmail()):null;
            }
             
            $proyectoDto->empresa=($valor->getIdempresa()!=null)?array("id"=>$valor->getIdempresa()->getId(),"nombre"=>$valor->getIdempresa()->getNombre()):[];        
            
            $Proyecto = $entityManager->getRepository(Statuscalendarioproyecto::class)->find($valor->getIdstatuscalendarioproyecto());
            $proyectoDto->idstatuscalendarioproyecto=$Proyecto->getId();
            $proyectoDto->estado=($Proyecto->getEstado());
            $proyectoDto->color=($Proyecto->getColor());

            $rolesProyecto=[];
            $recursosProyecto=[];
            if($valor->getRoles()!=null){
                foreach($valor->getRoles()as $roles){
                    $rolesProyecto[]=array("id"=>$roles->getId(),"rol"=>$roles->getDescripcion());
                }
            }   
            $recursoshoras =$entityManager->getRepository(RecursosProyecto::class)->findBy([
                'idproyecto' => $valor->getId()
            ]);
            foreach($recursoshoras as $claveResult=>$recursoshorasdedicadas){
                $recursosProyecto[]=array("id"=>$recursoshorasdedicadas->getIdrecurso()->getId(),
                    "primerNombre"=>$recursoshorasdedicadas->getIdrecurso()->getPrimerNombre(),
                    "primerApellido"=>$recursoshorasdedicadas->getIdrecurso()->getPrimerApellido(),
                    "email"=>$recursoshorasdedicadas->getIdrecurso()->getEmail(),
                    "horasdedicadas"=>$recursoshorasdedicadas->getHorasdedicacion(),
                );
            }
            /* if($valor->getRecursos()!=null){
                foreach($valor->getRecursos()as $recurso){
                    $recursosProyecto[]=array("id"=>$recurso->getId(),
                    "primerNombre"=>$recurso->getUsers()->getPrimerNombre(),
                    "primerApellido"=>$recurso->getUsers()->getPrimerApellido(),
                    "email"=>$recurso->getUsers()->getEmail());
                }
            }   */
            $Adjuntos=[];
            if($valor->getProyectoAdjuntos()!=null){
                foreach($valor->getProyectoAdjuntos()as $proyectoAdjuntos){
                    $Adjuntos[]=array("id"=>$proyectoAdjuntos->getId(),
                    "user"=>$proyectoAdjuntos->getIdUser()->getPrimerNombre()." ".$proyectoAdjuntos->getIdUser()->getPrimerApellido(),
                    "path"=>$proyectoAdjuntos->getPath());
                }
            }  
             
            $recursosSprings=[];
            if($valor->getSprings()!=null){
                foreach($valor->getSprings()as $spring){
                    $recursosSprings[]=array("id"=>$spring->getId(),
                    "fechaInicio"=>!is_null($spring->getFechainicio())?$spring->getFechainicio()->format("Y-m-d"):null,
                    "fechaFin"=>!is_null($spring->getFechafin())?$spring->getFechafin()->format("Y-m-d"):null,
                    "nombreSpring"=>$spring->getNombre(),
                    "actividades"=> array($entityManager->getRepository(SprintItem::class)->findItemsByIdSprint($spring->getId())));
                }
            }   
            
            $proyectoDto->roles= $rolesProyecto;
            $proyectoDto->recursos= $recursosProyecto;
            $proyectoDto->springs= $recursosSprings;
            $proyectoDto->adjuntos=$Adjuntos;
            $proyectoDto->total=$dataTotal;

            $recursosProyecto=[];
            $rolesProyecto=[];
            $Adjuntos=[];
            $dataProyecto[]=$proyectoDto;



            $dataproyecto[]=$proyectoDto;





        }
       return array("data"=>$dataproyecto);
 
    }


     /**
     * Create Proyecto.
     */
    public function post($data,$validator,$helper): JsonResponse  {

        //var_dump($data);die;

        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new Proyecto(),$data);

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setCreateBy($currentUser->getUserName());
            //$entity->setHoraestimadas(intval($data['horaestimadas']));

            //$entity->setHoraestimadas(180);


            //var_dump($data['horaestimadas']);die;

            //$entity->setIdStatus($entityManager->getRepository(Status::class)->find(1)); 
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);
            $entityManager->persist($entity);
            $entityManager->flush();
            $data=array("tipoEntidad"=>"Proyecto","idEntidad"=>$entity->getId(),"createdBy"=>$currentUser->getId(),"sqlInstruction"=>"update","accion"=>1);
            $this->traza->post($data,$validator,$helper);

            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }

    /**
     * Update Proyecto.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Proyecto::class)->find($id);
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
            $uow = $entityManager->getUnitOfWork();
            $uow->computeChangeSets(); // do not compute changes if inside a listener
            $changeset = $uow->getEntityChangeSet($entity);
            var_dump($changeset);
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }

    }

        
    /**
     * Update User Pmo Proyecto.
     */
    public function postUserPmo($data,$validator,$helper): JsonResponse  
    {
        $userPmoId = $data['userPmoId'];
        $entityManager = $this->getEntityManager();
        if (!$data['projectId']) {
            return new JsonResponse(['msg'=>'La projectId no puede estar en blanco'],200);  
        } 
        if (!$data['userPmoId']) {
            return new JsonResponse(['msg'=>'La userPmoId no puede estar en blanco'],200);  
        } 
        $entity =$entityManager->getRepository(Proyecto::class)->find($data['projectId']);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen projectId: '.$data['projectId']],404);  
        }
        $entity=$helper->setParametersToEntity($entity,$data);
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity->setUpdateBy($currentUser->getUserName());
        $entity->setUpdateAt(new \DateTime());
        $currentUserPmo =$entityManager->getRepository(User::class)->find($data["userPmoId"]);
        $entity->setIdUserPmo($currentUserPmo);
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

            $data=array("tipoEntidad"=>"Activar PMO","idEntidad"=>$entity->getId(),"createdBy"=>$currentUser->getId(),"sqlInstruction"=>"update","accion"=>2);
            $this->traza->post($data,$validator,$helper);
           return new JsonResponse(['msg'=>'Registro Actualizado: '.$userPmoId],200);
        }

    }

    
    
    public function getProyectPmoById($projectId,$url,$validator,$helper){

        $entityManager = $this->getEntityManager();
        if (!$projectId) {
            return new JsonResponse(['msg'=>'La projectId no puede estar en blanco'],200);  
        } 
        $entity =$entityManager->getRepository(Proyecto::class)->find($projectId);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen projectId: '.$projectId],404);  
        }
        //$entity=$helper->setParametersToEntity($entity,$data);
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
            $entityManager->persist($entity);
            $entityManager->flush();
            $sql2 = "update proyecto set id_user_pmo_id=NULL where id=".$projectId."";
            $conn2 = $this->getEntityManager()->getConnection();
            $stmt2 = $conn2->prepare($sql2);
            $stmt2->execute();
            $data=array("tipoEntidad"=>"Eliminar PMO","idEntidad"=>$entity->getId(),"createdBy"=>$currentUser->getId(),"sqlInstruction"=>"update","accion"=>1);
            $this->traza->post($data,$validator,$helper);
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$projectId],200);
        }

    }


    public function getProyectById($id,$url){
        $entityManager = $this->getEntityManager();
        $proyectData= $this->createQueryBuilder('a')
            ->andWhere('a.id='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();



            $sql = " SELECT a.horaestimadas,a.idstatuscalendarioproyecto_id, b.id as idspring FROM `proyecto` a 
            inner join spring b on a.id = b.idproyecto_id 
            inner join sprint_item x on b.id = x.id_spring_id 
            inner join items i on x.id_item_id = i.id_backlog_padre_id where a.id=".$id.";";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            $dataTotal=array();
            $colorprogress='';
            foreach($result as $claveResult=>$valorResult){
                //$dataTotal[]=array("Total"=>$valorResult["total"] );

                $sql3 = " SELECT *  FROM items_horas_trabajadas where idspring=".$valorResult["idspring"]."  order by id ASC"; 
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql3);
                $stmt->execute();
                $dataitetrabajo=$stmt->fetchAll();
                $iditemshorastrabajadas=0;
                $pesoremanente=0;
                $remanente=0;
                $horasreales=0;
                $progreso=0;

                foreach($dataitetrabajo as $clave=>$valoritemhorastrabj){
                    $horasreales = $horasreales  + $valoritemhorastrabj["pesotrabajado"];
                }

                if($horasreales!=0){
                    $progreso = ($horasreales/$valorResult["horaestimadas"])*100;
                    $progreso = number_format($progreso, 2);
                    //$progreso = number_format($progreso, 2, '.', ',');
                }

                if($valorResult["idstatuscalendarioproyecto_id"]==3){
                    //Rojo
                    $colorprogress='danger';
                }else if($valorResult["idstatuscalendarioproyecto_id"]==4){
                    //Naranja
                    $colorprogress='active';

                }else if($valorResult["idstatuscalendarioproyecto_id"]==2){
                    //amarrillo 
                    $colorprogress='warning';
                }else if($valorResult["idstatuscalendarioproyecto_id"]==1){
                    //verde
                    $colorprogress='success';
                }
 
                //$dataTotal[]=array("totalprogress"=>!is_null($valorResult["total"])?$valorResult["total"]:"0","colorprogress"=>$colorprogress);

                $dataTotal[]=array("totalprogress"=>$progreso,"colorprogress"=>$colorprogress);
                break;   
            }    


            /* $sql = " SELECT sum(i.peso) as total FROM `proyecto` a 
            inner join spring b on a.id = b.idproyecto_id 
            inner join sprint_item x on b.id = x.id_spring_id 
            inner join items i on x.id_item_id = i.id_backlog_padre_id where a.id=".$id.";";

            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            $dataTotal=array();
            $colorprogress='';
            foreach($result as $claveResult=>$valorResult){
                //$dataTotal[]=array("Total"=>$valorResult["total"] );
                if($valorResult["total"]<=5){
                    //negro
                    $colorprogress='dark';
                }else if($valorResult["total"]>=6 and $valorResult["total"]<=40){
                    //Rojo
                    $colorprogress='danger';
                }else if($valorResult["total"]>=41 and $valorResult["total"]<=70){
                    //Naranja
                    $colorprogress='active';

                }else if($valorResult["total"]>=71 and $valorResult["total"]<=90){
                    //amarrillo 
                    $colorprogress='warning';
                }else if($valorResult["total"]>=91 and $valorResult["total"]<=100){
                    //verde
                    $colorprogress='success';
                }
 
                $dataTotal[]=array("totalprogress"=>!is_null($valorResult["total"])?$valorResult["total"]:"0","colorprogress"=>$colorprogress);
            } */   
            

        $dataProyecto=array();
        foreach($proyectData as $clave=>$valor){
            $proyectoDto =new ProyectoOutPutDto();
            $proyectoDto->id=$valor->getId();
            $proyectoDto->nombre=$valor->getNombre();
            $proyectoDto->descripcion=$valor->getDescripcion();

            $proyectoDto->fechaInicio=!is_null($valor->getFechainicio())?$valor->getFechainicio()->format("Y-m-d"):null;
            $proyectoDto->fechaFin=!is_null($valor->getFechafin())?$valor->getFechafin()->format("Y-m-d"):null;

            $proyectoDto->horaestimadas=$valor->getHoraestimadas();

            //$proyectoDto->fechainicio=!is_null($valor->getFechainicio())?$valor->getFechainicio():null;
            //$proyectoDto->fechafin=!is_null($valor->getFechafin())?$valor->getFechafin()->format("d-m-Y"):null;

            $currentUser =$entityManager->getRepository(User::class)->find(48);

           /*  if($valor->getIdUserPmo()==null){
              $iduserpmo=0;
            }else{
                $iduserpmo =$valor->getIdUserPmo()->getId();
            }
            
         if($iduserpmo>0){
            $query =  $entityManager->createQueryBuilder();
            $allAppointmentsQuery = $query->select('user.id,user.foto,user.sexo')
            ->from(User::class,'user') 
            ->Where('user.id='.$valor->getIdUserPmo()->getId())
            //->addOrderBy('id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $data =  $queryult->execute();
            foreach($data as $clave=>$currentUser){
                $dime = $currentUser["foto"];

                if(!is_null($currentUser["foto"])){
                    //$stringFoto=$url.'\fotos\\'.$valor->getFoto();
                    $stringFoto=$url.$currentUser["foto"];
                    $stringFoto = filter_var($stringFoto, FILTER_SANITIZE_URL);
                    $stringFoto = str_replace('\\', '', $stringFoto);
               }
                if($currentUser["sexo"]=="M"){
                    $proyectoDto->userPmo=($valor->getIdUserPmo()!=null)?array("id"=>$valor->getIdUserPmo()->getId(),"primerNombre"=>$valor->getIdUserPmo()->getPrimerNombre(),"primerApellido"=>$valor->getIdUserPmo()->getPrimerApellido(), "email"=>$valor->getIdUserPmo()->getEmail(),"foto"=>(is_null($currentUser["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_masculino.png'):$this->assetPackage->getUrl($stringFoto)):null;
                }else{
                    $proyectoDto->userPmo=($valor->getIdUserPmo()!=null)?array("id"=>$valor->getIdUserPmo()->getId(),"primerNombre"=>$valor->getIdUserPmo()->getPrimerNombre(),"primerApellido"=>$valor->getIdUserPmo()->getPrimerApellido(), "email"=>$valor->getIdUserPmo()->getEmail(),"foto"=>(is_null($currentUser["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_femenino.png'):$this->assetPackage->getUrl($stringFoto)):null;
                }

            }
         }
 */
            //$currentUser =$entityManager->getRepository(User::class)->find($valor->getIdUserPmo()->getId());
            

            
             
            $proyectoDto->empresa=($valor->getIdempresa()!=null)?array("id"=>$valor->getIdempresa()->getId(),"nombre"=>$valor->getIdempresa()->getNombre()):[];        
            
            $Proyecto = $entityManager->getRepository(Statuscalendarioproyecto::class)->find($valor->getIdstatuscalendarioproyecto());
            $proyectoDto->estadoId=$Proyecto->getId();
            $proyectoDto->estado=($Proyecto->getEstado());
            $proyectoDto->color=($Proyecto->getColor());

            if (!$result) {
                if($Proyecto->getId()==3){
                    //Rojo
                    $colorprogress='danger';
                }else if($Proyecto->getId()==4){
                    //Naranja
                    $colorprogress='active';

                }else if($Proyecto->getId()==2){
                    //amarrillo 
                    $colorprogress='warning';
                }else if($Proyecto->getId()==1){
                    //verde
                    $colorprogress='success';
                }
                $dataTotal[]=array("totalprogress"=>0,"colorprogress"=>$colorprogress);
            }


            $rolesProyecto=[];
            $recursosProyecto=[];
            if($valor->getRoles()!=null){
                foreach($valor->getRoles()as $roles){
                    $rolesProyecto[]=array("id"=>$roles->getId(),"rol"=>$roles->getDescripcion());
                }
            }   
            /* $recursoshoras =$entityManager->getRepository(RecursosProyecto::class)->findBy([
                'idproyecto' => $valor->getId()
            ]);
            foreach($recursoshoras as $claveResult=>$recursoshorasdedicadas){

                $query =  $entityManager->createQueryBuilder();
                $allAppointmentsQuery = $query->select('user.id,user.foto,user.sexo')
                ->from(User::class,'user') 
                ->Where('user.id='.$recursoshorasdedicadas->getIdrecurso()->getId())
                //->addOrderBy('id', 'ASC')
                ->getQuery();
                $queryult = $query->getQuery();
                $data =  $queryult->execute();
                $fotos='';
                foreach($data as $clave=>$currentUser){
                    $dime = $currentUser["foto"];
    
                    if(!is_null($currentUser["foto"])){
                        //$stringFoto=$url.'\fotos\\'.$valor->getFoto();
                        $stringFoto=$url.$currentUser["foto"];
                        $stringFoto = filter_var($stringFoto, FILTER_SANITIZE_URL);
                        $stringFoto = str_replace('\\', '', $stringFoto);
                   }
                    if($currentUser["sexo"]=="M"){
                        $fotos=($valor->getIdUserPmo()!=null)?(is_null($currentUser["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_masculino.png'):$this->assetPackage->getUrl($stringFoto):null;
                    }else{
                        $fotos=($valor->getIdUserPmo()!=null)?(is_null($currentUser["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_femenino.png'):$this->assetPackage->getUrl($stringFoto):null;
                    }
    
                }

                $recursosProyecto[]=array("id"=>$recursoshorasdedicadas->getIdrecurso()->getId(),
                    "primerNombre"=>$recursoshorasdedicadas->getIdrecurso()->getPrimerNombre(),
                    "primerApellido"=>$recursoshorasdedicadas->getIdrecurso()->getPrimerApellido(),
                    "email"=>$recursoshorasdedicadas->getIdrecurso()->getEmail(),
                    "horasdedicadas"=>$recursoshorasdedicadas->getHorasdedicacion(),
                    "foto"=>$fotos,
                );
            } */


            /* if($valor->getRecursos()!=null){
                foreach($valor->getRecursos()as $recurso){
                    $recursosProyecto[]=array("id"=>$recurso->getId(),
                    "primerNombre"=>$recurso->getUsers()->getPrimerNombre(),
                    "primerApellido"=>$recurso->getUsers()->getPrimerApellido(),
                    "email"=>$recurso->getUsers()->getEmail());
                }
            }   */
            $Adjuntos=[];
           /*  if($valor->getProyectoAdjuntos()!=null){
                foreach($valor->getProyectoAdjuntos()as $proyectoAdjuntos){
                    $Adjuntos[]=array("id"=>$proyectoAdjuntos->getId(),
                    "user"=>$proyectoAdjuntos->getIdUser()->getPrimerNombre()." ".$proyectoAdjuntos->getIdUser()->getPrimerApellido(),
                    "path"=>$proyectoAdjuntos->getPath());
                }
            }  */ 
            
            $recursosSprings=[];
            /* if($valor->getSprings()!=null){
                foreach($valor->getSprings()as $spring){

                   // var_dump($spring);
                    $recursosSprings[]=array("id"=>$spring->getId(),
                    "fechaInicio"=>!is_null($spring->getFechainicio())?$spring->getFechainicio()->format("Y-m-d"):null,
                    "fechaFin"=>!is_null($spring->getFechafin())?$spring->getFechafin()->format("Y-m-d"):null,
                    "nombreSpring"=>$spring-getNombre(),
                    "actividades"=> array($entityManager->getRepository(SprintItem::class)->findItemsByIdSprint($spring->getId())));

                }
            }    */
            
            $proyectoDto->roles= $rolesProyecto;
            $proyectoDto->recursos= $recursosProyecto;
            $proyectoDto->springs= $recursosSprings;
            $proyectoDto->adjuntos=$Adjuntos;
            $proyectoDto->total=$dataTotal;

            $recursosProyecto=[];
            $rolesProyecto=[];
            $Adjuntos=[];
            $dataProyecto[]=$proyectoDto;
        }
        return $dataProyecto;
 
    }



    public function getProyectPmoRecursosById($id,$url,$Calculos){
        $entityManager = $this->getEntityManager();
        $proyectData= $this->createQueryBuilder('a')
            ->andWhere('a.id='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
            $sql = " SELECT sum(i.peso) as total FROM `proyecto` a 
            inner join spring b on a.id = b.idproyecto_id 
            inner join sprint_item x on b.id = x.id_spring_id 
            inner join items i on x.id_item_id = i.id_backlog_padre_id where a.id=".$id.";";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            $dataTotal=array();
            $colorprogress='';
        $dataProyecto=array();
        foreach($proyectData as $clave=>$valor){
            $proyectoDto =new ProyectoOutPutDto();
            $proyectoDto->id=$valor->getId();
            
            //$iduserpmo =$valor->getIdUserPmo()->getId();
            
            if($valor->getIdUserPmo()==null){
                $iduserpmo=0;
            }else{
                $iduserpmo =$valor->getIdUserPmo()->getId();
            

            $query =  $entityManager->createQueryBuilder();
            $allAppointmentsQuery = $query->select('user.id,user.foto,user.sexo')
            ->from(User::class,'user') 
            ->Where('user.id='.$valor->getIdUserPmo()->getId())
            //->addOrderBy('id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $data =  $queryult->execute();
            foreach($data as $clave=>$currentUser){
                $dime = $currentUser["foto"];

                if(!is_null($currentUser["foto"])){
                    //$stringFoto=$url.'\fotos\\'.$valor->getFoto();
                    $stringFoto=$url.$currentUser["foto"];
                    $stringFoto = filter_var($stringFoto, FILTER_SANITIZE_URL);
                    $stringFoto = str_replace('\\', '', $stringFoto);
               }
                if($currentUser["sexo"]=="M"){
                    $proyectoDto->userPmo=($valor->getIdUserPmo()!=null)?array("id"=>$valor->getIdUserPmo()->getId(),"primerNombre"=>$valor->getIdUserPmo()->getPrimerNombre(),"primerApellido"=>$valor->getIdUserPmo()->getPrimerApellido(), "email"=>$valor->getIdUserPmo()->getEmail(),"foto"=>(is_null($currentUser["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_masculino.png'):$this->assetPackage->getUrl($stringFoto)):null;
                }else{
                    $proyectoDto->userPmo=($valor->getIdUserPmo()!=null)?array("id"=>$valor->getIdUserPmo()->getId(),"primerNombre"=>$valor->getIdUserPmo()->getPrimerNombre(),"primerApellido"=>$valor->getIdUserPmo()->getPrimerApellido(), "email"=>$valor->getIdUserPmo()->getEmail(),"foto"=>(is_null($currentUser["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_femenino.png'):$this->assetPackage->getUrl($stringFoto)):null;
                }

            }
           }
            
            $recursosProyecto=[];
            //$recursoshoras =$entityManager->getRepository(RecursosProyecto::class)->findBy(['idproyecto' => $valor->getId()]);
            $recursoshoras =$entityManager->getRepository(RecursosProyecto::class)->findBy(['idproyecto' => $valor->getId(),'swact' => 1]);
            foreach($recursoshoras as $claveResult=>$recursoshorasdedicadas){
                $query =  $entityManager->createQueryBuilder();
                $allAppointmentsQuery = $query->select('user.id,user.foto,user.sexo')
                ->from(User::class,'user') 
                ->Where('user.id='.$recursoshorasdedicadas->getIdrecurso()->getId())
                //->addOrderBy('id', 'ASC')
                ->getQuery();
                $queryult = $query->getQuery();
                $data =  $queryult->execute();
                $fotos='';
                foreach($data as $clave=>$currentUser){
                    $dime = $currentUser["foto"];
    
                    if(!is_null($currentUser["foto"])){
                        //$stringFoto=$url.'\fotos\\'.$valor->getFoto();
                        $stringFoto=$url.$currentUser["foto"];
                        $stringFoto = filter_var($stringFoto, FILTER_SANITIZE_URL);
                        $stringFoto = str_replace('\\', '', $stringFoto);
                   }
                    if($currentUser["sexo"]=="M"){
                        $fotos=($currentUser["foto"]!=null)?(is_null($currentUser["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_masculino.png'):$this->assetPackage->getUrl($stringFoto):null;
                    }else{
                        $fotos=($currentUser["foto"]!=null)?(is_null($currentUser["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_femenino.png'):$this->assetPackage->getUrl($stringFoto):null;
                    }
    
                }

                $idRecursosProyecto = $recursoshorasdedicadas->getId();
                $totdias=0;
                $queryresp2 = $entityManager->createQueryBuilder();
                $allAppointmentsQuery = $queryresp2->select('calendario_recursos_proyecto')
                ->from(CalendarioRecursosProyecto::class,'calendario_recursos_proyecto')
                ->where("calendario_recursos_proyecto.idrecursosproyecto =$idRecursosProyecto and calendario_recursos_proyecto.swactivo=1")
                ->getQuery();
                $queryrespdata2 = $queryresp2->getQuery();
                $resultrc =  $queryrespdata2->execute();
                $dataRecursos=array();
                foreach($resultrc as $claveResult=>$valorResult){
                    $fech_fincompara1 = strtotime($valorResult->getFechainicio()->format("Y-m-d"));
                    $startDate = date('Y-m-d', $fech_fincompara1);
                    $fech_fincompara2 = strtotime($valorResult->getFechafin()->format("Y-m-d"));
                    $endDate = date('Y-m-d', $fech_fincompara2);
                    $Tiempdifrute= $Calculos->dias_pasados_sin_fin_semana($startDate,$endDate);
                    $totdias = $totdias + $Tiempdifrute;
                }   

                $recursosProyecto[]=array("id"=>$recursoshorasdedicadas->getIdrecurso()->getId(),
                    "primerNombre"=>$recursoshorasdedicadas->getIdrecurso()->getPrimerNombre(),
                    "primerApellido"=>$recursoshorasdedicadas->getIdrecurso()->getPrimerApellido(),
                    "email"=>$recursoshorasdedicadas->getIdrecurso()->getEmail(),
                    "horasdedicadas"=>$recursoshorasdedicadas->getHorasdedicacion(),
                    "foto"=>$fotos,"totaldias"=>$totdias
                );
            }
            
            $proyectoDto->recursos= $recursosProyecto;
            $recursosProyecto=[];
            $dataProyecto[]=$proyectoDto;
        }
        return $dataProyecto;
 
    }

   
    public function getProyectspringById($id){
        $entityManager = $this->getEntityManager();
            $sql = "SELECT a.id,b.fechainicio as fechainiciospring,b.fechafin as fechafinspring,b.nroiteracion,b.id,x.id
            FROM proyecto a inner join spring b on a.id = b.idproyecto_id 
            inner join sprint_item x on b.id = x.id_spring_id where b.id=".$id.";";


            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            //cuanto registro hay
             $cunat = count($result);

            $dataTotal=array();
            $dataProyecto=array();
            $fechafinsprint=array();
            $recursosSprings=[];
            foreach($result as $claveResult=>$valorResult){
                $proyectoDto =new ProyectoOutPutDto();
                $sql = " SELECT sum(i.peso) as total FROM `proyecto` a 
                inner join spring b on a.id = b.idproyecto_id 
                inner join sprint_item x on b.id = x.id_spring_id 
                inner join items i on x.id_item_id = i.id_backlog_padre_id where b.id=".$id.";";

                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $resulttotal= $stmt->fetchAll();
                $dataTotal=array();
                $colorprogress='';
                foreach($resulttotal as $claveResult=>$valorResultotal){
                    //$dataTotal[]=array("Total"=>$valorResult["total"] );
                    if($valorResultotal["total"]<=5){
                        //negro
                        $colorprogress='dark';
                    }else if($valorResultotal["total"]>=6 and $valorResultotal["total"]<=40){
                        //Rojo
                        $colorprogress='danger';
                    }else if($valorResultotal["total"]>=41 and $valorResultotal["total"]<=70){
                        //Naranja
                        $colorprogress='active';
    
                    }else if($valorResultotal["total"]>=71 and $valorResultotal["total"]<=90){
                        //amarrillo 
                        $colorprogress='warning';
                    }else if($valorResultotal["total"]>=91 and $valorResultotal["total"]<=100){
                        //verde
                        $colorprogress='success';
                    }
     
                    $dataTotal[]=array("totalprogress"=>!is_null($valorResultotal["total"])?$valorResultotal["total"]:"0","colorprogress"=>$colorprogress);
                }   
                
                
                if($valorResult!=null){
                    $recursosSprings[]=array("id"=>$valorResult["id"],
                    "fechaInicio"=>!is_null($valorResult["fechainiciospring"])?$valorResult["fechainiciospring"]:null,
                    "fechaFin"=>!is_null($valorResult["fechafinspring"])?$valorResult["fechafinspring"]:null,
                    "iteraccion"=>$valorResult["nroiteracion"],
                    "actividades"=> array($entityManager->getRepository(SprintItem::class)->findItemsByIdSprint($valorResult["id"])),
                    "total"=>$dataTotal);
                    $fechafinsprint=array("fechaFin"=>!is_null($valorResult["fechafinspring"])?$valorResult["fechafinspring"]:null);  
                }
                  
            }   
            return (['spring'=>$recursosSprings,"fechaFinSpring"=>$fechafinsprint]);
    }

    
    public function findSpringRecursos($data,$idspring,$validator,$helper ){
       
        if ($data['idrecurso'] == 0) {
            $idrecurso='';
        }else{        
            $idrecurso=$data['idrecurso'];
        }

        /* $idrecurso=0;
        if(strtolower($idrecurso)=='null'){
            $idrecurso='';
        }elseif(strtolower($idrecurso)==0){    
            $idrecurso='';
        } */

         //var_dump($idrecurso);die;

        $entityManager = $this->getEntityManager();
        /* $proyectData= $this->createQueryBuilder('a')
            ->andWhere('a.id='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult(); */

            $entity= $this->getEntityManager()->createQueryBuilder();
            $proyectData= $entity->select("p,b")
                ->from("App\Entity\Proyecto\Proyecto","p")
                ->innerJoin('p.springs', 'b')
                ->where("b.id ='".$idspring."'")
                ->getQuery()
                ->getResult();

            //var_dump($proyectData); die;

            /* $sql = " SELECT sum(i.peso) as total FROM `proyecto` a 
            inner join spring b on a.id = b.idproyecto_id 
            inner join sprint_item x on b.id = x.id_spring_id 
            inner join items i on x.id_item_id = i.id_backlog_padre_id where a.id=".$id.";"; */

            $sql = " SELECT sum(i.peso) as total FROM `proyecto` a 
            inner join spring b on a.id = b.idproyecto_id 
            inner join sprint_item x on b.id = x.id_spring_id 
            inner join items i on x.id_item_id = i.id_backlog_padre_id where b.id=".$idspring.";"; 



            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            $dataTotal=array();
            $colorprogress='';
            foreach($result as $claveResult=>$valorResult){
                //$dataTotal[]=array("Total"=>$valorResult["total"] );
                if($valorResult["total"]<=5){
                    //negro
                    $colorprogress='dark';
                }else if($valorResult["total"]>=6 and $valorResult["total"]<=40){
                    //Rojo
                    $colorprogress='danger';
                }else if($valorResult["total"]>=41 and $valorResult["total"]<=70){
                    //Naranja
                    $colorprogress='active';

                }else if($valorResult["total"]>=71 and $valorResult["total"]<=90){
                    //amarrillo 
                    $colorprogress='warning';
                }else if($valorResult["total"]>=91 and $valorResult["total"]<=100){
                    //verde
                    $colorprogress='success';
                }
 
                $dataTotal[]=array("totalprogress"=>!is_null($valorResult["total"])?$valorResult["total"]:"0","colorprogress"=>$colorprogress);
            }   
               

        $dataProyecto=array();
        foreach($proyectData as $clave=>$valor){

            //var_dump($valor); die;

            $proyectoDto =new ProyectoOutPutDto();
            $proyectoDto->id=$valor->getId();
            $proyectoDto->nombre=$valor->getNombre();
            $proyectoDto->descripcion=$valor->getDescripcion();

            $proyectoDto->fechaInicio=!is_null($valor->getFechainicio())?$valor->getFechainicio()->format("Y-m-d"):null;
            $proyectoDto->fechaFin=!is_null($valor->getFechafin())?$valor->getFechafin()->format("Y-m-d"):null;

            $proyectoDto->horaestimadas=$valor->getHoraestimadas();

            //$proyectoDto->fechainicio=!is_null($valor->getFechainicio())?$valor->getFechainicio():null;
            //$proyectoDto->fechafin=!is_null($valor->getFechafin())?$valor->getFechafin()->format("d-m-Y"):null;

            $proyectoDto->userPmo=($valor->getIdUserPmo()!=null)?array("id"=>$valor->getIdUserPmo()->getId(),"primerNombre"=>$valor->getIdUserPmo()->getPrimerNombre(),"primerApellido"=>$valor->getIdUserPmo()->getPrimerApellido(), "email"=>$valor->getIdUserPmo()->getEmail()):null;
             
            $proyectoDto->empresa=($valor->getIdempresa()!=null)?array("id"=>$valor->getIdempresa()->getId(),"nombre"=>$valor->getIdempresa()->getNombre()):[];        
            
            $Proyecto = $entityManager->getRepository(Statuscalendarioproyecto::class)->find($valor->getIdstatuscalendarioproyecto());
            $proyectoDto->idstatuscalendarioproyecto=$Proyecto->getId();
            $proyectoDto->estado=($Proyecto->getEstado());
            $proyectoDto->color=($Proyecto->getColor());

            $rolesProyecto=[];
            $recursosProyecto=[];
            if($valor->getRoles()!=null){
                foreach($valor->getRoles()as $roles){
                    $rolesProyecto[]=array("id"=>$roles->getId(),"rol"=>$roles->getDescripcion());
                }
            }   
            if($valor->getRecursos()!=null){

                foreach($valor->getRecursos()as $recurso){
               if($idrecurso!=null){
                    if($recurso->getId()==$idrecurso){
                        $recursoshoras =$entityManager->getRepository(RecursosProyecto::class)->findBy([
                            'idproyecto' => $valor->getId()
                        ]);
                        foreach($recursoshoras as $claveResult=>$recursoshorasdedicadas){
                            $recursosProyecto[]=array("id"=>$recursoshorasdedicadas->getIdrecurso()->getId(),
                                "primerNombre"=>$recursoshorasdedicadas->getIdrecurso()->getPrimerNombre(),
                                "primerApellido"=>$recursoshorasdedicadas->getIdrecurso()->getPrimerApellido(),
                                "email"=>$recursoshorasdedicadas->getIdrecurso()->getEmail(),
                                "horasdedicadas"=>$recursoshorasdedicadas->getHorasdedicacion(),
                            );
                        }
                        /* $recursosProyecto[]=array("id"=>$recurso->getId(),
                        "primerNombre"=>$recurso->getUsers()->getPrimerNombre(),
                        "primerApellido"=>$recurso->getUsers()->getPrimerApellido(),
                        "email"=>$recurso->getUsers()->getEmail()); */
                        break;
                    }
               }else{ 
                        $recursoshoras =$entityManager->getRepository(RecursosProyecto::class)->findBy([
                            'idproyecto' => $valor->getId()
                        ]);
                        foreach($recursoshoras as $claveResult=>$recursoshorasdedicadas){
                            $recursosProyecto[]=array("id"=>$recursoshorasdedicadas->getIdrecurso()->getId(),
                                "primerNombre"=>$rech->getPrimerNombre(),
                                "primerApellido"=>$rech->getPrimerApellido(),
                                "email"=>$rech->getEmail(),
                                "horasdedicadas"=>$recursoshorasdedicadas->getHorasdedicacion(),
                            );
                        }
                        /* $recursosProyecto[]=array("id"=>$recurso->getId(),
                        "primerNombre"=>$recurso->getUsers()->getPrimerNombre(),
                        "primerApellido"=>$recurso->getUsers()->getPrimerApellido(),
                        "email"=>$recurso->getUsers()->getEmail()); */
                    }
               }

            }  
            $Adjuntos=[];
            if($valor->getProyectoAdjuntos()!=null){
                foreach($valor->getProyectoAdjuntos()as $proyectoAdjuntos){
                    $Adjuntos[]=array("id"=>$proyectoAdjuntos->getId(),
                    "user"=>$proyectoAdjuntos->getIdUser()->getPrimerNombre()." ".$proyectoAdjuntos->getIdUser()->getPrimerApellido(),
                    "path"=>$proyectoAdjuntos->getPath());
                }
            }  
            
            $recursosSprings=[];
            if($valor->getSprings()!=null){
                foreach($valor->getSprings()as $spring){

                   // var_dump($spring);

                    $recursosSprings[]=array("id"=>$spring->getId(),
                    "fechaInicio"=>!is_null($spring->getFechainicio())?$spring->getFechainicio()->format("Y-m-d"):null,
                    "fechaFin"=>!is_null($spring->getFechafin())?$spring->getFechafin()->format("Y-m-d"):null,
                   "nombreSpring"=>$spring->getNombre(),
                    "actividades"=> array($entityManager->getRepository(SprintItem::class)->findItemsByIdSprint($spring->getId())));

                }
            }   
            
            $proyectoDto->roles= $rolesProyecto;
            $proyectoDto->recursos= $recursosProyecto;
            $proyectoDto->springs= $recursosSprings;
            $proyectoDto->adjuntos=$Adjuntos;
            $proyectoDto->total=$dataTotal;

            $recursosProyecto=[];
            $rolesProyecto=[];
            $Adjuntos=[];
            $dataProyecto[]=$proyectoDto;
        }
        return $dataProyecto;
 
    }

     /**
     * Panel de Tablero Proyectos.
     */

    public function getBoardpanelById($id){
            $entityManager = $this->getEntityManager();
            $sql = " SELECT * FROM `columna_estados` ";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $resulcolumna_estados= $stmt->fetchAll();
            $dataBacklog=array();
            $seccion=[];
            foreach($resulcolumna_estados as $claveResult1=>$valorResulColumna_Estados){
                    $seccion[]= $valorResulColumna_Estados["id_issuestatus"];
            }   

            $sql = "SELECT b.idproyecto_id, b.id as idspring, x.id as idspringitem, x.id_item_id, i.id as iditem, i.id_backlog_padre_id, i.titulo , n.nivelpanel, s.prioridad,
             t.tipodescripcion, s.imagenprioridad, s.prioridad FROM `spring` b inner join sprint_item x on b.id = x.id_spring_id inner join items i on x.id_item_id = i.id 
             inner join nivel_board_panel n on i.idnivelboardpanel_id = n.id inner join statusisuues s on i.idstatusisuues_id = s.id 
             inner join type_event t on i.id_typeevent_id = t.id where b.idproyecto_id=".$id." order by n.nivelpanel asc";

            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            //cuanto registro hay
             $cunat = count($result);

             $dataTotal=array();
             $dataProyecto=array();
             $fechafinsprint=array();

             $general=[];
             $activiades=[];
             $seleccionado=[];
             $enproceso=[];
             $listo=[];
             $contsecc=0;
             $idisues_BACKLOG='IssueStatus.BACKLOG';
             $titleisue_BACKLOG='ACTIVIDADES';
             $idisues_SELECTED='IssueStatus.SELECTED';
             $titleisue_SELECTED='QUE HACER';
             $idisues_IN_PROGRESS='IssueStatus.IN_PROGRESS';
             $titleisue_IN_PROGRESS='EN PROGRESO';
             $idisues_DONE='IssueStatus.DONE';
             $titleisue_DONE='LISTO';

            foreach($result as $claveResult2=>$valorResult){
                foreach($resulcolumna_estados as $claveResult3=>$valorResulColumna_Estados){
                    if($valorResult["nivelpanel"] == $valorResulColumna_Estados["id_issuestatus"]){
                        if($valorResult["nivelpanel"] == "BACKLOG"){
                            $idisues_BACKLOG="IssueStatus.".$valorResult["nivelpanel"];
                            $titleisue_BACKLOG=$valorResulColumna_Estados["title"];
                            $activiades[]=array("id"=>$valorResult["id_item_id"],"id_backlog_padre_id"=>$valorResult["id_backlog_padre_id"],"priorityactivitask"=>$valorResult["prioridad"],"imagepriority"=>$valorResult["imagenprioridad"],"priority"=>"IssuePriority.".$valorResult["prioridad"],"status"=>"IssueStatus.".$valorResult["nivelpanel"],"title"=>$valorResult["titulo"],"type"=>"IssueType.".$valorResult["tipodescripcion"]);
                        }elseif($valorResult["nivelpanel"] == "SELECTED"){
                            $idisues_SELECTED="IssueStatus.".$valorResult["nivelpanel"];
                            $titleisue_SELECTED=$valorResulColumna_Estados["title"];
                            $seleccionado[]=array("id"=>$valorResult["id_item_id"],"id_backlog_padre_id"=>$valorResult["id_backlog_padre_id"],"priorityactivitask"=>$valorResult["prioridad"],"imagepriority"=>$valorResult["imagenprioridad"],"priority"=>"IssuePriority.".$valorResult["prioridad"],"status"=>"IssueStatus.".$valorResult["nivelpanel"],"title"=>$valorResult["titulo"],"type"=>"IssueType.".$valorResult["tipodescripcion"]);
                        }elseif($valorResult["nivelpanel"] == "IN_PROGRESS"){
                            $idisues_IN_PROGRESS="IssueStatus.".$valorResult["nivelpanel"];
                            $titleisue_IN_PROGRESS=$valorResulColumna_Estados["title"];
                            $enproceso[]=array("id"=>$valorResult["id_item_id"],"id_backlog_padre_id"=>$valorResult["id_backlog_padre_id"],"priorityactivitask"=>$valorResult["prioridad"],"imagepriority"=>$valorResult["imagenprioridad"],"priority"=>"IssuePriority.".$valorResult["prioridad"],"status"=>"IssueStatus.".$valorResult["nivelpanel"],"title"=>$valorResult["titulo"],"type"=>"IssueType.".$valorResult["tipodescripcion"]);
                        }elseif($valorResult["nivelpanel"] == "DONE"){
                            $idisues_DONE="IssueStatus.".$valorResult["nivelpanel"];
                            $titleisue_DONE=$valorResulColumna_Estados["title"];
                            $listo[]=array("id"=>$valorResult["id_item_id"],"id_backlog_padre_id"=>$valorResult["id_backlog_padre_id"],"priorityactivitask"=>$valorResult["prioridad"],"imagepriority"=>$valorResult["imagenprioridad"],"priority"=>"IssuePriority.".$valorResult["prioridad"],"status"=>"IssueStatus.".$valorResult["nivelpanel"],"title"=>$valorResult["titulo"],"type"=>"IssueType.".$valorResult["tipodescripcion"]);
                        }
                        break;
                    }
                }   
            }

            $general[]=array("id"=>$idisues_BACKLOG, "title"=>$titleisue_BACKLOG,"issues"=> $activiades);
            $general[]=array("id"=>$idisues_SELECTED, "title"=>$titleisue_SELECTED,"issues"=> $seleccionado);
            $general[]=array("id"=>$idisues_IN_PROGRESS, "title"=>$titleisue_IN_PROGRESS,"issues"=> $enproceso);
            $general[]=array("id"=>$idisues_DONE, "title"=>$titleisue_DONE,"issues"=> $listo);
            //return (['JLane'=>$general]);
            return ($general);
    }


     /**
     * Panel de Tablero Proyectos Actividad.
     */

    public function getBoardpanelactividadById($idproye,$idactividad){
        $entityManager = $this->getEntityManager();
        $sql = " SELECT * FROM `columna_estados` ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resulcolumna_estados= $stmt->fetchAll();
        $dataBacklog=array();
        $seccion=[];
        foreach($resulcolumna_estados as $claveResult1=>$valorResulColumna_Estados){
                $seccion[]= $valorResulColumna_Estados["id_issuestatus"];
        }   

        $sql = "SELECT b.idproyecto_id, b.id as idspring, x.id as idspringitem, x.id_item_id, i.id as iditem, i.id_backlog_padre_id, i.titulo , n.nivelpanel, s.prioridad,
         t.tipodescripcion, s.imagenprioridad, s.prioridad FROM `spring` b inner join sprint_item x on b.id = x.id_spring_id inner join items i on x.id_item_id = i.id 
         inner join nivel_board_panel n on i.idnivelboardpanel_id = n.id inner join statusisuues s on i.idstatusisuues_id = s.id 
         inner join type_event t on i.id_typeevent_id = t.id where b.idproyecto_id=".$idproye." order by n.nivelpanel asc";

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result= $stmt->fetchAll();
        //cuanto registro hay
         $cunat = count($result);

         $dataTotal=array();
         $dataProyecto=array();
         $fechafinsprint=array();

         $general=[];
         $activiades=[];
         $seleccionado=[];
         $enproceso=[];
         $listo=[];
         $contsecc=0;
         $idisues_BACKLOG='IssueStatus.BACKLOG';
         $titleisue_BACKLOG='ACTIVIDADES';
         $idisues_SELECTED='IssueStatus.SELECTED';
         $titleisue_SELECTED='QUE HACER';
         $idisues_IN_PROGRESS='IssueStatus.IN_PROGRESS';
         $titleisue_IN_PROGRESS='EN PROGRESO';
         $idisues_DONE='IssueStatus.DONE';
         $titleisue_DONE='LISTO';

        foreach($result as $claveResult2=>$valorResult){
          if($valorResult["id_item_id"] == $idactividad OR $valorResult["id_backlog_padre_id"] == $idactividad){
            foreach($resulcolumna_estados as $claveResult3=>$valorResulColumna_Estados){
                if($valorResult["nivelpanel"] == $valorResulColumna_Estados["id_issuestatus"]){
                    if($valorResult["nivelpanel"] == "BACKLOG"){
                        $idisues_BACKLOG="IssueStatus.".$valorResult["nivelpanel"];
                        $titleisue_BACKLOG=$valorResulColumna_Estados["title"];
                        $activiades[]=array("id"=>$valorResult["id_item_id"],"id_backlog_padre_id"=>$valorResult["id_backlog_padre_id"],"priorityactivitask"=>$valorResult["prioridad"],"imagepriority"=>$valorResult["imagenprioridad"],"priority"=>"IssuePriority.".$valorResult["prioridad"],"status"=>"IssueStatus.".$valorResult["nivelpanel"],"title"=>$valorResult["titulo"],"type"=>"IssueType.".$valorResult["tipodescripcion"]);
                    }elseif($valorResult["nivelpanel"] == "SELECTED"){
                        $idisues_SELECTED="IssueStatus.".$valorResult["nivelpanel"];
                        $titleisue_SELECTED=$valorResulColumna_Estados["title"];
                        $seleccionado[]=array("id"=>$valorResult["id_item_id"],"id_backlog_padre_id"=>$valorResult["id_backlog_padre_id"],"priorityactivitask"=>$valorResult["prioridad"],"imagepriority"=>$valorResult["imagenprioridad"],"priority"=>"IssuePriority.".$valorResult["prioridad"],"status"=>"IssueStatus.".$valorResult["nivelpanel"],"title"=>$valorResult["titulo"],"type"=>"IssueType.".$valorResult["tipodescripcion"]);
                    }elseif($valorResult["nivelpanel"] == "IN_PROGRESS"){
                        $idisues_IN_PROGRESS="IssueStatus.".$valorResult["nivelpanel"];
                        $titleisue_IN_PROGRESS=$valorResulColumna_Estados["title"];
                        $enproceso[]=array("id"=>$valorResult["id_item_id"],"id_backlog_padre_id"=>$valorResult["id_backlog_padre_id"],"priorityactivitask"=>$valorResult["prioridad"],"imagepriority"=>$valorResult["imagenprioridad"],"priority"=>"IssuePriority.".$valorResult["prioridad"],"status"=>"IssueStatus.".$valorResult["nivelpanel"],"title"=>$valorResult["titulo"],"type"=>"IssueType.".$valorResult["tipodescripcion"]);
                    }elseif($valorResult["nivelpanel"] == "DONE"){
                        $idisues_DONE="IssueStatus.".$valorResult["nivelpanel"];
                        $titleisue_DONE=$valorResulColumna_Estados["title"];
                        $listo[]=array("id"=>$valorResult["id_item_id"],"id_backlog_padre_id"=>$valorResult["id_backlog_padre_id"],"priorityactivitask"=>$valorResult["prioridad"],"imagepriority"=>$valorResult["imagenprioridad"],"priority"=>"IssuePriority.".$valorResult["prioridad"],"status"=>"IssueStatus.".$valorResult["nivelpanel"],"title"=>$valorResult["titulo"],"type"=>"IssueType.".$valorResult["tipodescripcion"]);
                    }
                    break;
                }
            }   
          }
        }

        $general[]=array("id"=>$idisues_BACKLOG, "title"=>$titleisue_BACKLOG,"issues"=> $activiades);
        $general[]=array("id"=>$idisues_SELECTED, "title"=>$titleisue_SELECTED,"issues"=> $seleccionado);
        $general[]=array("id"=>$idisues_IN_PROGRESS, "title"=>$titleisue_IN_PROGRESS,"issues"=> $enproceso);
        $general[]=array("id"=>$idisues_DONE, "title"=>$titleisue_DONE,"issues"=> $listo);
        //return (['JLane'=>$general]);
        return ($general);
}

     
     
     /**
     * Panel de Tablero Proyectos Spring.
     */
    public function getSpringBoardPanelById($id){
        $entityManager = $this->getEntityManager();
        $sql = "SELECT b.idproyecto_id, b.id as idspring, b.fechainicio, b.fechafin, b.nombre FROM `spring` b  where b.idproyecto_id=".$id." order by b.id asc";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result= $stmt->fetchAll();
        //cuanto registro hay
         $cunat = count($result);
         $springProy=array();
         $isCurrent = 0;
        foreach($result as $claveResult2=>$valorResult){
            $fech_fincompara1 = strtotime($valorResult["fechainicio"]);
            $fech_inicocompara = date('Y-m-d', $fech_fincompara1);
            $fech_fincompara1 = strtotime($fech_inicocompara);
            $fech_fincompara2 = strtotime($valorResult["fechafin"]);
            $fech_fincompara = date('Y-m-d', $fech_fincompara2);
            $fech_fincompara2 = strtotime($fech_fincompara);
            $fecha1 = date("Y-m-d");
            $fecha = strtotime($fecha1);
            if (($fecha >= $fech_fincompara1) && ($fecha <= $fech_fincompara2)){
                $isCurrent = 1;
            }else{
                if (($fech_fincompara1 > $fecha)){
                      $isCurrent = 'NULL';
                }else{
                  $isCurrent = 0;
                }
            }

            $springProy[]=array("id"=>$valorResult["idspring"],"fechaInicio"=>$valorResult["fechainicio"],"fechaFin"=>$valorResult["fechafin"],"nombreSpring"=>$valorResult["nombre"],"idProyecto"=>$valorResult["idproyecto_id"],"isCurrent"=>$isCurrent);
        }
        return ($springProy);
}

     



     /**
     * Panel de Tablero Proyectos BACKLOG.
     */

    public function getBoardpanelBackLogbyId($id){
        $entityManager = $this->getEntityManager();
        $sql = " SELECT * FROM `columna_estados` ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resulcolumna_estados= $stmt->fetchAll();
        $dataBacklog=array();
        $seccion=[];
        foreach($resulcolumna_estados as $claveResult1=>$valorResulColumna_Estados){
                $seccion[]= $valorResulColumna_Estados["id_issuestatus"];
        }   

        $sql = "SELECT b.idproyecto_id, b.id as idspring, x.id as idspringitem, x.id_item_id, i.id as iditem, i.id_backlog_padre_id, i.titulo , n.nivelpanel, s.prioridad,
         t.tipodescripcion, s.imagenprioridad, s.prioridad FROM `spring` b inner join sprint_item x on b.id = x.id_spring_id inner join items i on x.id_item_id = i.id 
         inner join nivel_board_panel n on i.idnivelboardpanel_id = n.id inner join statusisuues s on i.idstatusisuues_id = s.id 
         inner join type_event t on i.id_typeevent_id = t.id where b.idproyecto_id=".$id." order by n.nivelpanel asc";

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result= $stmt->fetchAll();
        //cuanto registro hay
         $cunat = count($result);

         $dataTotal=array();
         $dataProyecto=array();
         $fechafinsprint=array();

         $general=[];
         $activiades=[];
         $seleccionado=[];
         $enproceso=[];
         $listo=[];
         $contsecc=0;
         $idisues_BACKLOG='IssueStatus.BACKLOG';
         $titleisue_BACKLOG='ACTIVIDADES';
         $idisues_SELECTED='IssueStatus.SELECTED';
         $titleisue_SELECTED='QUE HACER';
         $idisues_IN_PROGRESS='IssueStatus.IN_PROGRESS';
         $titleisue_IN_PROGRESS='EN PROGRESO';
         $idisues_DONE='IssueStatus.DONE';
         $titleisue_DONE='LISTO';

        foreach($result as $claveResult2=>$valorResult){
            foreach($resulcolumna_estados as $claveResult3=>$valorResulColumna_Estados){
                if($valorResult["nivelpanel"] == $valorResulColumna_Estados["id_issuestatus"]){
                    if($valorResult["nivelpanel"] == "BACKLOG"){
                        $idisues_BACKLOG="IssueStatus.".$valorResult["nivelpanel"];
                        $titleisue_BACKLOG=$valorResulColumna_Estados["title"];
                        $activiades[]=array("id"=>$valorResult["id_item_id"],"id_backlog_padre_id"=>$valorResult["id_backlog_padre_id"],"priorityactivitask"=>$valorResult["prioridad"],"imagepriority"=>$valorResult["imagenprioridad"],"priority"=>"IssuePriority.".$valorResult["prioridad"],"status"=>"IssueStatus.".$valorResult["nivelpanel"],"title"=>$valorResult["titulo"],"type"=>"IssueType.".$valorResult["tipodescripcion"]);
                    }
                    break;
                }
            }   
        }
        $general[]=array("id"=>$idisues_BACKLOG, "title"=>$titleisue_BACKLOG,"issues"=> $activiades);
        return ($general);
}


  /**
    * Panel de Tablero Spring BACKLOG.
  */

    public function getBoardpanelSpringBackLogbyId($id){
        $entityManager = $this->getEntityManager();
        $sql = " SELECT * FROM `columna_estados` ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resulcolumna_estados= $stmt->fetchAll();
        $dataBacklog=array();
        $seccion=[];
        foreach($resulcolumna_estados as $claveResult1=>$valorResulColumna_Estados){
                $seccion[]= $valorResulColumna_Estados["id_issuestatus"];
        }   

        $sql = "SELECT b.idproyecto_id, b.id as idspring, x.id as idspringitem, x.id_item_id, i.id as iditem, i.id_backlog_padre_id, i.titulo , n.nivelpanel, s.prioridad,
         t.tipodescripcion, s.imagenprioridad, s.prioridad FROM `spring` b inner join sprint_item x on b.id = x.id_spring_id inner join items i on x.id_item_id = i.id 
         inner join nivel_board_panel n on i.idnivelboardpanel_id = n.id inner join statusisuues s on i.idstatusisuues_id = s.id 
         inner join type_event t on i.id_typeevent_id = t.id where b.id=".$id." order by n.nivelpanel asc";

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result= $stmt->fetchAll();
        //cuanto registro hay
         $cunat = count($result);

         $dataTotal=array();
         $dataProyecto=array();
         $fechafinsprint=array();

         $general=[];
         $activiades=[];
         $seleccionado=[];
         $enproceso=[];
         $listo=[];
         $contsecc=0;
         $idisues_BACKLOG='IssueStatus.BACKLOG';
         $titleisue_BACKLOG='ACTIVIDADES';
         $idisues_SELECTED='IssueStatus.SELECTED';
         $titleisue_SELECTED='QUE HACER';
         $idisues_IN_PROGRESS='IssueStatus.IN_PROGRESS';
         $titleisue_IN_PROGRESS='EN PROGRESO';
         $idisues_DONE='IssueStatus.DONE';
         $titleisue_DONE='LISTO';

        foreach($result as $claveResult2=>$valorResult){
            foreach($resulcolumna_estados as $claveResult3=>$valorResulColumna_Estados){
                if($valorResult["nivelpanel"] == $valorResulColumna_Estados["id_issuestatus"]){
                    if($valorResult["nivelpanel"] == "BACKLOG"){
                        $idisues_BACKLOG="IssueStatus.".$valorResult["nivelpanel"];
                        $titleisue_BACKLOG=$valorResulColumna_Estados["title"];
                        $activiades[]=array("id"=>$valorResult["id_item_id"],"id_backlog_padre_id"=>$valorResult["id_backlog_padre_id"],"priorityactivitask"=>$valorResult["prioridad"],"imagepriority"=>$valorResult["imagenprioridad"],"priority"=>"IssuePriority.".$valorResult["prioridad"],"status"=>"IssueStatus.".$valorResult["nivelpanel"],"title"=>$valorResult["titulo"],"type"=>"IssueType.".$valorResult["tipodescripcion"]);
                    }
                    break;
                }
            }   
        }
        $general[]=array("id"=>$idisues_BACKLOG, "title"=>$titleisue_BACKLOG,"issues"=> $activiades);
        return ($general);
}



/**
     * Update Estatus Proyecto.
     */
    public function putestatus($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Proyecto::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }

        $sql = "SELECT * FROM `proyecto` p where p.id=".$id." ";
        $conn = $entityManager->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result= $stmt->fetchAll();
        if(count($result)>0){
            $idestatus = $result[0]["idstatuscalendarioproyecto_id"];
        }else{
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404); 
        }

        if($idestatus==3 || $idestatus==4){
            return new JsonResponse(['msg'=>'No se puede cambiar el estado a un proyecto que esta cerrado o finializado'],404); 
        }

        if($idestatus==1 && $data["idstatuscalendarioproyecto"]==2){
            return new JsonResponse(['msg'=>'No se puede cambiar el estado en proceso * nuevo'],404); 
        }

       /*  $aa = $entity->getIdstatuscalendarioproyecto() ;
        foreach($aa as $claveResult2=>$valorResult){
            $aa = $valorResult["id"];

        } */

        $entityestatus =$entityManager->getRepository(Statuscalendarioproyecto::class)->find($data["idstatuscalendarioproyecto"]);
        if (!$entityestatus) {
            return new JsonResponse(['msg'=>'No existen Registros de estatus con el id: '.$data["idstatuscalendarioproyecto"]],404);  
        }

        //$entity=$helper->setParametersToEntity($entity,$data);
        $entity->setIdstatuscalendarioproyecto($entityestatus);

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

}