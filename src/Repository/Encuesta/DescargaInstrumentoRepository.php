<?php

namespace App\Repository\Encuesta;
use App\Entity\Encuesta\Respuesta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


use App\Dto\Encuesta\PreguntaOutPutDto;
use App\Entity\Encuesta\Pregunta;
use App\Entity\Encuesta\TipoCategoria;
use App\Entity\Encuesta\TipoInput;
use App\Entity\Encuesta\InstrumentoCaptura;
use App\Entity\Encuesta\Opciones;
use App\Entity\Encuesta\InstrumentoUsuario;
use App\Entity\User;
use App\Entity\Status;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use Proxies\__CG__\App\Entity\Encuesta\InstrumentoCaptura as EncuestaInstrumentoCaptura;

/**
 * @method Respuesta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Respuesta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Respuesta[]    findAll()
 * @method Respuesta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DescargaInstrumentoRepository extends ServiceEntityRepository
{
    private $security;
    private $totalCount=0;
    private $muestra=0;

    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Respuesta::class);
    }

    
    public function getResult($id)
    {
        $sql = " select count(b.Id) as total,b.Respuesta,c.Pregunta
         from respuestas a inner JOIN opciones_respuesta b on a.id_opcion_respuesta_id = 
        b.Id INNER JOIN preguntas c on b.id_pregunta_id = c.Id 
         GROUP by b.Respuesta,c.Pregunta order by Pregunta,total desc ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function resultados($id)
    {

        $entityManager = $this->getEntityManager();
        $data=[];
        $sqlUser = ' SELECT b.id,b.primer_nombre,b.primer_apellido 
        from instrumento_usuario a inner join user b on a.id_user_id = b.id
        where a.id_instrumento_id='.$id;
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlUser);
        $stmt->execute();
        $dataUser=$stmt->fetchAll();
        foreach($dataUser as $clave=>$valor){
            $dataResultado=[];
            $sql = " SELECT sum(x.puntos) as total,f.nombre FROM `respuesta` a 
            inner join pregunta b on a.id_pregunta_id = b.id inner join opciones x on x.id = a.id_opcion_id
             inner JOIN instrumento_captura c on b.id_instrumento_id = c.id 
            INNER JOIN tipo_categoria f on b.id_categoria_id = f.id
             where  c.id= $id and a.id_user_id=".$valor['id']." GROUP by f.nombre;";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            foreach($result as $claveResult=>$valorResult){
                $dataResultado[]=array("label"=>$valorResult["nombre"],"value"=>$valorResult["total"]);
            }    
            $data["entidades"][]=array("entidad"=>$valor["primer_nombre"]." ".$valor["primer_apellido"], "resultado"=>$dataResultado);
        }
        return $data;

    }

    public function resultadosSinCategorizacion($id)
    {

        $entityManager = $this->getEntityManager();
        $data=[];
        $sqlPregunta = 'SELECT * FROM pregunta
        where id_instrumento_id='.$id;
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlPregunta);
        $stmt->execute();
        $dataPregunta=$stmt->fetchAll();
        foreach($dataPregunta as $clave=>$valor){
            $dataResultado=[];
            $sql = " SELECT count(x.puntos) as total,x.nombre FROM `respuesta` a 
            right join pregunta b on a.id_pregunta_id = b.id right join opciones x on x.id = a.id_opcion_id
             inner JOIN instrumento_captura c on b.id_instrumento_id = c.id 
             where  b.id_instrumento_id=".$valor["id_instrumento_id"]." and b.id=".$valor["id"]." GROUP by x.nombre
             ";

            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            foreach($result as $claveResult=>$valorResult){
                $dataResultado[]=array("label"=>$valorResult["nombre"],"value"=>$valorResult["total"]);
            }    
            $data["entidades"][]=array("entidad"=>$valor["pregunta"], "resultado"=>$dataResultado);
        }
        return $data;

    }


     public function resultadosContadorDeOpciones($id)
    {

        $entityManager = $this->getEntityManager();
        $data=[];
        $sqlUser = ' SELECT b.id,b.primer_nombre,b.primer_apellido 
        from instrumento_usuario a inner join user b on a.id_user_id = b.id
        where a.id_instrumento_id='.$id;
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlUser);
        $stmt->execute();
        $dataUser=$stmt->fetchAll();
        foreach($dataUser as $clave=>$valor){
            $dataResultado=[];
            $sql="SELECT count(x.id) as total,x.valor FROM `respuesta` a 
            inner join pregunta b on a.id_pregunta_id = b.id 
            inner join opciones x on x.id = a.id_opcion_id 
            inner JOIN instrumento_captura c on b.id_instrumento_id = c.id 
            where c.id= $id and a.id_user_id=".$valor['id']." GROUP by x.valor";

            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            foreach($result as $claveResult=>$valorResult){
                $dataResultado[]=array("label"=>$valorResult["valor"],"value"=>$valorResult["total"]);
            }    
            $data["entidades"][]=array("entidad"=>$valor["primer_nombre"]." ".$valor["primer_apellido"], "resultado"=>$dataResultado);
        }
        return $data;

    }


    public function generalResult($param,$id){

        if (!isset ($param['page']) ) {  
            $page = 1;  
        } else {  
            $page = $param['page'];  
        }

        if (!isset($param['rowByPage']) ) {  
            $results_per_page = null;  
        } else {  
            $results_per_page = $param['rowByPage'];  
        }


        $page_first_result = ($page-1) * $results_per_page;  

        $sql="SELECT b.questions_by_category,b.puntos_globales from instrumento_captura b  
            where b.id=". $id;

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $dataCategoria=$stmt->fetchAll();
        $categoria=false;
        $puntosGlobales=false;
        $data=array();
        if($dataCategoria!=null){
            if($dataCategoria[0]["questions_by_category"]==true){
                $categoria=true;
            }
            if($dataCategoria[0]["puntos_globales"]==1){
                $puntosGlobales=true;
            }
        }else{
            $caterogia=false;
        }
        $where =" where c.id=".$id;
        $wherePersona=" where a.id_instrumento_id=".$id;
        if($param["desde"]!=null && $param["hasta"]!=null){
            $where.= " and (r.fecha_inicio BETWEEN '".$param["desde"]." 00:00:00' and '".$param["hasta"]." 23:59:59') ";
            $wherePersona.= " and (a.fecha_inicio BETWEEN '".$param["desde"]." 00:00:00' and '".$param["hasta"]." 23:59:59') ";
        }
        if($param["desde"]!=null && $param["hasta"]==null){
            $where.= " and (r.fecha_inicio >= '".$param["desde"]." 00:00:00')";
            $wherePersona.= " and (a.fecha_inicio >= '".$param["desde"]." 00:00:00')";
        }
        if($param["sexo"]!=null){
            $where.= " and f.sexo = '".$param["sexo"]."'";
            $wherePersona.= " and b.sexo = '".$param["sexo"]."'";
        }

        if($param["pais"]!=null){
            $where.= " and f.pais_id = '".$param["pais"]."'";
            $wherePersona.= " and  b.pais_id = ".$param["pais"];
        }
        if($param["estado"]!=null){
            $where.= " and  f.estado_id = '".$param["estado"]."'";
            $wherePersona.= " and  b.estado_id = ".$param["estado"];

        }
        if($param["ciudad"]!=null){
            $where.= " and  f.ciudad_id = '".$param["ciudad"]."'";
            $wherePersona.= " and  b.ciudad_id = ".$param["ciudad"];
        }

        if($param["word"]!=null){
            $wherePersona.= " and (b.primer_nombre like '%".$param["word"]."%' or
                b.primer_apellido like '%".$param["word"]."%')
            ";
        }


        $especial=false;
        if($id ==3){
           $especial=true; 
           if($param["byuser"]){
                $fields=" sum(x.puntos) as total,x.valor as nombre ";
                $groupby="GROUP by x.valor ";    
           }else{
                $fields=" sum(x.puntos) as total,x.valor as nombre ";
                $groupby="GROUP by x.valor";    
           } 
        }else{
            if($categoria){
                $fields= " sum(x.puntos) as total,m.nombre ";
                $groupby="GROUP by m.nombre";
            }else{
                if($param["byuser"]){
                   $fields=" sum(x.puntos) as total,x.nombre,b.pregunta ";
                   $groupby="GROUP by x.nombre,b.pregunta";     
                }else{
                    $fields=" sum(x.puntos) as total,x.nombre ";
                    $groupby="GROUP by x.nombre";     
 
                }
            }
        }
        $this->getPoblacion($wherePersona);
        if($param["byuser"]==1){
                $data=[];
               // $wherePersona="where a.id_instrumento_id=3 and a.id_user_id BETWEEN 484 and 489";
               $dataUser=$this->getUserByInstrumento($wherePersona,$page,$results_per_page,$page_first_result);
               $dataResultado=array();
               $whereUser="";
            //    $data["count"]=$this->totalCount;
            //    $data["muestra"]=$this->muestra;
               if($puntosGlobales){

                    foreach($dataUser as $clave=>$valor){
                        $whereUser = $where . " and a.id_user_id=".$valor['id'];
                            if(!$categoria){
                                $result=$this->getTotal($whereUser,$fields,$groupby);
                                foreach($result as $claveResult=>$valorResult){
                                    if($especial){
                                        $dataResultado[]=array("entidad"=>$valor["primer_nombre"]." ".$valor["primer_apellido"],
                                        "cedula"=>$valor["numero_documento"],
                                        "pais"=>$valor["pais"],
                                        "estado"=>$valor["estado"],
                                        "ciudad"=>$valor["ciudad"],
                                        "sexo"=>$valor["sexo"],
                                        "fecha_inicio"=>$valor["fecha_inicio"], "label"=>$valorResult["nombre"],"value"=>$valorResult["total"]);                      
                                    }else{
                                        $dataResultado[]=array("entidad"=>$valor["primer_nombre"]." ".$valor["primer_apellido"],
                                        "cedula"=>$valor["numero_documento"],
                                        "pais"=>$valor["pais"],
                                        "estado"=>$valor["estado"],
                                        "ciudad"=>$valor["ciudad"],
                                        "sexo"=>$valor["sexo"],
                                        "fecha_inicio"=>$valor["fecha_inicio"], "Pregunta"=>$valorResult["pregunta"],"Respuesta"=> $valorResult["nombre"],"value"=>$valorResult["total"]);                      
                                    }  
                                }    
                            }else{
                                $result=$this->getTotalByCategoria($whereUser,$fields,$groupby);                        
                                foreach($result as $claveResult=>$valorResult){
                                    $dataResultado[]=array("entidad"=>$valor["primer_nombre"]." ".$valor["primer_apellido"],
                                    "cedula"=>$valor["numero_documento"],
                                    "pais"=>$valor["pais"],
                                    "estado"=>$valor["estado"],
                                    "ciudad"=>$valor["ciudad"],
                                    "sexo"=>$valor["sexo"],
                                    "fecha_inicio"=>$valor["fecha_inicio"], "label"=>$valorResult["nombre"],"value"=>$valorResult["total"]);                        
                                }        
                            }
                           // $data=array("resultado"=>$dataResultado);               
                    } 
                   $data= $dataResultado;                  
                }else{
                    $dataResultado=[];                   
                    foreach($dataUser as $clave=>$valor){
                        // if (!$valor['id']==2037){
                        //     var_dump("Aca",$valor['id_cargo_id']);
                        //     die;
                        //     continue;
                        // }
                        $sql = " SELECT sum(oc.score) as total,h.nombre,h.id FROM `respuesta` a 
                        inner join pregunta b on a.id_pregunta_id = b.id 
                        inner join opciones x on x.id = a.id_opcion_id
                        inner JOIN instrumento_captura c on b.id_instrumento_id = c.id 
                        INNER JOIN tipo_categoria h on b.id_categoria_id = h.id
                        inner join opciones_cargo oc on x.id = oc.opcion_id 
                        inner join instrumento_usuario r  on r.id_user_id = a.id_user_id and r.id_instrumento_id= c.id
                        inner join user f on f.id = r.id_user_id
                        $where and a.id_user_id=".$valor['id']." and oc.id_cargo_id=".$valor['id_cargo_id']." 
                        GROUP by h.nombre,h.id";
                        $conn = $this->getEntityManager()->getConnection();
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result= $stmt->fetchAll();
                        $sumWeightedLevel=0;
                        $sumWeightedRequirement=0;
                        $adecuacyLevel=0;
                        foreach($result as $claveResult=>$valorResult){

                           $sql = " SELECT escala FROM `categoria_cargo_escala` a  
                           where  cargo_id=".$valor['id_cargo_id']." and a.categoria_id=".$valorResult["id"];
                           $stmt = $conn->prepare($sql);
                           $stmt->execute();
                           $resultEscala= $stmt->fetchAll();

                           $sql = " SELECT ponderacion FROM categoria_nivel_ponderacion a where a.categoria_id=".$valorResult["id"]." and nivel_id=
                           (select nivel_id from cargo where id=".$valor['id_cargo_id'].")"; 
                           $stmt = $conn->prepare($sql);
                           $stmt->execute();
                           $resultPonderacion= $stmt->fetchAll();

                            $escala=$resultEscala[0]["escala"]!=null?$resultEscala[0]["escala"]:0;
                            $ponderacion=$resultPonderacion!=null?$resultPonderacion[0]["ponderacion"]:0;
                            $weightedRequirement=($ponderacion*$escala/100);
                            $weightedLevel=($ponderacion*$valorResult["total"])/100;
                            $dataResultado[]=array("entidad"=>$valor["primer_nombre"]." ".$valor["primer_apellido"],
                            "cedula"=>$valor["numero_documento"],
                            "dependencia"=>$valor["dependencia"],
                            "gerencia"=>$valor["gerencia"],
                            "cargo"=>$valor["cargo"],
                            "coordinacion"=>$valor["coordinacion"],
                            "label"=>$valorResult["nombre"],
                            "value"=>$valorResult["total"],
                                "levelRequired"=>$escala,
                                "porcenageWeighing"=>$ponderacion,    
                                "gap"=>$escala-$valorResult["total"],
                                "weightedRequirement"=>$weightedRequirement,
                                "weightedLevel"=>$weightedLevel,
                                "weightPerGap"=>$weightedRequirement-$weightedLevel,
                                "DIF"=>$ponderacion*$escala!=0?round(1-($weightedLevel/$weightedRequirement),2):0
                            );
                            $sumWeightedLevel+=$ponderacion*$valorResult["total"];
                            $sumWeightedRequirement+=$ponderacion*$escala;
                            if($sumWeightedRequirement>0)
                                $adecuacyLevel=$sumWeightedLevel/$sumWeightedRequirement;
                        }    
                        
                        // $data["entidades"][]=array("entidad"=>$valor["primer_nombre"]." ".$valor["primer_apellido"],
                        // "pais"=>$valor["pais"],
                        // "estado"=>$valor["estado"],
                        // "ciudad"=>$valor["ciudad"],
                        // "sexo"=>$valor["sexo"],
                        // "fecha_inicio"=>$valor["fecha_inicio"],
                        // "dependencia"=>$valor["dependencia"],
                        // "gerencia"=>$valor["gerencia"],
                        // "cargo"=>$valor["cargo"],
                        // "coordinacion"=>$valor["coordinacion"],
                        // "resultado"=>$dataResultado,
                        // "totales"=>array("adecuacyLevel"=>$adecuacyLevel));
                    }
                    $data=$dataResultado;
                }
        }elseif($param["byuser"]==2){
            $sql = "select * from estructura_organizativa";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $dependencias=$stmt->fetchAll();
            $whereCargo= " where 1=1";
            if(isset($param["word"])){
                $whereCargo= " where descripcion like '%".$param["word"]."%'";
            }

            foreach($dependencias as $claveDependencia=>$valorDepedencia){
                
                
                $wherePersona = " where 1=1";
                $sql = "select * from cargo $whereCargo";
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $cargoData=$stmt->fetchAll();
                $dataAcumulador=[];
                $contadorPersonas=0;
                $paisD="";
                $estadoD="";
                $ciudadD="";
                $dependenciaD="";
                $cargoD="";
                $idsUser="";
                foreach($cargoData as $claveCargo=>$valorCargo){
                    $contadorPersonas=0;

                    $whereUser = $wherePersona."  and b.idestructura= ".$valorDepedencia["id"]." 
                    and id_cargo_id= ".$valorCargo["id"];
                    $dataUser=$this->getUserByInstrumentoAndDependenciaAndCargo($whereUser,$page,$results_per_page,$page_first_result,$id);
                    $idsUser="";
                    foreach($dataUser as $clave=>$valor){
                        if($idsUser==""){
                            $idsUser=$valor['id'];
                        }else{
                            $idsUser.=",".$valor['id'];
                        }
                    }    
                    $dataAcumulador=[];                     
                    if(!$puntosGlobales && count($dataUser)>0){
                        $dataResultado=[];
                        
                        $contadorPersonas++;
                            $dataResultado=[];
                            $sql = " SELECT (sum(oc.score)/".count($dataUser).") as total,h.nombre,h.id FROM `respuesta` a 
                            inner join pregunta b on a.id_pregunta_id = b.id 
                            inner join opciones x on x.id = a.id_opcion_id
                            inner JOIN instrumento_captura c on b.id_instrumento_id = c.id 
                            INNER JOIN tipo_categoria h on b.id_categoria_id = h.id
                            inner join opciones_cargo oc on x.id = oc.opcion_id 
                            inner join instrumento_usuario r  on r.id_user_id = a.id_user_id and r.id_instrumento_id= c.id
                            inner join user f on f.id = r.id_user_id
                            $where and a.id_user_id in(".$idsUser.") and oc.id_cargo_id=".$valor['id_cargo_id']."
                            GROUP by h.nombre,h.id";
                            $conn = $this->getEntityManager()->getConnection();
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result= $stmt->fetchAll();
                            $sumWeightedLevel=0;
                            $sumWeightedRequirement=0;
                            $adecuacyLevel=0;
                            $paisD=$valor["pais"];
                            $estadoD=$valor["estado"];
                            $ciudadD=$valor["ciudad"];
                            $dependenciaD=$valor["dependencia"];
                            $cargoD=$valor["cargo"];
    
                            foreach($result as $claveResult=>$valorResult){  
                                $sql = " SELECT escala FROM `categoria_cargo_escala` a  
                                where  cargo_id=".$valorCargo["id"]." and a.categoria_id=".$valorResult["id"];
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $resultEscala= $stmt->fetchAll();
                                $sql = " SELECT ponderacion FROM categoria_nivel_ponderacion a where a.categoria_id=".$valorResult["id"]." and nivel_id=
                                (select nivel_id from cargo where id=".$valorCargo["id"].")"; 
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $resultPonderacion= $stmt->fetchAll();
        
                                $escala=$resultEscala[0]["escala"]!=null?$resultEscala[0]["escala"]:0;
                                $ponderacion=$resultPonderacion!=null?$resultPonderacion[0]["ponderacion"]:0;
                                $weightedRequirement=($ponderacion*$escala/100);
                                $weightedLevel=($ponderacion*$valorResult["total"])/100;
                                $dataResultado[]=array("label"=>$valorResult["nombre"],
                                     "value"=>round($valorResult["total"],2),
                                    "levelRequired"=>round($escala,2),
                                    "porcenageWeighing"=>$ponderacion,    
                                    "gap"=>$escala-$valorResult["total"],
                                    "weightedRequirement"=>$weightedRequirement,
                                    "weightedLevel"=>$weightedLevel,
                                    "weightPerGap"=>$weightedRequirement-$weightedLevel,
                                    "DIF"=>$ponderacion*$escala!=0?round(1-($weightedLevel/$weightedRequirement),2):0,
                                    "cantidadPersonas"=>count($dataUser),
                                    "users"=>$idsUser,
                                    "entidad"=>"Dependencia: ".$dependenciaD."  -  Cargo:".$cargoD,
                                    "estado"=>$estadoD,
                                    "ciudad"=>$ciudadD,
                                    "dependencia"=>$dependenciaD
                                );
                                $sumWeightedLevel+=$ponderacion*$valorResult["total"];
                                $sumWeightedRequirement+=$ponderacion*$escala;
                                if($sumWeightedRequirement>0){
                                    $adecuacyLevel=$sumWeightedLevel/$sumWeightedRequirement;
                                }    
                            }
                            // $data["entidades"][]=array("entidad"=>"Dependencia: ".$dependenciaD."  -  Cargo:".$cargoD,
                            // "pais"=>$paisD,
                            // "estado"=>$estadoD,
                            // "ciudad"=>$ciudadD,
                            // "dependencia"=>$dependenciaD,
                            // "resultado"=>$dataResultado,
                            // "totales"=>array("adecuacyLevel"=>$adecuacyLevel));            
                            $data[]=$dataResultado;
                            $dataResultado=[];
                    }
    
                }   
                

            }
            // if(isset($data['entidades'])){
            //     $total_items = count($data['entidades']);
            //     $total_pages = ceil($total_items / $results_per_page); 
            //     $offset = ($page - 1) * $results_per_page;
            //     $data["count"]=$total_items;
            //     $data["muestra"]=$total_items;
            //     $result = array_slice($data['entidades'], $offset, $results_per_page);
            //     $data["entidades"]=$result;
            // }else{
            //     $data["count"]=0;
            //     $data["muestra"]=0;
            //     $data["entidades"]=[];
            // }
        
        }elseif($param["byuser"]==3){

            if(isset($param["categoryIds"])){
                $categorias=implode(",",$param["categoryIds"]);
            }
            $sql = "select * from estructura_organizativa";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $dependencias=$stmt->fetchAll();
            $sql = "select * from cargo";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $cargoData=$stmt->fetchAll();
            $dataAcumulador=[];
            $contadorPersonas=0;
            $paisD="";
            $estadoD="";
            $ciudadD="";
            $dependenciaD="";
            $cargoD="";
            $idsUser="";
            foreach($cargoData as $claveCargo=>$valorCargo){
                $contadorPersonas=0;
            
                $whereUser = $wherePersona."  
                and id_cargo_id= ".$valorCargo["id"];

                $dataUser=$this->getUserByInstrumentoAndAndCargo($whereUser,$page,$results_per_page,$page_first_result);
                $idsUser="";
                foreach($dataUser as $clave=>$valor){
                    if($idsUser==""){
                        $idsUser=$valor['id'];
                    }else{
                        $idsUser.=",".$valor['id'];
                    }
                }    
                $dataAcumulador=[];                     
                if(!$puntosGlobales && count($dataUser)>0){
                    $dataResultado=[];
                    
                    $contadorPersonas++;
                        $dataResultado=[];
                        if(isset($categorias)){
                            $where .= " and  h.id in ($categorias)";
                        }
                        $sql = " SELECT (sum(oc.score)/".count($dataUser).") as total,h.nombre,h.id FROM `respuesta` a 
                        inner join pregunta b on a.id_pregunta_id = b.id 
                        inner join opciones x on x.id = a.id_opcion_id
                        inner JOIN instrumento_captura c on b.id_instrumento_id = c.id 
                        INNER JOIN tipo_categoria h on b.id_categoria_id = h.id
                        inner join opciones_cargo oc on x.id = oc.opcion_id 
                        inner join instrumento_usuario r on r.id_user_id = a.id_user_id and r.id_instrumento_id= c.id
                        inner join user f on f.id = r.id_user_id
                        $where and a.id_user_id in(".$idsUser.") and oc.id_cargo_id=".$valor['id_cargo_id']."
                        GROUP by h.nombre,h.id";
                        $conn = $this->getEntityManager()->getConnection();
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result= $stmt->fetchAll();
                        $sumWeightedLevel=0;
                        $sumWeightedRequirement=0;
                        $adecuacyLevel=0;
                        $paisD=$valor["pais"];
                        $estadoD=$valor["estado"];
                        $ciudadD=$valor["ciudad"];
                        $cargoD=$valor["cargo"];

                        foreach($result as $claveResult=>$valorResult){  
                            $sql = " SELECT escala FROM `categoria_cargo_escala` a  
                            where  cargo_id=".$valorCargo["id"]." and a.categoria_id=".$valorResult["id"];
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $resultEscala= $stmt->fetchAll();
                            $sql = " SELECT ponderacion FROM categoria_nivel_ponderacion a where a.categoria_id=".$valorResult["id"]." and nivel_id=
                            (select nivel_id from cargo where id=".$valorCargo["id"].")"; 
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $resultPonderacion= $stmt->fetchAll();
    
                            $escala=$resultEscala[0]["escala"]!=null?$resultEscala[0]["escala"]:0;
                            $ponderacion=$resultPonderacion!=null?$resultPonderacion[0]["ponderacion"]:0;
                            $weightedRequirement=($ponderacion*$escala/100);
                            $weightedLevel=($ponderacion*$valorResult["total"])/100;
                            $dataResultado[]=array("label"=>$valorResult["nombre"],
                                    "value"=>round($valorResult["total"],2),
                                "levelRequired"=>round($escala,2),
                                "porcenageWeighing"=>$ponderacion,    
                                "gap"=>$escala-$valorResult["total"],
                                "weightedRequirement"=>$weightedRequirement,
                                "weightedLevel"=>$weightedLevel,
                                "weightPerGap"=>$weightedRequirement-$weightedLevel,
                                "DIF"=>$ponderacion*$escala!=0?round(1-($weightedLevel/$weightedRequirement),2):0,
                                "cantidadPersonas"=>count($dataUser),
                                "users"=>$idsUser,
                                "entidad"=>"Dependencia: ".$dependenciaD."  -  Cargo:".$cargoD,
                                "estado"=>$estadoD,
                                "ciudad"=>$ciudadD,
                                "dependencia"=>$dependenciaD
                            );
                            $sumWeightedLevel+=$ponderacion*$valorResult["total"];
                            $sumWeightedRequirement+=$ponderacion*$escala;
                            if($sumWeightedRequirement>0){
                                $adecuacyLevel=$sumWeightedLevel/$sumWeightedRequirement;
                            }    
                        }
                        // $data["entidades"][]=array("entidad"=>"Cargo: ".$cargoD,
                        // "pais"=>$paisD,
                        // "estado"=>$estadoD,
                        // "ciudad"=>$ciudadD,
                        // "dependencia"=>$dependenciaD,
                        // "resultado"=>$dataResultado,
                        // "totales"=>array("adecuacyLevel"=>$adecuacyLevel));            
                        $data[]=$dataResultado;

                        $dataResultado=[];
                }

            }   
                

            
            // if(isset($data['entidades'])){
            //     $total_items = count($data['entidades']);
            //     $total_pages = ceil($total_items / $results_per_page); 
            //     $offset = ($page - 1) * $results_per_page;
            //     $data["count"]=$total_items;
            //     $data["muestra"]=$total_items;
            //     $result = array_slice($data['entidades'], $offset, $results_per_page);
            //     $data["entidades"]=$result;
            // }else{
            //     $data["count"]=0;
            //     $data["muestra"]=0;
            //     $data["entidades"]=[];
            // }
        
        }else{
            $page=null;
            $results_per_page =null;
            $page_first_result=null;
            if($especial){
                $dataUser=$this->getUserByInstrumento($wherePersona,$page,$results_per_page,$page_first_result);
                $dataResultado=array();
                $whereUser="";
                $data["count"]=$this->totalCount;
                $data["muestra"]=$this->muestra;
                foreach($dataUser as $clave=>$valor){
                    $whereUser = $where . " and a.id_user_id=".$valor['id'];
                        $resultEspecial=$this->getTotalContadorEspecial($whereUser,$fields,$groupby);
                        if(count($resultEspecial)>0){   
                            if(count($dataResultado)>0){
                                $position= array_key_exists($resultEspecial[0]["nombre"], $dataResultado);
                                if($position){
                                    $dataResultado[$resultEspecial[0]["nombre"]]=$dataResultado[$resultEspecial[0]["nombre"]]+1;                                                        
                                }else{
                                    $dataResultado[$resultEspecial[0]["nombre"]]=1;                                                        
                                }
                            }else{
                                $dataResultado[$resultEspecial[0]["nombre"]]=1;                                                        
                            }
                        }                                            
                    $whereUser="";
                }   
                if($especial){
                    $dataEspecial=[];
                    foreach($dataResultado as $clave=>$valor){
                        $dataEspecial[]=array("label"=>$clave,"value"=>$valor);

                    }
                    $data[]=$dataEspecial;
                    //$data["entidades"][]=array("entidad"=>"", "resultado"=>$dataEspecial);
                }
            }else{
                $sql="select nombre from pais where id= '".$param["pais"]."'";
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $paisData=$stmt->fetchAll();
                $sql="select nombre from ciudad where id= '".$param["ciudad"]."'";
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $ciudadData=$stmt->fetchAll();
                $sql="select nombre from estado where id= '".$param["estado"]."'";
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $estadoData=$stmt->fetchAll();
            
                if($param["sexo"]!=null){
                    $sexo=strtolower($param["sexo"])=="f"?"Femenino":"Masculino";
                }else{
                    $sexo =$param["sexo"];
                }
                $datapregunta=$this->getPreguntaByInstrumento($id,$param);
                $dataResultado=array();
                if(!$categoria){
                    $data["count"]=$this->getPoblacionPregunta($id);
                    $data["muestra"]=$this->muestra;

                    foreach($datapregunta as $clave=>$valor){
                        $wherePregunta= $where . " and b.id_instrumento_id=".$valor["id_instrumento_id"]." and b.id=".$valor["id"]."";
                           
                        $result=$this->getTotal($wherePregunta,$fields,$groupby);
                       
                        foreach($result as $claveResult=>$valorResult){
                            $dataResultado[]=array("entidad"=>$valor["pregunta"],
                                                    "pais"=>$paisData!=null?$paisData[0]["nombre"]:null,
                                                    "estado"=>$estadoData!=null?$estadoData[0]["estado"]:null,
                                                    "ciudad"=>$ciudadData!=null?$ciudadData[0]["nombre"]:null,
                                                    "sexo"=>$sexo,"label"=>$valorResult["nombre"],"value"=>$valorResult["total"]);
                        }    
                        // $data["entidades"][]=array("entidad"=>$valor["pregunta"],
                        // "pais"=>$paisData!=null?$paisData[0]["nombre"]:null,
                        // "estado"=>$estadoData!=null?$estadoData[0]["estado"]:null,
                        // "ciudad"=>$ciudadData!=null?$ciudadData[0]["nombre"]:null,
                        // "sexo"=>$sexo,
                        // "resultado"=>$dataResultado);

                        $data[]=$dataResultado;
                        $wherePregunta="";
                        $dataResultado=array();
                    }            
                }else{
                    $data["count"]=0;
                    $data["muestra"]=$this->muestra;
                    $wherePregunta= $where . " and b.id_instrumento_id=".$id;
                    $fields=" sum(x.puntos) as value, m.nombre as label ";
                    $groupby="GROUP by m.nombre";     
                    $result=$this->getTotalByCategoriaGlobal($wherePregunta,$fields,$groupby);                        
                    foreach($result as $clave=>$valor){
                        $result[$clave]["value"]=round($result[$clave]["value"]/$this->totalCount,0);
                        $result[$clave]["pais"]=$paisData!=null?$paisData[0]["nombre"]:null;
                        $result[$clave]["value"]=$estadoData!=null?$estadoData[0]["nombre"]:null;
                        $result[$clave]["value"]=$ciudadData!=null?$ciudadData[0]["nombre"]:null;
                        $result[$clave]["value"]=round($result[$clave]["value"]/$this->totalCount,0);

                    }
                    $data["entidades"][]=array("entidad"=>"",
                    "pais"=>$paisData!=null?$paisData[0]["nombre"]:null,
                    "estado"=>$estadoData!=null?$estadoData[0]["nombre"]:null,
                    "ciudad"=>$ciudadData!=null?$ciudadData[0]["nombre"]:null,
                    "sexo"=>$sexo
                    , "resultado"=>$result);
                }
            }
        }
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('Data');
        $row = 1;

        foreach ($data as $cl=>$r) {
            $col = 'A';
            foreach ($r as $clave=>$valor) {
                if(is_array($valor)){
                    foreach($valor as $cellData ) {
                        $spreadsheet->getActiveSheet()->setCellValue($col . $row, $cellData);
                        $col++;
                    }
                }else{
                    $spreadsheet->getActiveSheet()->setCellValue($col . $row, $valor);
                    $col++;
                }    

            }
            $row++;
        }
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_start();
        $objWriter->save('php://output');
        $binaryData = ob_get_contents();
        ob_end_clean();
        return $binaryData;
       //  $objWriter->save('../public/dowload/data.xlsx');
        //return $data;

    }

    public function getUserByInstrumento($where,$page,$results_per_page,$page_first_result){


        if(isset($results_per_page)){
    
            $sqlUser = " SELECT b.id,b.numero_documento, b.primer_nombre,b.primer_apellido,case b.sexo when 'f' then 'Femenino' when 'm' then 'Masculino' end as sexo,a.fecha_inicio,
            f.nombre as pais,e.nombre as estado,c.nombre as ciudad,b.id_cargo_id
            , s.estructura_organizativa as  dependencia,'' as gerencia,'' as coordinacion,
            p.descripcion cargo  
            from instrumento_usuario a inner join user b on a.id_user_id = b.id
            left join pais f on b.pais_id = f.id 
            left join estado e on b.estado_id= e.id 
            left join ciudad c on b.ciudad_id = c.id 
            left join estructura_organizativa s on s.id = b.idestructura
            left join cargo p on b.id_cargo_id = p.id
             ".$where . " and a.respondida=1  "; //and b.id_cargo_id in (5,6)  
    
        }else{
            $sqlUser = " SELECT b.id,b.primer_nombre,b.numero_documento,b.primer_apellido,
            case b.sexo when 'f' then 'Femenino' when 'm' then 'Masculino' end as sexo,
            a.fecha_inicio, f.nombre as pais,e.nombre as estado,c.nombre as ciudad,b.id_cargo_id  
            from instrumento_usuario a inner join user b on a.id_user_id = b.id
            left join pais f on b.pais_id = f.id 
            left join estado e on b.estado_id= e.id 
            left join ciudad c on b.ciudad_id = c.id  ".$where. " and a.respondida=1 "; //and b.id_cargo_id in (5,6)              
        }
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlUser);
        $stmt->execute();
        $dataUser=$stmt->fetchAll();
        return $dataUser;
    }


    public function getUserByInstrumentoAndDependenciaAndCargo($where,$page,$results_per_page,$page_first_result,$id){


        if(isset($results_per_page)){
            if($this->totalCount>0){
                $number_of_page = ceil ($this->totalCount / $results_per_page); 
            }
    
            $sqlUser = " SELECT b.id,b.primer_nombre,b.primer_apellido,case b.sexo when 'f' then 'Femenino' when 'm' then 'Masculino' end as sexo,a.fecha_inicio,
            f.nombre as pais,e.nombre as estado,c.nombre as ciudad,b.id_cargo_id,
            s.estructura_organizativa as dependencia, '' as gerencia,'' as 
            coordinacion,
            p.descripcion cargo  
            from instrumento_usuario a inner join user b on a.id_user_id = b.id
            left join pais f on b.pais_id = f.id 
            left join estado e on b.estado_id= e.id 
            left join ciudad c on b.ciudad_id = c.id 
            left join estructura_organizativa s on s.id = b.idestructura left join cargo p on b.id_cargo_id = p.id
            left join cargo p on b.id_cargo_id = p.id
             ".$where . " and a.respondida=1  and a.id_instrumento_id = ".$id; 
        }else{
            $sqlUser = " SELECT b.id,b.primer_nombre,b.primer_apellido,
            case b.sexo when 'f' then 'Femenino' when 'm' then 'Masculino' end as sexo,
            a.fecha_inicio, f.nombre as pais,e.nombre as estado,c.nombre as ciudad,b.id_cargo_id  
            from instrumento_usuario a inner join user b on a.id_user_id = b.id
            left join pais f on b.pais_id = f.id 
            left join estado e on b.estado_id= e.id 
            left join ciudad c on b.ciudad_id = c.id  ".$where. " and a.respondida=1 and a.id_instrumento_id = ".$id;              
        }
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlUser);
        $stmt->execute();
        $dataUser=$stmt->fetchAll();
        
        return $dataUser;
    }


    public function getUserByInstrumentoAndAndCargo($where,$page,$results_per_page,$page_first_result){


        if(isset($results_per_page)){
            if($this->totalCount>0){
                $number_of_page = ceil ($this->totalCount / $results_per_page); 
            }
    
            $sqlUser = " SELECT b.id,b.primer_nombre,b.primer_apellido,case b.sexo when 'f' 
            then 'Femenino' when 'm' then 'Masculino' end as sexo,a.fecha_inicio,
            f.nombre as pais,e.nombre as estado,c.nombre as ciudad,b.id_cargo_id,
            p.descripcion cargo  
            from instrumento_usuario a inner join user b on a.id_user_id = b.id
            left join pais f on b.pais_id = f.id 
            left join estado e on b.estado_id= e.id 
            left join ciudad c on b.ciudad_id = c.id 
            left join cargo p on b.id_cargo_id = p.id
             ".$where . " and a.respondida=1 "; 
        }else{
            $sqlUser = " SELECT b.id,b.primer_nombre,b.primer_apellido,
            case b.sexo when 'f' then 'Femenino' when 'm' then 'Masculino' end as sexo,
            a.fecha_inicio, f.nombre as pais,e.nombre as estado,c.nombre 
            as ciudad,b.id_cargo_id  
            from instrumento_usuario a inner join user b on a.id_user_id = b.id
            left join pais f on b.pais_id = f.id 
            left join estado e on b.estado_id= e.id 
            left join ciudad c on b.ciudad_id = c.id  ".$where. " and a.respondida=1 ";              
        }
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlUser);
        $stmt->execute();
        $dataUser=$stmt->fetchAll();
        
        return $dataUser;
    }

    public function getPoblacion($where){
        $sqlUser = " SELECT count(b.id) as contador 
        from instrumento_usuario a inner join user b on a.id_user_id = b.id ".$where ." 
        and a.respondida=1 "  ;
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlUser);
        $stmt->execute();
        $dataUserCount=$stmt->fetchAll();
        $this->totalCount=$dataUserCount[0]["contador"];
        $this->muestra=$dataUserCount[0]["contador"];
    }

    public function getPoblacionPregunta($id){
        $sqlUser = " SELECT count(*) as contador FROM `pregunta` where id_instrumento_id= ".$id  ;
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlUser);
        $stmt->execute();
        $dataUserCount=$stmt->fetchAll();
        return $dataUserCount[0]["contador"];
    }


    public function getTotal($where,$fields,$groupby){

        $sql = " SELECT $fields FROM `respuesta` a 
         right join pregunta b on a.id_pregunta_id = b.id right join opciones x on x.id = a.id_opcion_id
         inner JOIN instrumento_captura c on b.id_instrumento_id = c.id
         inner join instrumento_usuario r on r.id_user_id = a.id_user_id and r.id_instrumento_id = c.id
         inner join user f on a.id_user_id = f.id 
         $where  $groupby
        
         ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data=$stmt->fetchAll();
        return $data;
 
    }
    
    public function getTotalByCategoria($where,$fields,$groupby){
        $sql = " SELECT $fields  FROM `respuesta` a 
        inner join pregunta b on a.id_pregunta_id = b.id inner join opciones x on x.id = a.id_opcion_id
         inner JOIN instrumento_captura c on b.id_instrumento_id = c.id 
        INNER JOIN tipo_categoria m on b.id_categoria_id = m.id
        inner join instrumento_usuario r on r.id_user_id = a.id_user_id and r.id_instrumento_id = c.id
         inner join user f on a.id_user_id = f.id
        $where  $groupby";

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data=$stmt->fetchAll();
        return $data;
 
    }

    public function getTotalByCategoriaGlobal($where,$fields,$groupby){
        $sql = " SELECT $fields  FROM `respuesta` a 
        inner join pregunta b on a.id_pregunta_id = b.id inner join opciones x on x.id = a.id_opcion_id
         inner JOIN instrumento_captura c on b.id_instrumento_id = c.id 
        INNER JOIN tipo_categoria m on b.id_categoria_id = m.id
        inner join instrumento_usuario r on r.id_user_id = a.id_user_id and r.id_instrumento_id = c.id
         inner join user f on a.id_user_id = f.id
        $where  $groupby";

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data=$stmt->fetchAll();
        return $data;
 
    }
    public function getTotalContadorEspecial($where,$fields,$groupby){
        $sql="SELECT $fields FROM `respuesta` a 
        inner join pregunta b on a.id_pregunta_id = b.id 
        inner join opciones x on x.id = a.id_opcion_id 
        inner JOIN instrumento_captura c on b.id_instrumento_id = c.id 
        inner join instrumento_usuario r on r.id_user_id = a.id_user_id and r.id_instrumento_id = c.id
         inner join user f on a.id_user_id = f.id
        $where $groupby";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result= $stmt->fetchAll();
        $mayor=array();

        foreach($result as $claveResult=>$valorResult){
            if(count($mayor)==0){
                $mayor[0]=array("nombre"=>strtoupper($valorResult["nombre"]),"total"=>$valorResult["total"]);
            }else{
                if($mayor[0]["total"]<$valorResult["total"]){                   
                    $mayor[0]["nombre"]=strtoupper($valorResult["nombre"]);
                    $mayor[0]["total"]=$valorResult["total"];

                }
            }            

        } 
        return $mayor;
    }


    public function getPreguntaByInstrumento($id,$param){

        if (!isset ($param['page']) ) {  
            $page = 1;  
        } else {  
            $page = $param['page'];  
        }

        if (!isset($param['rowByPage']) ) {  
            $results_per_page = null;  
        } else {  
            $results_per_page = $param['rowByPage'];  
        }

        $page_first_result = ($page-1) * $results_per_page;  

        if($page_first_result!=0 && $results_per_page!=null){
            $sqlPregunta = "SELECT * FROM pregunta
            where id_instrumento_id=".$id." LIMIT ". $page_first_result . "," . $results_per_page;    
        }else{
            $sqlPregunta = "SELECT * FROM pregunta
            where id_instrumento_id=".$id;                
        }
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlPregunta);
        $stmt->execute();
        $dataPregunta=$stmt->fetchAll();
        return $dataPregunta;
    }



    public function resultadosIndividualesPorCargosSinCategorizacion($id)
    {

        $entityManager = $this->getEntityManager();
        $data=[];
        $sqlUser = ' SELECT b.id,b.primer_nombre,b.primer_apellido,b.id_cargo_id 
        from instrumento_usuario a inner join user b on a.id_user_id = b.id
        where a.id_instrumento_id='.$id;
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlUser);
        $stmt->execute();
        $dataUser=$stmt->fetchAll();
        foreach($dataUser as $clave=>$valor){
            $dataResultado=[];
            $sql = " SELECT sum(oc.score) as total,x.nombre FROM `respuesta` a 
            right join pregunta b on a.id_pregunta_id = b.id right join opciones x 
            on x.id = a.id_opcion_id inner join opciones_cargo oc on x.id = oc.opcion_id 
            and a.id_opcion_id = oc.opcion_id inner JOIN instrumento_captura c on 
            b.id_instrumento_id = c.id
            where b.id_instrumento_id= $id and a.id_user_id=".$valor['id']."
            and oc.id_cargo_id =".$valor['id_cargo_id']."
            GROUP by x.nombre;";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            foreach($result as $claveResult=>$valorResult){
                $dataResultado[]=array("label"=>$valorResult["nombre"],"value"=>$valorResult["total"]);
            }    
            $data["entidades"][]=array("entidad"=>$valor["primer_nombre"]." ".$valor["primer_apellido"], "resultado"=>$dataResultado);            
        }    
        return $data;
    }


    public function resultadosIndividualesPorCargosConCategorizacion($id)
    {

        $entityManager = $this->getEntityManager();
        $data=[];
        $sqlUser = ' SELECT b.id,b.primer_nombre,b.primer_apellido,b.id_cargo_id 
        from instrumento_usuario a inner join user b on a.id_user_id = b.id
        where a.id_instrumento_id='.$id; 
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlUser);
        $stmt->execute();
        $dataUser=$stmt->fetchAll();
        foreach($dataUser as $clave=>$valor){
            $dataResultado=[];
            $sql = " SELECT sum(oc.score) as total,f.nombre FROM `respuesta` a 
            inner join pregunta b on a.id_pregunta_id = b.id inner join opciones x on x.id = a.id_opcion_id
            inner JOIN instrumento_captura c on b.id_instrumento_id = c.id 
            INNER JOIN tipo_categoria f on b.id_categoria_id = f.id
            inner join opciones_cargo oc on x.id = oc.opcion_id 
             where  c.id= $id and a.id_user_id=".$valor['id']." and oc.id_cargo_id=".$valor['id_cargo_id']." GROUP by f.nombre;";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            foreach($result as $claveResult=>$valorResult){
                $dataResultado[]=array("label"=>$valorResult["nombre"],"value"=>$valorResult["total"]);
            }    
            $data["entidades"][]=array("entidad"=>$valor["primer_nombre"]." ".$valor["primer_apellido"], "resultado"=>$dataResultado);            
        }    
        return $data;
    }

    
    public function resultadosPorCargosConCategorizacionyDependecia($id,$idpedendencia)
    {

        $entityManager = $this->getEntityManager();
        $data=[];
        $sqlUser = " SELECT b.id,b.primer_nombre,b.primer_apellido,b.id_cargo_id,
        f.descripcion 
        from instrumento_usuario a inner join user b on a.id_user_id = b.id
        inner join dependencia f on f.id = b.id_dependencia_id  
        where a.id_instrumento_id=".$id." and b.id_dependencia_id=".$idpedendencia; 
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sqlUser);
        $stmt->execute();
        $dataUser=$stmt->fetchAll();
        foreach($dataUser as $clave=>$valor){
            $dataResultado=[];
            $sql = " SELECT sum(oc.score) as total,f.nombre FROM `respuesta` a 
            inner join pregunta b on a.id_pregunta_id = b.id inner join opciones x on x.id = a.id_opcion_id
            inner JOIN instrumento_captura c on b.id_instrumento_id = c.id 
            INNER JOIN tipo_categoria f on b.id_categoria_id = f.id
            inner join opciones_cargo oc on x.id = oc.opcion_id 
             where  c.id= $id and a.id_user_id=".$valor['id']." and oc.id_cargo_id=".$valor['id_cargo_id']." GROUP by f.nombre;";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            foreach($result as $claveResult=>$valorResult){
                if(array_key_exists($valorResult["nombre"],$dataResultado)){
                    $dataResultado[$valorResult["nombre"]]["total"]+=$valorResult["total"];
                }else{
                    $dataResultado[]=array("label"=>$valorResult["nombre"],"value"=>$valorResult["total"]);
                }
            }    
            $data["entidades"][]=array("entidad"=>$valor["descripcion"], "resultado"=>$dataResultado);            
        }    
        return $data;
    }
}
