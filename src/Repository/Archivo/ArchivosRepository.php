<?php

namespace App\Repository\Archivo;

use App\Entity\Archivo\Archivos;
use App\Entity\Archivo\HistArchivoContVersion;
use App\Entity\Archivo\UsuarioArchivoBloqueado;
use App\Entity\Archivo\HistArchivoBloqueado;

Use App\Entity\User;
Use App\Entity\Archivo\TipoEstado;
Use App\Entity\Archivo\TipoLimitedBloqueo;
Use App\Entity\Archivo\TipoArchivo;
Use App\Entity\Archivo\TipoOrientacion;
Use App\Entity\Archivo\TipoOperaciones;

Use App\Entity\Archivo\UsuarioArchivos;
Use App\Entity\Archivo\ArchivosExtensiones;
use App\Dto\Archivo\ArchivosOutPutDto;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;

/**
 * @method Archivos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Archivos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Archivos[]    findAll()
 * @method Archivos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchivosRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Archivos::class);
    }


    public function findAllPage($data):JsonResponse{
        $entityManagerDefault = $this->getEntityManager();
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());

        $dataRol=[];
        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }

        $query= $this->createQueryBuilder('a');
        //$query->select("b,a")
        $query->select("b,a,t,e,l")
        ->innerJoin('a.iduserarchivos', 'b')
        ->innerJoin('a.id_tipo_archivo', 't')
       ->innerJoin('a.idestado', 'e')
        ->innerJoin('a.id_limited_bloqueo', 'l'); 
        //$query->Where("b.idusuario =".$this->security->getUser()->getId());
        $query->Where("b.idusuario =".$this->security->getUser()->getId()." and a.idempresa = ".$empresa->getId() );

        if($data['word']!=null){
            //$query->andWhere("a.titulo like '%".$data['word']."%'");
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
            

            
/*             $sss = $valor->getIdTipoArchivo()->getTipoArchivoExtesiones();
            foreach($valor->getIdTipoArchivo()->getTipoArchivoExtesiones()as $tipoArchivoExtensiones){
                $Adjuntos[]=array("id"=>$tipoArchivoExtensiones->getId(),
                "user"=>$tipoArchivoExtensiones->getTipoExtesiones());
            }
             */

            //$nombsss = $valor->getIdTipoArchivo()->getTipoArchivoExtesiones->getTipoExtesiones();


            $sql = " SELECT ua.*,u.id as iduser,u.primer_nombre,u.segundo_nombre,u.primer_apellido,u.segundo_apellido,u.email FROM `usuario_archivo_bloqueado` ua 
            inner join user u on ua.iduser_id = u.id 
            where ua.idarchivo_id=".$valor->getId().";";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            //$result= $stmt->executeQuery();
            foreach($result as $claveResult=>$valorResultH){
                $archivo_bloqueado[]=array(
                "fecha_bloqueo"=> $valorResultH["fecha_bloqueo"],
                "id"=>$valorResultH["iduser_id"],
                "nombre_apellido"=>$valorResultH["primer_nombre"].' '.$valorResultH["segundo_nombre"].' '.$valorResultH["primer_apellido"].' '.$valorResultH["segundo_apellido"],
                "email"=>$valorResultH["email"],
                );
            }


            $archivocapturaDto->idestado=  $valor->getIdestado()->getId();
            $archivocapturaDto->nombre_status=  $valor->getIdestado()->getNombreStatus();
            $archivocapturaDto->id_limited_bloqueo=  $valor->getIdLimitedBloqueo()->getId();
            $archivocapturaDto->nombre_limited_bloqueo=  $valor->getIdLimitedBloqueo()->getNombreBloqueo();
            $archivocapturaDto->hashtag=  $valor->getHashtag();
            $archivocapturaDto->bloquedo_por= $archivo_bloqueado;
            $dataarchivos[]=$archivocapturaDto;                
              
        }

        return New JsonResponse(["count"=>count($paginatorTotalCount),"data"=>$dataarchivos]);
 
    }

   
    public function getArchivosById($id){
        $entityManager = $this->getEntityManager();
        $query= $this->createQueryBuilder('a');
        //$query->select("b,a")
        $query->select("b,a,t,e,l")
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

                $sql = " SELECT a.*,b.* FROM `usuario_archivos` a 
                inner join user b on a.idusuario_id = b.id 
                where a.id=".$dime.";";
                 
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result= $stmt->fetchAll();
                //$result= $stmt->executeQuery();
                foreach($result as $claveResult=>$valorResult){
                    $recursosArchivos[]=array("id"=> $valorResult["id"],
                    "userName"=>$valorResult["username"],
                    "primerNombre"=>$valorResult["primer_nombre"],
                    "segundoNombre"=>$valorResult["segundo_nombre"],
                    "email"=>$valorResult["email"],
    
                    );
                }

            }

            $sql = " SELECT h.*,t.id as idoperacion,t.operacion,u.primer_nombre,u.segundo_nombre,u.primer_apellido,u.segundo_apellido,u.email FROM `hist_archivo_cont_version` h 
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
            }

            $sql = " SELECT ua.*,u.id as iduser,u.primer_nombre,u.segundo_nombre,u.primer_apellido,u.segundo_apellido,u.email FROM `usuario_archivo_bloqueado` ua 
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
            }

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
            $archivocapturaDto->bloquedo_por= $archivo_bloqueado;
            



            $dataarchivos[]=$archivocapturaDto;                
        }

        return New JsonResponse(["count"=>count($paginatorTotalCount),"data"=>$dataarchivos]);

    }


   /**
     * Identificar permisos de extensiones de archivos.
     */
    public function postextension($file,$validator,$helper): string  {
        //$extens = $file->guessExtension();
        $extens = $file->getClientOriginalExtension();
        $sql = " SELECT a.id_tipo_archivos_id ,a.tipo_extesiones,a.id_status_tipo_archivo_id as stat FROM `archivos_extesiones` a 
        where a.tipo_extesiones='".$extens."';";
        $conn = $this->getEntityManager()->getConnection();
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
    public function post($data,$idexts,$arrusers,$arrhashtag,$file,$validator,$helper): string  {
        $tipoarchivo = $idexts;
        //variable para el bloqueo de archivo cuando empiece a trabajar esta parte hay que tomarla desde $data
        $id_limited_bloqueo=3;
  
        $entityManager = $this->getEntityManager();

        $opciones = new Archivos();
        $opciones->setTitulo($data['titulo']);
        $opciones->setNombreOriginal($data['nombre_original']);
        $opciones->setDescripcionArchivo($data['descripcion_archivo']);
        $opciones->setTamano($data['tamano']);
        $opciones->setPublico($data['publico']);
        $entity12 =$entityManager->getRepository(TipoEstado::class)->find($data['idestado']);
        if (!$entity12) {
            return new JsonResponse(['msg'=>'No existen Tipo de Estado con el id: '.$data['idestado']],404);  
        }
        $opciones->setIdestado($entity12);
        
        //$entity13 =$entityManager->getRepository(TipoLimitedBloqueo::class)->find($data['id_limited_bloqueo']);
        $entity13 =$entityManager->getRepository(TipoLimitedBloqueo::class)->find($id_limited_bloqueo);
        if (!$entity13) {
            return new JsonResponse(['msg'=>'No existen el Proyecto con el idproyecto: '.$id_limited_bloqueo],404);  
        }
        $opciones->setIdLimitedBloqueo($entity13);
        $entity14 =$entityManager->getRepository(TipoArchivo::class)->find($tipoarchivo);
        if (!$entity14) {
            return new JsonResponse(['msg'=>'No existen el Proyecto con el idproyecto: '.$tipoarchivo],404);  
        }
        $opciones->setIdTipoArchivo($entity14);

        $opciones->setUrlAlojamiento('actualizar');
        $opciones->setNemotecnico('actualizar');
        $opciones->setFechaActividadRegistro(date('Y-m-d h:i:s', time() - 84600 * 1));
        $opciones->setHashtag($arrhashtag);
        
        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
               $opciones->setIdempresa($empresa);   

        $entityManager->persist($opciones);
        $entityManager->flush();
         

        $iduserpmo =  $this->security->getUser()->getId();
        $opcionesuser = new UsuarioArchivos();
        $entityarchivo =$entityManager->getRepository(Archivos::class)->find($opciones->getId());
        if (!$entityarchivo) {
            return new JsonResponse(['msg'=>'No existen el Archivo id : '.$opciones->getId()],404);  
        }
        $opcionesuser->setIduserarchivos($entityarchivo);
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        if (!$currentUser) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
        }
        $opcionesuser->setIdusuario($currentUser);
        $entityManager->persist($opcionesuser);
        $entityManager->flush();

        $cont=0; 
        $usercorr='';
        $arrnotifica='';
        if ($arrusers) {
        foreach($arrusers as $optionsuser){
            if ($iduserpmo!=$optionsuser) {
                $opcionesuser = new UsuarioArchivos();
                $entityarchivo =$entityManager->getRepository(Archivos::class)->find($opciones->getId());
                if (!$entityarchivo) {
                    return new JsonResponse(['msg'=>'No existen el Archivo id : '.$opciones->getId()],404);  
                }
                $opcionesuser->setIduserarchivos($entityarchivo);

                //$currentUser =$entityManager->getRepository(User::class)->find($optionsuser["userId"]);
                $currentUser =$entityManager->getRepository(User::class)->find($optionsuser);
                if (!$currentUser) {
                    return new JsonResponse(['msg'=>'No existen el Usuario id : '.$optionsuser],404);  
                }
                $opcionesuser->setIdusuario($currentUser);

                if ($cont==0) {
                    $usercorr =  $currentUser->getEmail();
                    $cont++;
                }else{
                    $usercorr = $usercorr . '|' . $currentUser->getEmail();;
                }
                
                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                   $opcionesuser->setIdempresa($empresa);   

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
        
        $urlarchv=('/archivos/giep/documentos/'.$nemotecnico.'.'.$file->getClientOriginalExtension());
        /* $sql = "update archivos set nemotecnico='".$nemotecnico."', url_alojamiento='".$urlarchv."'  where id=1 "; */
        $sql = "update archivos set nemotecnico='".$nemotecnico."', url_alojamiento='".$urlarchv."'  where id=".$opciones->getId()." ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();


        $quqe = intval($data['tamano']);

        /* tamano, url_alojamiento, ancho, altura, duracion, tags, fecha_creacion_hist, nemotecnico, comentario, iduser_id,
         id_orientacion_id, idarchivo_id, id_tipo_operaciones_id */

        $opcioHistArchivoContVersion = new HistArchivoContVersion();
        $opcioHistArchivoContVersion->setTamano($data['tamano']);

        $opcioHistArchivoContVersion->setNombreOriginal($data['nombre_original']);

        $opcioHistArchivoContVersion->setUrlAlojamiento($urlarchv);

        $opcioHistArchivoContVersion->setFechaCreacionHist(date('Y-m-d h:i:s', time() - 84600 * 1));
        
        $opcioHistArchivoContVersion->setNemotecnico($nemotecnico);

        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        if (!$currentUser) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
        }
        //$opcionesuser->setIdusuario($currentUser);
        $opcioHistArchivoContVersion->setIduser($currentUser);


        $currentOrientacion =$entityManager->getRepository(TipoOrientacion::class)->find(1);
        if (!$currentOrientacion) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '."1"],404);  
        }
        $opcioHistArchivoContVersion->setIdOrientacion($currentOrientacion);

        $currentArchivos =$entityManager->getRepository(Archivos::class)->find($opciones->getId());
        if (!$currentArchivos) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '.$opciones->getId()],404);  
        }
        $opcioHistArchivoContVersion->setIdarchivo($currentArchivos);

        $currentOperaciones =$entityManager->getRepository(TipoOperaciones::class)->find(1);
        if (!$currentOperaciones) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '."1"],404);  
        }
        $opcioHistArchivoContVersion->setIdTipoOperaciones($currentOperaciones);

        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                 $opcioHistArchivoContVersion->setIdempresa($empresa);   


        $entityManager->persist($opcioHistArchivoContVersion);
        $entityManager->flush(); 

         return $nemotecnico.'#'.$usercorr;
    }
    

    //Buscar el id del archivo y desacargar
    public function getBuscarArchivosById($id,$dirserv,$user){
        try {
        $entityManager = $this->getEntityManager();
        $query= $this->createQueryBuilder('a');
        //$query->select("b,a")
        $query->select("b,a,t,e,l")
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

            $opcioHistArchivoContVersion = new HistArchivoContVersion();
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
            $opcioHistArchivoContVersion->setIduser($currentUser);
    
    
            $currentOrientacion =$entityManager->getRepository(TipoOrientacion::class)->find(1);
            if (!$currentOrientacion) {
                return new JsonResponse(['msg'=>'No existen el Tipo Orientacion id : '."1"],404);  
            }
            $opcioHistArchivoContVersion->setIdOrientacion($currentOrientacion);
    
            $currentArchivos =$entityManager->getRepository(Archivos::class)->find($valor->getId());
            if (!$currentArchivos) {
                return new JsonResponse(['msg'=>'No existen el Archivo id : '.$valor->getId()],404);  
            }
            $opcioHistArchivoContVersion->setIdarchivo($currentArchivos);
    
            $currentOperaciones =$entityManager->getRepository(TipoOperaciones::class)->find(6);
            if (!$currentOperaciones) {
                return new JsonResponse(['msg'=>'No existen el Tipo Operación id : '."6"],404);  
            }
            $opcioHistArchivoContVersion->setIdTipoOperaciones($currentOperaciones);

            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                 $opcioHistArchivoContVersion->setIdempresa($empresa);   
    
            $entityManager->persist($opcioHistArchivoContVersion);
            $entityManager->flush(); 
            
            
            
            $opcioHistArchivoBloqueado = new HistArchivoBloqueado();
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
            $entityManager->flush(); 


            $opcioUsuarioArchivoBloqueado = new UsuarioArchivoBloqueado();
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
            $stmt->execute();

            
            
            

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
        $entity =$entityManager->getRepository(Archivos::class)->find($id);
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
  
    $sql = " SELECT a.* FROM `archivos` a 
    where a.id=".$idarchv."  and id_limited_bloqueo_id=1 order by id desc ";
    $conn = $this->getEntityManager()->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resultest= $stmt->fetchAll();
    if(true === is_array($resultest)){

   $sql = " SELECT a.* FROM `hist_archivo_cont_version` a 
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
    $sql = "update hist_archivo_bloqueado set fecha_desbloqueo='".date('Y-m-d h:i:s', time() - 84600 * 1)."'  where idarchivo_id=".$idarchv." and iduser_id=".$valorResult["iduser_id"]." ";
    $conn = $this->getEntityManager()->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
 
    $DateAndTime = date('his', time());
    $nemotecnico = $idarchv .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
    $sql = "update archivos set id_limited_bloqueo_id=3 where id=".$idarchv." ";
    $conn = $this->getEntityManager()->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
 


       //return $valorResult["nemotecnico"].'#'.$valorResult["iduser_id"].'#'.$valorResult["id_limited_bloqueo_id"].'#'.$valorResult["url_alojamiento"];
       $opcioHistArchivoContVersion = new HistArchivoContVersion();
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
       $currentOrientacion =$entityManager->getRepository(TipoOrientacion::class)->find(1);
       if (!$currentOrientacion) {
           return new JsonResponse(['msg'=>'No existen el Usuario id : '."1"],404);  
       }
       $opcioHistArchivoContVersion->setIdOrientacion($currentOrientacion);
       $currentArchivos =$entityManager->getRepository(Archivos::class)->find($idarchv);
       if (!$currentArchivos) {
           return new JsonResponse(['msg'=>'No existen el Usuario id : '.$opciones->getId()],404);  
       }
       $opcioHistArchivoContVersion->setIdarchivo($currentArchivos);
       $currentOperaciones =$entityManager->getRepository(TipoOperaciones::class)->find(8);
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

   $sql = "DELETE FROM usuario_archivo_bloqueado where idarchivo_id=".$idarchv." ";
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
        inner join usuario_archivos u on us.id = u.idusuario_id
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

        $sql = " SELECT a.*,u.iduser_id FROM `archivos` a 
            inner join usuario_archivo_bloqueado u on a.id = u.idarchivo_id
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

        $sql1 = " SELECT a.*,H.* FROM `archivos` a 
            inner join hist_archivo_cont_version H on a.id = H.idarchivo_id
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
    $sql = "update hist_archivo_bloqueado set fecha_desbloqueo='".date('Y-m-d h:i:s', time() - 84600 * 1)."'  where idarchivo_id=".$idarchv." and iduser_id=".$iduserbloq." ";
    $conn = $this->getEntityManager()->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $DateAndTime = date('his', time());
    $nemotecnico = $idarchv .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
    $sql = "update archivos set id_limited_bloqueo_id=3,nemotecnico='".$nemotecnico."', url_alojamiento='".'/archivos/giep/documentos/'.$nemotecnico.'.'.$extens."'  where id=".$idarchv." ";
    $conn = $this->getEntityManager()->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $sql = " SELECT a.* FROM `hist_archivo_cont_version` a 
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
        $opcioHistArchivoContVersion = new HistArchivoContVersion();
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
        $currentOrientacion =$entityManager->getRepository(TipoOrientacion::class)->find(1);
        if (!$currentOrientacion) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '."1"],404);  
        }
        $opcioHistArchivoContVersion->setIdOrientacion($currentOrientacion);
        $currentArchivos =$entityManager->getRepository(Archivos::class)->find($idarchv);
        if (!$currentArchivos) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '.$opciones->getId()],404);  
        }
        $opcioHistArchivoContVersion->setIdarchivo($currentArchivos);
        $currentOperaciones =$entityManager->getRepository(TipoOperaciones::class)->find(1);
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
    $sql = "DELETE FROM usuario_archivo_bloqueado where idarchivo_id=".$idarchv." ";
    $conn = $this->getEntityManager()->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $nemotecnico;
}


    /**
     * Update Cambios Status Archivos.
     */
    public function putcambiostatusarchivo($data,$validator,$helper){
  
        $sql = " SELECT a.* FROM `archivos` a 
        where a.id=".$data["id_archivo"]." order by id desc ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resultest= $stmt->fetchAll();
        if(true === is_array($resultest)){

       $sql = " SELECT a.* FROM `hist_archivo_cont_version` a 
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
        $sql = "update archivos set idestado_id=".$data["id_estado"]." where id=".$data["id_archivo"]." ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();

                //bloquear archivo ********************************************************************
        
        if($data["id_estado"] === 3 or $data["id_estado"] === 4 or $data["id_estado"] === 5 or $data["id_estado"] === 2 ){

        $sql = "update archivos set id_limited_bloqueo_id=1 where id=".$data["id_archivo"]." ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();


        $opcioHistArchivoBloqueado = new HistArchivoBloqueado();
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        if (!$currentUser) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
        }
        $opcioHistArchivoBloqueado->setIduser($currentUser);
        $currentArchivos =$entityManager->getRepository(Archivos::class)->find($data["id_archivo"]);
        if (!$currentArchivos) {
            return new JsonResponse(['msg'=>'No existen el Archivo id : '.$data["id_archivo"]],404);  
        }
        $opcioHistArchivoBloqueado->setIdarchivo($currentArchivos);
        $opcioHistArchivoBloqueado->setFechaBloqueo(date('Y-m-d h:i:s', time() - 84600 * 1));
        $entityManager->persist($opcioHistArchivoBloqueado);
        $entityManager->flush(); 


        $opcioUsuarioArchivoBloqueado = new UsuarioArchivoBloqueado();
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        if (!$currentUser) {
            return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
        }
        $opcioUsuarioArchivoBloqueado->setIduser($currentUser);
        $currentArchivos =$entityManager->getRepository(Archivos::class)->find($data["id_archivo"]);
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
           $opcioHistArchivoContVersion = new HistArchivoContVersion();
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
           $currentOrientacion =$entityManager->getRepository(TipoOrientacion::class)->find(1);
           if (!$currentOrientacion) {
               return new JsonResponse(['msg'=>'No existen el Usuario id : '."1"],404);  
           }
           $opcioHistArchivoContVersion->setIdOrientacion($currentOrientacion);
           $currentArchivos =$entityManager->getRepository(Archivos::class)->find($data["id_archivo"]);
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
           $currentOperaciones =$entityManager->getRepository(TipoOperaciones::class)->find($operc);
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
    //  * @return Archivos[] Returns an array of Archivos objects
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
    public function findOneBySomeField($value): ?Archivos
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






