<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\Archivosd;
use App\Entity\DocumentoDigital\HistArchivoContVersiond;
use App\Entity\DocumentoDigital\UsuarioArchivoBloqueadod;
use App\Entity\DocumentoDigital\HistArchivoBloqueadod;

Use App\Entity\User;
Use App\Entity\DocumentoDigital\TipoEstadod;
Use App\Entity\DocumentoDigital\TipoLimitedBloqueod;
Use App\Entity\DocumentoDigital\TipoArchivod;
Use App\Entity\DocumentoDigital\TipoOrientaciond;
Use App\Entity\DocumentoDigital\TipoOperacionesd;
Use App\Entity\DocumentoDigital\Pruebas;

Use App\Entity\DocumentoDigital\UsuarioArchivosd;
Use App\Entity\DocumentoDigital\ArchivosExtensionesd;
use App\Dto\DocumentoDigital\ArchivosOutPutDto;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;

use App\Entity\Ciudad;
use App\Entity\DocumentoDigital\ContenidoCaja;
use App\Entity\DocumentoDigital\ControlArchivoDigital;
use App\Entity\DocumentoDigital\EstructuraOrganizativa;
use App\Entity\DocumentoDigital\SubSerie;
use App\Entity\DocumentoDigital\TipoAlmacen;
use App\Entity\DocumentoDigital\TipoStatusArchivod;
use App\Entity\DocumentoDigital\Ubicacion;
use App\Entity\Region;
use App\Entity\Estado;
use App\Entity\Pais;


use App\Entity\StaExped\Area;
use App\Dto\StaExped\AreaOutPutDto;


/**
 * @method Archivosd|null find($id, $lockMode = null, $lockVersion = null)
 * @method Archivosd|null findOneBy(array $criteria, array $orderBy = null)
 * @method Archivosd[]    findAll()
 * @method Archivosd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchivosdRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Archivosd::class);
    }


    public function findAllPage($data,$em):JsonResponse{
        $entityManagerDefault = $this->getEntityManager();
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        $dataRol=[];
        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
        $query = $em->createQueryBuilder('a');
        $query->select("b,a,t,e,l")
        ->from('App\Entity\DocumentoDigital\Archivosd', 'a')
        ->innerJoin('a.iduserarchivos', 'b')
        ->innerJoin('a.id_tipo_archivo', 't')
        ->innerJoin('a.idestado', 'e')
        ->innerJoin('a.id_limited_bloqueo', 'l'); 
        $query->Where("b.idusuario_id =".$this->security->getUser()->getId()." and a.idempresa_id = ".$empresa->getId() );
        if($data['word']!=null){
            $query->andWhere("a.titulo like '%".$data['word']."%' or a.nombre_original like '%".$data['word']."%' or a.hashtag like '%".$data['word']."%' ");
         }
        $query->orderBy('a.id', 'ASC');   
 

        $query->getQuery();
        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery()	
      	->setFirstResult($data['rowByPage'] *($data['page']-1))	
        ->setMaxResults($data['rowByPage']);
        $dataarchivos=array();
         $archivo_bloqueado=[];
        foreach($paginator as $clave=>$valor){
            $archivocapturaDto =new ArchivosOutPutDto();
            $archivocapturaDto->id=$valor->getId();
            $archivocapturaDto->titulo=$valor->getTitulo();
            $archivocapturaDto->nombre_original=$valor->getNombreOriginal();
            $archivocapturaDto->descripcion_archivo=$valor->getDescripcionArchivo();
            $archivocapturaDto->tamano=$valor->getTamano();
            $archivocapturaDto->url_alojamiento=$valor->getUrlAlojamiento();
            $archivocapturaDto->publico=$valor->getPublico();
            $archivocapturaDto->nemotecnico=$valor->getNemotecnico();
            //$instrumentocapturaDto->fecha_actividad_registro=$valor->getFechaActividadRegistro();
            $archivocapturaDto->fecha_actividad_registro=$valor->getFechaActividadRegistro()!=null? $valor->getFechaActividadRegistro()->format("Y-m-d"):null;
            $archivocapturaDto->id_tipo_archivo=  $valor->getIdTipoArchivo()->getId();
            $archivocapturaDto->tipoarchivo=  $valor->getIdTipoArchivo()->getNombreArchivo();
            $extnomb = explode(".", $valor->getUrlAlojamiento());
            $extnombf = $extnomb[1];
            $archivocapturaDto->tipo_extensiones= $extnombf;
            //$quien = $valor->getIdTipoArchivo()->getTipoArchivoExtesiones();
            $archivocapturaDto->idestado=  $valor->getIdestado()->getId();
            $archivocapturaDto->nombre_status=  $valor->getIdestado()->getNombreStatus();
            $archivocapturaDto->id_limited_bloqueo=  $valor->getIdLimitedBloqueo()->getId();
            $archivocapturaDto->nombre_limited_bloqueo=  $valor->getIdLimitedBloqueo()->getNombreBloqueo();
            $archivocapturaDto->hashtag=  $valor->getHashtag();

            $archivocapturaDto->folios=  $valor->getFolios();
            $archivocapturaDto->num_dela_caja=  $valor->getNumDelaCaja();

            #$archivocapturaDto->num_dela_estuches=  $valor->getNumDelaEstuches();

            $archivocapturaDto->fecha_extrema_inicio=  $valor->getFechaExtremaInicio();
            $archivocapturaDto->fecha_extrema_fin=  $valor->getFechaExtremaFin();

            $archivocapturaDto->idempresa_id=  $valor->getIdempresa();

            $dataarchivos[]=$archivocapturaDto;                
        }
        return New JsonResponse(["count"=>count($paginatorTotalCount),"data"=>$dataarchivos]);
    }

   
    public function getArchivosById($id,$em){
        $entityManager = $this->getEntityManager();

        $query = $em->createQueryBuilder('a');
        $query->select("b,a,t,e,l")
        ->from('App\Entity\DocumentoDigital\Archivosd', 'a')
        ->innerJoin('a.iduserarchivos', 'b')
        ->innerJoin('a.id_tipo_archivo', 't')
        ->innerJoin('a.idestado', 'e')
        ->innerJoin('a.id_limited_bloqueo', 'l'); 
        $query->Where("a.id =".$id);
        $query->orderBy('a.id', 'ASC');   
        $query->getQuery();
        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery();	
        $dataarchivos=array();
        $recursosArchivos=array();
        $historico=array();
        $archivo_bloqueado=[];
        foreach($paginator as $clave=>$valor){
            $archivocapturaDto =new ArchivosOutPutDto();
            $recursosProyecto=[];
            $idusuario = $valor->getIduserarchivos();
            foreach($valor->getIduserarchivos() as $claveResult=>$usuarioarchivos){
                $dime = $usuarioarchivos->getId();

                $usuarr = $usuarioarchivos->getIduserarchivos()->getId();
                /* $sql = " SELECT a.*,b.* FROM `usuario_archivos` a 
                inner join user b on a.idusuario_id = b.id 
                where a.id=".$dime.";"; */

                /* $sql = " SELECT a.* FROM `usuario_archivos` a 
                where a.id=".$dime.";";
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result= $stmt->fetchAll(); */

                $query = $em->createQueryBuilder();
                $allAppointmentsQuery = $query->select('UsuarioArchivos')
                ->from(UsuarioArchivosd::class,'UsuarioArchivos')
                ->Where('UsuarioArchivos.id='.$dime)
                ->addOrderBy('UsuarioArchivos.id', 'ASC')
                ->getQuery();
                $queryult = $query->getQuery();
                $dataUsuarioArchivo =  $queryult->execute();
                if($dataUsuarioArchivo==null){
                  return new JsonResponse(['msg'=>'No existen el id Usuario Archivod : '.$dime],404);
                }

                //$result= $stmt->executeQuery();
                foreach($dataUsuarioArchivo as $claveResult=>$valorResult){
                //foreach($result as $claveResult=>$valorResult){

                  $dimm = $valorResult->getIdusuario();

                    $sqluser = " SELECT u.* FROM `user` u 
                    where u.id=".$dimm.";";
                    $conn2 = $this->getEntityManager()->getConnection();
                    $stmt2 = $conn2->prepare($sqluser);
                    $stmt2->execute();
                    $result2= $stmt2->fetchAll();
                    foreach($result2 as $claveResult=>$valorResult){
                        $recursosArchivos[]=array("id"=> $valorResult["id"],
                        "userName"=>$valorResult["username"],
                        "primerNombre"=>$valorResult["primer_nombre"],
                        "segundoNombre"=>$valorResult["segundo_nombre"],
                        "email"=>$valorResult["email"],
                    );
                   }
                }

            }

            // Modificar


            $entity = $em->createQueryBuilder();
            $data = $entity->select("h,t")
                ->from("App\Entity\DocumentoDigital\HistArchivoContVersiond", "h")
                ->innerJoin('h.id_tipo_operaciones', 't')
                ->where("h.idarchivo ='" . $valor->getId() . "'")
                ->getQuery()
                ->getResult();
            $dataPregunta = [];
            $opciones = [];
            foreach($data as $claveResult=>$valorResultH){
                $idusers = $valorResultH->getIduser();

                $sqluser = " SELECT u.* FROM `user` u 
                where u.id=".$idusers.";";

                $conn3 = $this->getEntityManager()->getConnection();
                $stmt3 = $conn3->prepare($sqluser);
                $stmt3->execute();
                $result3= $stmt3->fetchAll();
                $primernomb="";
                $segundonomb="";
                $primerapellido="";
                $segundoapellido="";
                $emaill="";
                foreach($result3 as $claveResult=>$valorResultuser){
                    $primernomb=$valorResultuser["primer_nombre"];
                    $segundonomb=$valorResultuser["segundo_nombre"];
                    $primerapellido=$valorResultuser["primer_apellido"];
                    $segundoapellido=$valorResultuser["segundo_apellido"];
                    $emaill=$valorResultuser["email"];
                }

                $historico[]=array("id"=>$valorResultH->getId(),
                "fecha_creacion_hist"=> $valorResultH->getFechaCreacionHist(),
                "tipo_operaciones"=>$valorResultH->getIdTipoOperaciones()->getOperacion(),
                "comentario"=>$valorResultH->getComentario(),
                "nemotecnico"=>$valorResultH->getNemotecnico(),
                "nombre_apellido"=>$primernomb.' '.$segundonomb.' '.$primerapellido.' '.$segundoapellido,"email"=>$emaill,
                );
            }

            //****************** */ 
            
            /* $sql = " SELECT h.*,t.id as idoperacion,t.operacion,u.primer_nombre,u.segundo_nombre,u.primer_apellido,u.segundo_apellido,u.email FROM `hist_archivo_cont_version` h 
            inner join tipo_operaciones t on h.id_tipo_operaciones_id = t.id 
            inner join user u on h.iduser_id = u.id 
            where h.idarchivo_id=".$valor->getId()." order by h.id desc";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            //$result= $stmt->executeQuery();
            $stmt->execute();
            $result= $stmt->fetchAll();
            foreach($result as $claveResult=>$valorResultH){
                $historico[]=array("id"=>$valorResultH["id"],
                "fecha_creacion_hist"=> $valorResultH["fecha_creacion_hist"],
                "tipo_operaciones"=>$valorResultH["operacion"],
                "comentario"=>$valorResultH["comentario"],
                "nemotecnico"=>$valorResultH["nemotecnico"],
                "nombre_apellido"=>$valorResultH["primer_nombre"].' '.$valorResultH["segundo_nombre"].' '.$valorResultH["primer_apellido"].' '.$valorResultH["segundo_apellido"],
                "email"=>$valorResultH["email"],
                );
            } */


            //para bloqueo de archivos
            /* $sql = " SELECT ua.*,u.id as iduser,u.primer_nombre,u.segundo_nombre,u.primer_apellido,u.segundo_apellido,u.email FROM `usuario_archivo_bloqueado` ua 
            inner join user u on ua.iduser_id = u.id 
            where ua.idarchivo_id=".$id.";";
            $conn = $this->getEntityManager()->getConnection();
            $stmt1 = $conn->prepare($sql);
            $stmt1->execute();
            $resultbloq= $stmt1->fetchAll();
            //$resultbloq= $stmt->executeQuery();
            foreach($resultbloq as $claveResult=>$valorResulblq){
                $archivo_bloqueado[]=array(
                "fecha_bloqueo"=> $valorResulblq["fecha_bloqueo"],
                "id"=>$valorResulblq["iduser_id"],
                "nombre_apellido"=>$valorResulblq["primer_nombre"].' '.$valorResulblq["segundo_nombre"].' '.$valorResulblq["primer_apellido"].' '.$valorResulblq["segundo_apellido"],
                "email"=>$valorResulblq["email"],
                );
            } */

            $archivocapturaDto->id=$valor->getId();
            $archivocapturaDto->titulo=$valor->getTitulo();
            $archivocapturaDto->nombre_original=$valor->getNombreOriginal();
            $archivocapturaDto->descripcion_archivo=$valor->getDescripcionArchivo();
            $archivocapturaDto->tamano=$valor->getTamano();
            $archivocapturaDto->url_alojamiento=$valor->getUrlAlojamiento();
            $archivocapturaDto->publico=$valor->getPublico();
            $archivocapturaDto->nemotecnico=$valor->getNemotecnico();
            //$instrumentocapturaDto->fecha_actividad_registro=$valor->getFechaActividadRegistro();
            $archivocapturaDto->fecha_actividad_registro=$valor->getFechaActividadRegistro()!=null? $valor->getFechaActividadRegistro()->format("Y-m-d"):null;

            $archivocapturaDto->id_tipo_archivo=  $valor->getIdTipoArchivo()->getId();
            $archivocapturaDto->tipoarchivo=  $valor->getIdTipoArchivo()->getNombreArchivo();

            $extnomb = explode(".", $valor->getUrlAlojamiento());
            $extnombf = $extnomb[1];

            $archivocapturaDto->tipo_extensiones= $extnombf;

            $archivocapturaDto->idestado=  $valor->getIdestado()->getId();
            $archivocapturaDto->nombre_status=  $valor->getIdestado()->getNombreStatus();
            $archivocapturaDto->id_limited_bloqueo=  $valor->getIdLimitedBloqueo()->getId();
            $archivocapturaDto->nombre_limited_bloqueo=  $valor->getIdLimitedBloqueo()->getNombreBloqueo();
            $archivocapturaDto->hashtag=  $valor->getHashtag();
            $archivocapturaDto->iduserarchivos= $recursosArchivos;
            $archivocapturaDto->historico= $historico;
           // $archivocapturaDto->bloquedo_por= $archivo_bloqueado;
            



            $dataarchivos[]=$archivocapturaDto;                
        }

        return New JsonResponse(["count"=>count($paginatorTotalCount),"data"=>$dataarchivos]);

    }


   /**
     * Identificar permisos de extensiones de archivos.
     */
    public function postextension($file,$validator,$helper,$em): string  {
        //$extens = $file->guessExtension();
        $extens = $file->getClientOriginalExtension();
        $sql = " SELECT a.id,a.id_tipo_archivos_id ,a.tipo_extesiones,a.id_status_tipo_archivo_id as stat FROM `archivos_extesionesd` a 
        where a.tipo_extesiones='".$extens."';";
        //$conn = $em->getEntityManager()->getConnection();
        $conn = $em->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result= $stmt->fetchAll();
        $dataTotal=array();
        $colorprogress='';
        foreach($result as $claveResult=>$valorResult){
            if($valorResult["stat"]==1){
                return $valorResult["id_tipo_archivos_id"];
            }else{
                return 0;
            }
        }
        return false;
    }


     /**
     * Create Registro Archivos.
     */
    public function post($data,$idexts,$arrusers,$arrhashtag,$file,$validator,$helper,$em): string  {
        if($data['Fecha_extrema_inicio']!="null"){
           $fech_fincompara_startDate = strtotime($data['Fecha_extrema_inicio']);
        }
        if($data['Fecha_extrema_fin']!="null"){
           $fech_fincompara_endDate = strtotime($data['Fecha_extrema_fin']);
        }
        //$fecha1 = date("Y-m-d");
        //$fecha = strtotime($fecha1);
        if($data['Fecha_extrema_inicio']!="null" And $data['Fecha_extrema_fin']!="null"){
            if(($fech_fincompara_endDate < $fech_fincompara_startDate)) {
                return new JsonResponse(['msg'=>'Verifique el rango de fecha es incorrecto debe ser mayor o igual : '.$fech_fincompara_endDate],409);  
            } 
        }

        //var_dump($data);die;
        $entityManager = $this->getEntityManager();
        $entity = new ControlArchivoDigital();

        $entityPais =$entityManager->getRepository(Pais::class)->find($data['id_pais']);
        if (!$entityPais) {
            return new JsonResponse(['msg'=>'No existen Registros de PAIS con el id: '.$data['id_pais']],404);  
        }
        $entity->setIdPais($entityPais->getId());

        /* $entityEstado =$entityManager->getRepository(Estado::class)->find($data['id_estado']);
        if (!$entityEstado) {
            return new JsonResponse(['msg'=>'No existen Registros de Estado con el id: '.$data['id_estado']],404);  
        }
        $entity->setIdEstado($entityEstado->getId());
        $entity->setIdregion($entityEstado->getRegionId()); */

         $sql = " SELECT * FROM `estado` e 
            where e.id=".$data['id_estado']." order by e.id Asc";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            //$result= $stmt->executeQuery();
            $stmt->execute();
            $result= $stmt->fetchAll();
            foreach($result as $claveResult=>$valorResultH){
                $entity->setIdEstado($valorResultH["id"]);
                $entity->setIdregion($valorResultH["idregion_id"]);

/*                 


                $query = $entityManager->createQueryBuilder();
                $allAppointmentsQuery = $query->select('Region')
                ->from(Region::class,'Region')
                ->Where('Region.id='.$valorResultH["region_id"])
                ->addOrderBy('Region.id', 'ASC')
                ->getQuery();
                $queryult = $query->getQuery();
                $dataRegion =  $queryult->execute();
                if($dataRegion!=null){
                    $entity->setIdregion($dataRegion[0]);
                    //$entity->setIdregion($dataRegion[0]);
                }else{
                return new JsonResponse(['msg'=>'No existen el id Region : '.$data['idregion']],404);
                }
 */
                /* $historico[]=array("id"=>$valorResultH["id"],
                "fecha_creacion_hist"=> $valorResultH["fecha_creacion_hist"],
                "tipo_operaciones"=>$valorResultH["operacion"],
                "comentario"=>$valorResultH["comentario"],
                "nemotecnico"=>$valorResultH["nemotecnico"],
                "nombre_apellido"=>$valorResultH["primer_nombre"].' '.$valorResultH["segundo_nombre"].' '.$valorResultH["primer_apellido"].' '.$valorResultH["segundo_apellido"],
                "email"=>$valorResultH["email"],
                ); */
            } 

/*          $query = $entityManager->createQueryBuilder();
        $allAppointmentsQuery = $query->select('Estado')
        ->from(Estado::class,'Estado')
        ->Where('Estado.id='.$data['id_estado'])
        ->addOrderBy('Estado.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $dataRegion =  $queryult->execute();
        if($dataRegion!=null){
            $entity->setIdEstado($dataRegion->getId());
            $entity->setIdregion($dataRegion->getRegionId());
            //$entity->setIdEstado($entityEstado->getId());
            //$entity->setIdregion($entityEstado->getRegionId());
        }else{
        return new JsonResponse(['msg'=>'No existen el id Estado : '.$data['id_estado']],404);
        } */
         


        $entityCiudad =$entityManager->getRepository(Ciudad::class)->find($data['id_ciudad']);
        if (!$entityCiudad) {
            return new JsonResponse(['msg'=>'No existen Registros de Ciudad con el id: '.$data['id_ciudad']],404);  
        }
        $entity->setIdCiudad($entityCiudad->getId());

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('EstructuraOrganizativa')
        ->from(EstructuraOrganizativa::class,'EstructuraOrganizativa')
        ->Where('EstructuraOrganizativa.id='.$data['id_estructura_organizativa'])
        ->addOrderBy('EstructuraOrganizativa.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $dataEstructuraOrganizativa =  $queryult->execute();
        if($dataEstructuraOrganizativa!=null){
            $entity->setIdEstructuraOrganizativa($dataEstructuraOrganizativa[0]);
        }else{
          return new JsonResponse(['msg'=>'No existen el id Estructura Organizativa : '.$data['id_estructura_organizativa']],404);
        }

        $entity->setAsuntos($data['asuntos']);
        $entity->setFechaFinConservac(!is_null($data['fecha_fin_conservac'])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data['fecha_fin_conservac'] )))):null);
        $entity->setSwArchivoFisico($data['sw_archivo_fisico']);
        $entity->setArgumentoJustificacion($data['argumento_justificacion']);

        if($data['num_expediente']!="null"){
          $entity->setNumExpediente($data['num_expediente']);
        }

        if($data['fecha_documento']!="null"){
           $entity->setFechaDocumento(!is_null($data['fecha_documento'])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data['fecha_documento'] )))):null);
        }
        
        if($data['id_tipo_almacen']!="null"){
            $query = $em->createQueryBuilder();
            $allAppointmentsQuery = $query->select('TipoAlmacen')
            ->from(TipoAlmacen::class,'TipoAlmacen')
            ->Where('TipoAlmacen.id='.$data['id_tipo_almacen'])
            ->addOrderBy('TipoAlmacen.id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $dataTipoAlmacen =  $queryult->execute();
            if($dataTipoAlmacen!=null){
                $entity->setIdTipoAlmacen($dataTipoAlmacen[0]);
            }else{
            return new JsonResponse(['msg'=>'No existen el id Tipo Almacen : '.$data['id_tipo_almacen']],404);
            }
        }

        if($data['idubica1']!="null"){
        //if(isset($data['idubica1'])){
            $query = $em->createQueryBuilder();
            $allAppointmentsQuery = $query->select('Ubicacion')
            ->from(Ubicacion::class,'Ubicacion')
            ->Where('Ubicacion.id='.$data['idubica1'])
            ->addOrderBy('Ubicacion.id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $dataUbicacion1 =  $queryult->execute();
            if($dataUbicacion1!=null){
                $entity->setIdubica1($dataUbicacion1[0]);
            }else{
            return new JsonResponse(['msg'=>'No existen el id Ubicación : '.$data['idubica1']],404);
            }
         //}
       }

       if($data['idubica2']!="null"){
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('Ubicacion')
        ->from(Ubicacion::class,'Ubicacion')
        ->Where('Ubicacion.id='.$data['idubica2'])
        ->addOrderBy('Ubicacion.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $dataUbicacion2 =  $queryult->execute();
        if($dataUbicacion2!=null){
            $entity->setIdubica2($dataUbicacion2[0]);
        }else{
        return new JsonResponse(['msg'=>'No existen el id Ubicación : '.$data['idubica2']],404);
        }
     }

     if($data['idubica3']!="null"){
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('Ubicacion')
        ->from(Ubicacion::class,'Ubicacion')
        ->Where('Ubicacion.id='.$data['idubica3'])
        ->addOrderBy('Ubicacion.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $dataUbicacion3 =  $queryult->execute();
        if($dataUbicacion3!=null){
            $entity->setIdubica3($dataUbicacion3[0]);
        }else{
        return new JsonResponse(['msg'=>'No existen el id Ubicación : '.$data['idubica3']],404);
        }
     }

     if($data['idubica4']!="null"){
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('Ubicacion')
        ->from(Ubicacion::class,'Ubicacion')
        ->Where('Ubicacion.id='.$data['idubica4'])
        ->addOrderBy('Ubicacion.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $dataUbicacion4 =  $queryult->execute();
        if($dataUbicacion4!=null){
            $entity->setIdubica4($dataUbicacion4[0]);
        }else{
        return new JsonResponse(['msg'=>'No existen el id Ubicación : '.$data['idubica4']],404);
        }
     }


        

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('SubSerie')
        ->from(SubSerie::class,'SubSerie')
        ->Where('SubSerie.id='.$data['codigo_serie_subserie'])
        ->addOrderBy('SubSerie.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $dataSerieSubserie =  $queryult->execute();
        if($dataSerieSubserie!=null){
            $entity->setCodigoSerieSubserie($dataSerieSubserie[0]);
        }else{
        return new JsonResponse(['msg'=>'No existen el id Serie Subserie : '.$data['codigo_serie_subserie']],404);
        }

        $status=1;
        //$data['id_status_tipoestado']
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('TipoStatusArchivo')
        ->from(TipoStatusArchivod::class,'TipoStatusArchivo')
        ->Where('TipoStatusArchivo.id='.$status )
        ->addOrderBy('TipoStatusArchivo.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $dataTipoStatusArchivo =  $queryult->execute();
        if($dataTipoStatusArchivo!=null){
            $entity->setIdStatusTipoestado($dataTipoStatusArchivo[0]);
        }else{
        return new JsonResponse(['msg'=>'No existen Registros de Tipo Status con el id : '.$status],404);
        }
       
        $entity->setCantidadCaja($data['cantidad_caja']);


        //$entity->setCantidadEstuche($data['cantidad_estuche']);

        $currentUserentrega =$entityManager->getRepository(User::class)->find($data['id_user_entrega']);
        $entity->setIdUserEntrega($currentUserentrega->getId());

        if($data['idcontenido_caja']!="null"){
            $query = $em->createQueryBuilder();
            $allAppointmentsQuery = $query->select('ContenidoCaja')
            ->from(ContenidoCaja::class,'ContenidoCaja')
            ->Where('ContenidoCaja.id='.$data['idcontenido_caja'])
            ->addOrderBy('ContenidoCaja.id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $dataContenidoCaja =  $queryult->execute();
            if($dataContenidoCaja!=null){
                $entity->setIdcontenidoCaja($dataContenidoCaja[0]);
            }else{
            return new JsonResponse(['msg'=>'No existen Registros Contenido Caja con el id : '.$data['idcontenido_caja']],404);
            }
      }else{


      }



        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity->setCreateBy($currentUser->getUserName());
        $entity->setCreateAt(new \DateTime('now'));

        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        if($empresa)
            $entity->setIdempresa($empresa->getId());
            $em->persist($entity);
            $em->flush();

          $idarchivodigital=$entity->getId();
        //***************FIN INSERCION DE ARCHIVO DIGITAL ************** */

        $tipoarchivo = $idexts;
        //variable para el bloqueo de archivo cuando empiece a trabajar esta parte hay que tomarla desde $data
        $id_limited_bloqueo=3;
        $dataArea=[];
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entityManager = $em;
        $opciones = new Archivosd();
        $opciones->setTitulo($data['titulo']);
        $opciones->setNombreOriginal($data['nombre_original']);
        $opciones->setDescripcionArchivo($data['descripcion_archivo']);
        $opciones->setTamano($data['tamano']);
        $opciones->setPublico($data['publico']);
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('TipoEstado')
        ->from(TipoEstadod::class,'TipoEstado')
        ->Where('TipoEstado.id='.$data['idestado'])
        ->addOrderBy('TipoEstado.nombre_status', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $dataTipoEstado =  $queryult->execute();
        if($dataTipoEstado!=null){
            $opciones->setIdestado($dataTipoEstado[0]);
        }else{
          return new JsonResponse(['msg'=>'No existen el id Tipo Estado : '.$data['idestado']],404);
        }
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('TipoLimitedBloqueo')
        ->from(TipoLimitedBloqueod::class,'TipoLimitedBloqueo')
        ->Where('TipoLimitedBloqueo.id='.$id_limited_bloqueo)
        ->addOrderBy('TipoLimitedBloqueo.nombre_bloqueo', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $dataTipoLimitedBloqueo =  $queryult->execute();
        if($dataTipoLimitedBloqueo!=null){
            $opciones->setIdLimitedBloqueo($dataTipoLimitedBloqueo[0]);
        }else{
          return new JsonResponse(['msg'=>'No existen el id Tipo Limited Bloqueo : '.$id_limited_bloqueo],404);
        }
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('TipoArchivo')
        ->from(TipoArchivod::class,'TipoArchivo')
        ->Where('TipoArchivo.id='.$tipoarchivo)
        ->addOrderBy('TipoArchivo.nombre_archivo', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $dataTipoArchivo =  $queryult->execute();
        if($dataTipoArchivo!=null){
            $opciones->setIdTipoArchivo($dataTipoArchivo[0]);
        }else{
          return new JsonResponse(['msg'=>'No existen el id Tipo Archivo : '.$tipoarchivo],404);
        }
        $opciones->setUrlAlojamiento('actualizar');
        $opciones->setNemotecnico('actualizar');
        $opciones->setFechaActividadRegistro(date('Y-m-d h:i:s', time() - 84600 * 1));
        $opciones->setHashtag($arrhashtag);
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        if($empresa)
           $opciones->setIdempresa($empresa->getId()); 

           $opciones->setFolios($data['folios']);
           $opciones->setNumDelaCaja($data['num_dela_caja']);
           //$opciones->setNumDelaEstuches($data['num_dela_estuches']);

           if($data['Fecha_extrema_inicio']!="null"){
               $opciones->setFechaExtremaInicio(!is_null($data['Fecha_extrema_inicio'])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data['Fecha_extrema_inicio'] )))):null);  
           }
           if($data['Fecha_extrema_fin']!="null"){
               $opciones->setFechaExtremaFin(!is_null($data['Fecha_extrema_fin'])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data['Fecha_extrema_fin'] )))):null);   
           }
          

           //pendiente con este debe estar relacionado con la tabla Control Archivo Digital
           
           $query = $em->createQueryBuilder();
           $allAppointmentsQuery = $query->select('ControlArchivoDigital')
           ->from(ControlArchivoDigital::class,'ControlArchivoDigital')
           ->Where('ControlArchivoDigital.id='.$idarchivodigital)
           ->addOrderBy('ControlArchivoDigital.id', 'ASC')
           ->getQuery();
           $queryult = $query->getQuery();
           $dataControlArchivoDigital =  $queryult->execute();
           if($dataControlArchivoDigital!=null){
              $opciones->setIdControlArchivoDigital($dataControlArchivoDigital[0]);
           }else{
              return new JsonResponse(['msg'=>'No existen el id Control de Archivo Digital : '.$idarchivodigital],404);
           }

           $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
           $opciones->setCreateBy($currentUser->getUserName());
           $opciones->setCreateAt(new \DateTime('now'));

        $entityManager->persist($opciones);
        $entityManager->flush();


        //$opciones->getId()



        $iduserpmo =  $this->security->getUser()->getId();
        $opcionesuser = new UsuarioArchivosd();
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('Archivos')
        ->from(Archivosd::class,'Archivos')
        ->Where('Archivos.id='.$opciones->getId())
        ->getQuery();
        $queryult = $query->getQuery();
        $dataArchivo =  $queryult->execute();
        if($dataArchivo!=null){
           $opcionesuser->setIduserarchivos($dataArchivo[0]);
        }else{
          return new JsonResponse(['msg'=>'No existen el Archivo : '.$opciones->getId()],404);
        }
        $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
        if (!$currentUser) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
        }
        $opcionesuser->setIdusuario($currentUser->getId());

        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        if($empresa)
           $opcionesuser->setIdempresa($empresa->getId()); 

        

        $entityManager->persist($opcionesuser);
        $entityManager->flush();
        $cont=0; 
        $usercorr='';
        $arrnotifica='';
        if ($arrusers) {
        foreach($arrusers as $optionsuser){
            if ($iduserpmo!=$optionsuser) {
                $opcionesuser = new UsuarioArchivosd();
                $query = $em->createQueryBuilder();
                $allAppointmentsQuery = $query->select('Archivos')
                ->from(Archivosd::class,'Archivos')
                ->Where('Archivos.id='.$opciones->getId())
                ->getQuery();
                $queryult = $query->getQuery();
                $dataArchivo =  $queryult->execute();
                if($dataArchivo!=null){
                $opcionesuser->setIduserarchivos($dataArchivo[0]);
                }else{
                return new JsonResponse(['msg'=>'No existen el Archivo : '.$opciones->getId()],404);
                }
                $query = $entityManagerDefault->createQueryBuilder();
                $allAppointmentsQuery = $query->select('User')
                ->from(User::class,'User')
                ->Where('User.id='.$optionsuser)
                ->getQuery();
                $queryult = $query->getQuery();
                $dataArchivo =  $queryult->execute();
                $currentUser =$entityManagerDefault->getRepository(User::class)->find($optionsuser);
                if (!$currentUser) {
                    return new JsonResponse(['msg'=>'No existen el Usuario id : '.$optionsuser],404);  
                }
                $opcionesuser->setIdusuario($currentUser->getId());
                if ($cont==0) {
                    $usercorr =  $currentUser->getEmail();
                    $cont++;
                }else{
                    $usercorr = $usercorr . '|' . $currentUser->getEmail();
                }
                $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                   $opcionesuser->setIdempresa($empresa->getId());  
                $entityManager->persist($opcionesuser);
                $entityManager->flush();
            }
        }
        //$arrnotifica = array('destinatary' => $usercorr, 'message' => 'Ha sido agregado como colaborador al documento ');
       }
        $DateAndTime = date('his', time());
        $nemotecnico = $opciones->getId() .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
        //$nemotecnico = 1 .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        //$urlarchv=('/archivos/giep/documentos/'.$originalFilename.'-'.$nemotecnico.'.'.$file->getClientOriginalExtension());
        $urlarchv=('/archivos/giep/documentosdigital/'.$nemotecnico.'.'.$file->getClientOriginalExtension());
        $queryresp = $em->createQueryBuilder();
           $queryresp->update(Archivosd::class,'archivos')
          ->set('archivos.nemotecnico', ':nemotecnico')
          ->set('archivos.url_alojamiento', ':url_alojamiento')
          ->Where('archivos.id='.$opciones->getId())
          ->setParameter('nemotecnico', $nemotecnico)
          ->setParameter('url_alojamiento', $urlarchv)
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
        $quqe = intval($data['tamano']);
        /* tamano, url_alojamiento, ancho, altura, duracion, tags, fecha_creacion_hist, nemotecnico, comentario, iduser_id,
         id_orientacion_id, idarchivo_id, id_tipo_operaciones_id */
        $opcioHistArchivoContVersion = new HistArchivoContVersiond();
        $opcioHistArchivoContVersion->setTamano($data['tamano']);

        $opcioHistArchivoContVersion->setNombreOriginal($data['nombre_original']);

        $opcioHistArchivoContVersion->setUrlAlojamiento($urlarchv);

        $opcioHistArchivoContVersion->setFechaCreacionHist(date('Y-m-d h:i:s', time() - 84600 * 1));
        
        $opcioHistArchivoContVersion->setNemotecnico($nemotecnico);

        $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
        if (!$currentUser) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
        }
        $opcioHistArchivoContVersion->setIduser($currentUser->getId());
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('TipoOrientacion')
        ->from(TipoOrientaciond::class,'TipoOrientacion')
        ->Where('TipoOrientacion.id='."1")
        ->getQuery();
        $queryult = $query->getQuery();
        $dataTipoOrientacion =  $queryult->execute();
        if($dataArchivo!=null){
            $opcioHistArchivoContVersion->setIdOrientacion($dataTipoOrientacion[0]);
        }else{
          return new JsonResponse(['msg'=>'No existen el Archivo : '.$opciones->getId()],404);
        }

        $query = $em->createQueryBuilder();
                $allAppointmentsQuery = $query->select('Archivos')
                ->from(Archivosd::class,'Archivos')
                ->Where('Archivos.id='.$opciones->getId())
                ->getQuery();
                $queryult = $query->getQuery();
                $dataArchivo =  $queryult->execute();
                if($dataArchivo!=null){
                  $opcioHistArchivoContVersion->setIdarchivo($dataArchivo[0]);
                }else{
                return new JsonResponse(['msg'=>'No existen el Archivo : '.$opciones->getId()],404);
                }

        $query = $em->createQueryBuilder();
                $allAppointmentsQuery = $query->select('TipoOperaciones')
                ->from(TipoOperacionesd::class,'TipoOperaciones')
                ->Where('TipoOperaciones.id='."1")
                ->getQuery();
                $queryult = $query->getQuery();
                $dataTipoOperaciones =  $queryult->execute();
                if($dataTipoOperaciones!=null){
                    $opcioHistArchivoContVersion->setIdTipoOperaciones($dataTipoOperaciones[0]);
                }else{
                return new JsonResponse(['msg'=>'No existen el Archivo : '."1"],404);
                }

               $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                 $opcioHistArchivoContVersion->setIdempresa($empresa->getId());   


        $entityManager->persist($opcioHistArchivoContVersion);
        $entityManager->flush(); 

         return $nemotecnico.'#'.$usercorr;
    }
    

    //Buscar el id del archivo y Mostrarlo 
    public function getBuscarArchivosById($id,$dirserv,$user,$em){
        try {
        $entityManager = $this->getEntityManager();
        $entityManagerDefault = $this->getEntityManager();
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        $dataRol=[];
        $query = $em->createQueryBuilder('a');
        $query->select("b,a,t,e,l")
        ->from('App\Entity\DocumentoDigital\Archivosd', 'a')
        ->innerJoin('a.iduserarchivos', 'b')
        ->innerJoin('a.id_tipo_archivo', 't')
        ->innerJoin('a.idestado', 'e')
        ->innerJoin('a.id_limited_bloqueo', 'l'); 
        $query->Where("a.id =".$id);
        $query->orderBy('a.id', 'ASC');   
        $query->getQuery();
        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery();	

        $dataarchivos=array();
        $base64_arch="";
        $urles="";
        foreach($paginator as $clave=>$valor){
            $nomborg = explode("/", $valor->getUrlAlojamiento()); 
            $nomborgf = explode(".", $nomborg[4]);

            //$dirserv="/home/pafarco1/public_html/bofficegiepstage/public";

            // Para producción 
            $urles = $dirserv . $valor->getUrlAlojamiento();

            //$urles = 'C:\xampp\htdocs\giep/public' . $valor->getUrlAlojamiento();
            
            $imgbinary = fread(fopen($urles, "r"), filesize($urles));

            $filetype = $nomborgf[1];
            if ($filetype=='docx') {
                $base64_arch = 'data:@file/vnd.openxmlformats-officedocument.wordprocessingml.document'.';base64,' . base64_encode($imgbinary);
            }else if ($filetype=='doc') {
                $base64_arch = 'data:@file/msword;base64,' . base64_encode($imgbinary);    
            }else if ($filetype=='pptx') {
                $base64_arch = 'data:@file/vnd.openxmlformats-officedocument.presentationml.presentation'.';base64,' . base64_encode($imgbinary);
            }else if ($filetype=='ppt') {
                $base64_arch = 'data:@file/vnd.ms-powerpoint'.';base64,' . base64_encode($imgbinary);
            }else if ($filetype=='xlsx') {
                $base64_arch = 'data:@file/octet-stream'.';base64,' . base64_encode($imgbinary);
            }else if ($filetype=='xls') {
                $base64_arch = 'data:@file/msexcel'.';base64,' . base64_encode($imgbinary);
            }else if ($filetype=='png') {
                $base64_arch = 'data:image/png'.';base64,' . base64_encode($imgbinary);    
            }else if ($filetype=='jpg') {
                $base64_arch = 'data:image/jpeg'.';base64,' . base64_encode($imgbinary);        
            }else if ($filetype=='jpeg') {
                $base64_arch = 'data:image/jpeg'.';base64,' . base64_encode($imgbinary);        
            }else if ($filetype=='gif') {
                $base64_arch = 'data:image/gif'.';base64,' . base64_encode($imgbinary);            
            }else if ($filetype=='mp4') {
                $base64_arch = 'data:image/mp4'.';base64,' . base64_encode($imgbinary);            
            }else if ($filetype=='ods') {
                $base64_arch = 'data:@file/vnd.oasis.opendocument.spreadsheet'.';base64,' . base64_encode($imgbinary);                
            }else if ($filetype=='odp') {
                $base64_arch = 'data:@file/vnd.oasis.opendocument.presentation'.';base64,' . base64_encode($imgbinary);                    
            }else{
                $base64_arch = 'data:@file/'. $filetype .';base64,' . base64_encode($imgbinary);
            }

            $opcioHistArchivoContVersion = new HistArchivoContVersiond();
            $opcioHistArchivoContVersion->setUrlAlojamiento($valor->getUrlAlojamiento());
            $opcioHistArchivoContVersion->setTamano($valor->getTamano());

            $opcioHistArchivoContVersion->setNombreOriginal($valor->getNombreOriginal());
    
            $opcioHistArchivoContVersion->setFechaCreacionHist(date('Y-m-d h:i:s', time() - 84600 * 1));
            
            $opcioHistArchivoContVersion->setNemotecnico($valor->getNemotecnico());
    
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            if (!$currentUser) {
                return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
            }
            //$opcionesuser->setIdusuario($currentUser);
            $opcioHistArchivoContVersion->setIduser($currentUser->getId());
    

            $query = $em->createQueryBuilder();
            $allAppointmentsQuery = $query->select('TipoOrientacion')
            ->from(TipoOrientaciond::class,'TipoOrientacion')
            ->Where('TipoOrientacion.id='."1")
            ->getQuery();
            $queryult = $query->getQuery();
            $dataTipoOrientacion =  $queryult->execute();
            if($dataTipoOrientacion!=null){
                $opcioHistArchivoContVersion->setIdOrientacion($dataTipoOrientacion[0]);
            }else{
               return new JsonResponse(['msg'=>'No existen el Tipo Orientacion : '."1"],404);
            }
    
/*             $currentOrientacion =$entityManager->getRepository(TipoOrientacion::class)->find(1);
            if (!$currentOrientacion) {
                return new JsonResponse(['msg'=>'No existen el Tipo Orientacion id : '."1"],404);  
            }
            $opcioHistArchivoContVersion->setIdOrientacion($currentOrientacion); */

            $query = $em->createQueryBuilder();
            $allAppointmentsQuery = $query->select('Archivos')
            ->from(Archivosd::class,'Archivos')
            ->Where('Archivos.id='.$valor->getId())
            ->getQuery();
            $queryult = $query->getQuery();
            $dataArchivo =  $queryult->execute();
            if($dataArchivo!=null){
                $opcioHistArchivoContVersion->setIdarchivo($dataArchivo[0]);
            }else{
               return new JsonResponse(['msg'=>'No existen el Archivo : '."1"],404);
            }
    
/*             $currentArchivos =$entityManager->getRepository(Archivos::class)->find($valor->getId());
            if (!$currentArchivos) {
                return new JsonResponse(['msg'=>'No existen el Archivo id : '.$valor->getId()],404);  
            }
            $opcioHistArchivoContVersion->setIdarchivo($currentArchivos); */

            $query = $em->createQueryBuilder();
            $allAppointmentsQuery = $query->select('TipoOperaciones')
            ->from(TipoOperacionesd::class,'TipoOperaciones')
            ->Where('TipoOperaciones.id='."12")
            ->getQuery();
            $queryult = $query->getQuery();
            $dataTipoOperaciones =  $queryult->execute();
            if($dataTipoOperaciones!=null){
                $opcioHistArchivoContVersion->setIdTipoOperaciones($dataTipoOperaciones[0]);
            }else{
               return new JsonResponse(['msg'=>'No existen el Tipo Operaciones : '."1"],404);
            }
    
            /* $currentOperaciones =$entityManager->getRepository(TipoOperaciones::class)->find(12);
            if (!$currentOperaciones) {
                return new JsonResponse(['msg'=>'No existen el Tipo Operación id : '."6"],404);  
            }
            $opcioHistArchivoContVersion->setIdTipoOperaciones($currentOperaciones); */

            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                 $opcioHistArchivoContVersion->setIdempresa($empresa->getId());   
    
            $em->persist($opcioHistArchivoContVersion);
            $em->flush(); 
            
            
            
            /* $opcioHistArchivoBloqueado = new HistArchivoBloqueado();
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            if (!$currentUser) {
                return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
            }
            $opcioHistArchivoBloqueado->setIduser($currentUser);
            $currentArchivos =$entityManager->getRepository(Archivos::class)->find($valor->getId());
            if (!$currentArchivos) {
                return new JsonResponse(['msg'=>'No existen el Archivo id : '.$valor->getId()],404);  
            }
            $opcioHistArchivoBloqueado->setIdarchivo($currentArchivos);
            $opcioHistArchivoBloqueado->setFechaBloqueo(date('Y-m-d h:i:s', time() - 84600 * 1));

            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                $opcioHistArchivoBloqueado->setIdempresa($empresa);   
                 
            $entityManager->persist($opcioHistArchivoBloqueado);
            $entityManager->flush();  */


            /* $opcioUsuarioArchivoBloqueado = new UsuarioArchivoBloqueado();
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            if (!$currentUser) {
                return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
            }
            $opcioUsuarioArchivoBloqueado->setIduser($currentUser);
            $currentArchivos =$entityManager->getRepository(Archivos::class)->find($valor->getId());
            if (!$currentArchivos) {
                return new JsonResponse(['msg'=>'No existen el Archivo id : '.$valor->getId()],404);  
            }
            $opcioUsuarioArchivoBloqueado->setIdarchivo($currentArchivos);
            $opcioUsuarioArchivoBloqueado->setFechaBloqueo(date('Y-m-d h:i:s', time() - 84600 * 1));
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                $opcioUsuarioArchivoBloqueado->setIdempresa($empresa);   

            $entityManager->persist($opcioUsuarioArchivoBloqueado);
            $entityManager->flush();  
            $sql = "update archivos set id_limited_bloqueo_id=1 where id=".$id." ";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();*/


            $nomorg = explode(".", $valor->getNombreOriginal()); 

        }
        if ($base64_arch) {
            return New JsonResponse(["file"=>$base64_arch,"title"=>$nomorg[0].'-'.$nomborgf[0],"extension"=>$nomborgf[1],"nemotecnico"=>$valor->getNemotecnico(),"filenemotecnico"=>$urles]); 
        }else{
            return new JsonResponse(['msg'=>'No existe el Archivo: '.$id],404);  
        }

        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error descargando el archivo '.$urles],500);
        }
        
    }


    /**
     * Update Archivos.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Archivosd::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $acmhastag = '';
        $cuenta = 0;
        foreach ($data["hashtag"] as $key => $value) {
            if ($cuenta==0) {
                $acmhastag =  '["'.$value .'"';
            }else{
                $acmhastag = $acmhastag .  ',' .'"' .$value .'"';
            }
            $cuenta++;
        }
        $acmhastag = $acmhastag .  ']';
        $entity->setTitulo($data["titulo"]);
        $entity->setDescripcionArchivo($data["descripcion_archivo"]);
        $entity->setHashtag($acmhastag);
        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                  $entity->setIdempresa($empresa);   
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return new JsonResponse($messages,500);
        }else{
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }
    }
    
    
    /**
     * Update Desbloqueo Archivos Administrador.
     */
 public function putadmindesbloqueoarchivos($data,$idarchv,$validator,$helper){
  
    $sql = " SELECT a.* FROM `archivosd` a 
    where a.id=".$idarchv."  and id_limited_bloqueo_id=1 order by id desc ";
    $conn = $this->getEntityManager()->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resultest= $stmt->fetchAll();
    if(true === is_array($resultest)){

   $sql = " SELECT a.* FROM `hist_archivo_cont_versiond` a 
   where a.idarchivo_id=".$idarchv." order by id desc ";
   $conn = $this->getEntityManager()->getConnection();
   $stmt = $conn->prepare($sql);
   $stmt->execute();

   $result= $stmt->fetchAll();
   //$result= $stmt->executeQuery();
   $dataTotal=array();
   $colorprogress='';
   foreach($result as $claveResult=>$valorResult){

    $entityManager = $this->getEntityManager();
    $sql = "update hist_archivo_bloqueadod set fecha_desbloqueo='".date('Y-m-d h:i:s', time() - 84600 * 1)."'  where idarchivo_id=".$idarchv." and iduser_id=".$valorResult["iduser_id"]." ";
    $conn = $this->getEntityManager()->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
 
    $DateAndTime = date('his', time());
    $nemotecnico = $idarchv .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
    $sql = "update archivosd set id_limited_bloqueo_id=3 where id=".$idarchv." ";
    $conn = $this->getEntityManager()->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
 


       //return $valorResult["nemotecnico"].'#'.$valorResult["iduser_id"].'#'.$valorResult["id_limited_bloqueo_id"].'#'.$valorResult["url_alojamiento"];
       $opcioHistArchivoContVersion = new HistArchivoContVersiond();
       $opcioHistArchivoContVersion->setTamano($valorResult["tamano"]);
       $opcioHistArchivoContVersion->setNombreOriginal($valorResult["nombre_original"]);
       $opcioHistArchivoContVersion->setUrlAlojamiento($valorResult["url_alojamiento"]);
       $opcioHistArchivoContVersion->setFechaCreacionHist(date('Y-m-d h:i:s', time() - 84600 * 1));
       $opcioHistArchivoContVersion->setNemotecnico($valorResult["nemotecnico"]);
       $opcioHistArchivoContVersion->setComentario("Desbloqueado por Administrador");
       

       $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
       if (!$currentUser) {
           return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
       }
       //$opcionesuser->setIdusuario($currentUser);
       $opcioHistArchivoContVersion->setIduser($currentUser);
       $currentOrientacion =$entityManager->getRepository(TipoOrientaciond::class)->find(1);
       if (!$currentOrientacion) {
           return new JsonResponse(['msg'=>'No existen TipoOrientaciond id : '."1"],404);  
       }
       $opcioHistArchivoContVersion->setIdOrientacion($currentOrientacion);
       $currentArchivos =$entityManager->getRepository(Archivosd::class)->find($idarchv);
       if (!$currentArchivos) {
           return new JsonResponse(['msg'=>'No existen Archivosd id : '],404);  
       }
       $opcioHistArchivoContVersion->setIdarchivo($currentArchivos);
       $currentOperaciones =$entityManager->getRepository(TipoOperacionesd::class)->find(8);
       if (!$currentOperaciones) {
           return new JsonResponse(['msg'=>'No existen TipoOperacionesd id : '."1"],404);  
       }
       $opcioHistArchivoContVersion->setIdTipoOperaciones($currentOperaciones);
       $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                $opcioHistArchivoContVersion->setIdempresa($empresa);   
       $entityManager->persist($opcioHistArchivoContVersion);
       $entityManager->flush();   
      break;
   } 

   $sql = "DELETE FROM usuario_archivo_bloqueadod where idarchivo_id=".$idarchv." ";
   $conn = $this->getEntityManager()->getConnection();
   $stmt = $conn->prepare($sql);
   $stmt->execute(); 

   return new JsonResponse(['msg'=>'Registro Desbloqueado: '.$idarchv],200);

  }else{
    return new JsonResponse(['msg'=>'El registro no se encuentra bloqueado: '.$idarchv],200);
  }
 }

    
    
    
    
   /**
     * consultar nemotecnico para subir archivos versionados.
     */
    public function postnemotecnicoversion($idfile,$validator,$helper): string  {

        $sql = " SELECT us.email,u.iduserarchivos_id FROM `user` us 
        inner join usuario_archivosd u on us.id = u.idusuario_id
        where u.iduserarchivos_id=".$idfile.";";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resultcolv= $stmt->fetchAll();
        $cont=0;
        $usercorr='';
        foreach($resultcolv as $claveResult=>$valorResultColv){
            if ($cont==0) {
                $usercorr =  $valorResultColv["email"];
                $cont++;
            }else{
                $usercorr = $usercorr . '|' . $valorResultColv["email"];
            }
        } 

        $sql = " SELECT a.*,u.iduser_id FROM `archivosd` a 
            inner join usuario_archivo_bloqueadod u on a.id = u.idarchivo_id
            where a.id=".$idfile.";";

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result= $stmt->fetchAll();
         //$result= $stmt->executeQuery();
        $dataTotal=array();
        $colorprogress='';
        foreach($result as $claveResult=>$valorResult){
              return $valorResult["nemotecnico"].'#'.$valorResult["iduser_id"].'#'.$valorResult["id_limited_bloqueo_id"].'#'.$valorResult["url_alojamiento"].'#'.$usercorr;
        }

        $sql1 = " SELECT a.*,H.* FROM `archivosd` a 
            inner join hist_archivo_cont_versiond H on a.id = H.idarchivo_id
            where a.id=".$idfile.";";  
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql1);
        $stmt->execute();
        $result= $stmt->fetchAll();
         //$result= $stmt->executeQuery();
        $dataTotal=array();
        $colorprogress='';
        foreach($result as $claveResult=>$valorResult){
              return $valorResult["nemotecnico"].'#'.$valorResult["iduser_id"].'#'.$valorResult["id_limited_bloqueo_id"].'#'.$valorResult["url_alojamiento"].'#'.$usercorr;
        } 


        return false;
    }



    
    //Actualizar version archivos
public function postactualizarversion($idarchv,$urlarchv,$iduserbloq,$extens,$comentarios,$validator,$helper){
     $entityManager = $this->getEntityManager();
    $sql = "update hist_archivo_bloqueadod set fecha_desbloqueo='".date('Y-m-d h:i:s', time() - 84600 * 1)."'  where idarchivo_id=".$idarchv." and iduser_id=".$iduserbloq." ";
    $conn = $this->getEntityManager()->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $DateAndTime = date('his', time());
    $nemotecnico = $idarchv .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
    $sql = "update archivosd set id_limited_bloqueo_id=3,nemotecnico='".$nemotecnico."', url_alojamiento='".'/archivos/giep/documentos/'.$nemotecnico.'.'.$extens."'  where id=".$idarchv." ";
    $conn = $this->getEntityManager()->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $sql = " SELECT a.* FROM `hist_archivo_cont_versiond` a 
    where a.idarchivo_id=".$idarchv." order by id desc ";
    $conn = $this->getEntityManager()->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result= $stmt->fetchAll();
    //$result= $stmt->executeQuery();
    $dataTotal=array();
    $colorprogress='';
    foreach($result as $claveResult=>$valorResult){
        //return $valorResult["nemotecnico"].'#'.$valorResult["iduser_id"].'#'.$valorResult["id_limited_bloqueo_id"].'#'.$valorResult["url_alojamiento"];
        $opcioHistArchivoContVersion = new HistArchivoContVersiond();
        $opcioHistArchivoContVersion->setTamano($valorResult["tamano"]);
        $opcioHistArchivoContVersion->setNombreOriginal($valorResult["nombre_original"]);
        $opcioHistArchivoContVersion->setUrlAlojamiento('/archivos/giep/documentos/'.$nemotecnico.'.'.$extens);
        $opcioHistArchivoContVersion->setFechaCreacionHist(date('Y-m-d h:i:s', time() - 84600 * 1));
        $opcioHistArchivoContVersion->setNemotecnico($nemotecnico);
        $opcioHistArchivoContVersion->setComentario($comentarios);
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        if (!$currentUser) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
        }
        //$opcionesuser->setIdusuario($currentUser);
        $opcioHistArchivoContVersion->setIduser($currentUser);
        $currentOrientacion =$entityManager->getRepository(TipoOrientaciond::class)->find(1);
        if (!$currentOrientacion) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '."1"],404);  
        }
        $opcioHistArchivoContVersion->setIdOrientacion($currentOrientacion);
        $currentArchivos =$entityManager->getRepository(Archivosd::class)->find($idarchv);
        if (!$currentArchivos) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '.$opciones->getId()],404);  
        }
        $opcioHistArchivoContVersion->setIdarchivo($currentArchivos);
        $currentOperaciones =$entityManager->getRepository(TipoOperacionesd::class)->find(1);
        if (!$currentOperaciones) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '."1"],404);  
        }
        $opcioHistArchivoContVersion->setIdTipoOperaciones($currentOperaciones);
        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                $opcioHistArchivoContVersion->setIdempresa($empresa);   
        $entityManager->persist($opcioHistArchivoContVersion);
        $entityManager->flush();   
       break;
    } 
    $sql = "DELETE FROM usuario_archivo_bloqueadod where idarchivo_id=".$idarchv." ";
    $conn = $this->getEntityManager()->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $nemotecnico;
}


    /**
     * Update Cambios Status Archivos.
     */
    public function putcambiostatusarchivo($data,$validator,$helper){
  
        $sql = " SELECT a.* FROM `archivosd` a 
        where a.id=".$data["id_archivo"]." order by id desc ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resultest= $stmt->fetchAll();
        if(true === is_array($resultest)){

       $sql = " SELECT a.* FROM `hist_archivo_cont_versiond` a 
       where a.idarchivo_id=".$data["id_archivo"]." order by id desc ";
       $conn = $this->getEntityManager()->getConnection();
       $stmt = $conn->prepare($sql);
       $stmt->execute();
    
       $result= $stmt->fetchAll();
       //$result= $stmt->executeQuery();
       $dataTotal=array();
       $colorprogress='';
       foreach($result as $claveResult=>$valorResult){
    
        $entityManager = $this->getEntityManager();
        $sql = "update archivosd set idestado_id=".$data["id_estado"]." where id=".$data["id_archivo"]." ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();

                //bloquear archivo ********************************************************************
        
        if($data["id_estado"] === 3 or $data["id_estado"] === 4 or $data["id_estado"] === 5 or $data["id_estado"] === 2 ){

        $sql = "update archivosd set id_limited_bloqueo_id=1 where id=".$data["id_archivo"]." ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();


        $opcioHistArchivoBloqueado = new HistArchivoBloqueadod();
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        if (!$currentUser) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
        }
        $opcioHistArchivoBloqueado->setIduser($currentUser);
        $currentArchivos =$entityManager->getRepository(Archivosd::class)->find($data["id_archivo"]);
        if (!$currentArchivos) {
            return new JsonResponse(['msg'=>'No existen el Archivo id : '.$data["id_archivo"]],404);  
        }
        $opcioHistArchivoBloqueado->setIdarchivo($currentArchivos);
        $opcioHistArchivoBloqueado->setFechaBloqueo(date('Y-m-d h:i:s', time() - 84600 * 1));
        $entityManager->persist($opcioHistArchivoBloqueado);
        $entityManager->flush(); 


        $opcioUsuarioArchivoBloqueado = new UsuarioArchivoBloqueadod();
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        if (!$currentUser) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
        }
        $opcioUsuarioArchivoBloqueado->setIduser($currentUser);
        $currentArchivos =$entityManager->getRepository(Archivosd::class)->find($data["id_archivo"]);
        if (!$currentArchivos) {
            return new JsonResponse(['msg'=>'No existen el Archivo id : '.$data["id_archivo"]],404);  
        }
        $opcioUsuarioArchivoBloqueado->setIdarchivo($currentArchivos);
        $opcioUsuarioArchivoBloqueado->setFechaBloqueo(date('Y-m-d h:i:s', time() - 84600 * 1));
        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                $opcioUsuarioArchivoBloqueado->setIdempresa($empresa);   
        $entityManager->persist($opcioUsuarioArchivoBloqueado);
        $entityManager->flush(); 

        }
        //*************************************************************************************

     
        //$DateAndTime = date('his', time());
        //$nemotecnico = $idarchv .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
    
           //return $valorResult["nemotecnico"].'#'.$valorResult["iduser_id"].'#'.$valorResult["id_limited_bloqueo_id"].'#'.$valorResult["url_alojamiento"];
           $opcioHistArchivoContVersion = new HistArchivoContVersiond();
           $opcioHistArchivoContVersion->setTamano($valorResult["tamano"]);
           $opcioHistArchivoContVersion->setNombreOriginal($valorResult["nombre_original"]);
           $opcioHistArchivoContVersion->setUrlAlojamiento($valorResult["url_alojamiento"]);
           $opcioHistArchivoContVersion->setFechaCreacionHist(date('Y-m-d h:i:s', time() - 84600 * 1));
           $opcioHistArchivoContVersion->setNemotecnico($valorResult["nemotecnico"]);
           $opcioHistArchivoContVersion->setComentario($data["comentarios"]);
           
    
           $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
           if (!$currentUser) {
               return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
           }
           //$opcionesuser->setIdusuario($currentUser);
           $opcioHistArchivoContVersion->setIduser($currentUser);
           $currentOrientacion =$entityManager->getRepository(TipoOrientaciond::class)->find(1);
           if (!$currentOrientacion) {
               return new JsonResponse(['msg'=>'No existen el Usuario id : '."1"],404);  
           }
           $opcioHistArchivoContVersion->setIdOrientacion($currentOrientacion);
           $currentArchivos =$entityManager->getRepository(Archivosd::class)->find($data["id_archivo"]);
           if (!$currentArchivos) {
               return new JsonResponse(['msg'=>'No existen el Usuario id : '.$opciones->getId()],404);  
           }
           $opcioHistArchivoContVersion->setIdarchivo($currentArchivos);

           $operc=0;
           if ($data["id_estado"]==4) {
              $operc=3;
           }else if ($data["id_estado"]==2) {
            $operc=9;
           }else if ($data["id_estado"]==3) {
            $operc=10;
           }else if ($data["id_estado"]==5) {
            $operc=11;
           }
           $currentOperaciones =$entityManager->getRepository(TipoOperacionesd::class)->find($operc);
           if (!$currentOperaciones) {
               return new JsonResponse(['msg'=>'No existen el Usuario id : '."1"],404);  
           }
           $opcioHistArchivoContVersion->setIdTipoOperaciones($currentOperaciones);
           $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                $opcioHistArchivoContVersion->setIdempresa($empresa);   
           $entityManager->persist($opcioHistArchivoContVersion);
           $entityManager->flush();   
          break;
       } 
     
       return new JsonResponse(['msg'=>'Registro Actualizado : '.$data["id_archivo"]],200);
    
      }else{
        return new JsonResponse(['msg'=>'El registro no se encuentra: '.$data["id_archivo"]],200);
      }
     }
     



    // /**
    //  * @return Archivosd[] Returns an array of Archivos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Archivosd
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}






