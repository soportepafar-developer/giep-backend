<?php

namespace App\Repository\DocumentoDigital;

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
use App\Dto\DocumentoDigital\ArchivosOutPutDto;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Asset\Packages;
use App\Entity\Proyecto\Empresa;
Use App\Entity\User;

/**
 * @method ControlArchivoDigital|null find($id, $lockMode = null, $lockVersion = null)
 * @method ControlArchivoDigital|null findOneBy(array $criteria, array $orderBy = null)
 * @method ControlArchivoDigital[]    findAll()
 * @method ControlArchivoDigital[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ControlArchivoDigitalRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, ControlArchivoDigital::class);
    }

    /**
     * Create Control Archivo Digital.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {

        //var_dump($data);die;
            $entityManager = $this->getEntityManager();
            $entity = new ControlArchivoDigital();

            $entityPais =$entityManager->getRepository(Pais::class)->find($data['id_pais']);
            if (!$entityPais) {
                return new JsonResponse(['msg'=>'No existen Registros de PAIS con el id: '.$data['id_pais']],404);  
            }
            $entity->setIdPais($entityPais->getId());

            $entityEstado =$entityManager->getRepository(Estado::class)->find($data['id_estado']);
            if (!$entityEstado) {
                return new JsonResponse(['msg'=>'No existen Registros de PAIS con el id: '.$data['id_pais']],404);  
            }
            $entity->setIdEstado($entityEstado->getId());

            $entityCiudad =$entityManager->getRepository(Ciudad::class)->find($data['id_ciudad']);
            if (!$entityCiudad) {
                return new JsonResponse(['msg'=>'No existen Registros de PAIS con el id: '.$data['id_pais']],404);  
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
            $entity->setNumExpediente($data['num_expediente']);
            $entity->setFechaDocumento(!is_null($data['fecha_documento'])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data['fecha_documento'] )))):null);

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

            if(isset($data['idubica1'])){
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
          }

          if(isset($data['idubica2'])){
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

         if(isset($data['idubica3'])){
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


            $query = $em->createQueryBuilder();
            $allAppointmentsQuery = $query->select('Region')
            ->from(Region::class,'Region')
            ->Where('Region.id='.$data['idregion'])
            ->addOrderBy('Region.id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $dataRegion =  $queryult->execute();
            if($dataRegion!=null){
                $entity->setIdregion($dataRegion[0]);
            }else{
            return new JsonResponse(['msg'=>'No existen el id Region : '.$data['idregion']],404);
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

            $query = $em->createQueryBuilder();
            $allAppointmentsQuery = $query->select('TipoStatusArchivo')
            ->from(TipoStatusArchivod::class,'TipoStatusArchivo')
            ->Where('TipoStatusArchivo.id='.$data['id_status_tipoestado'])
            ->addOrderBy('TipoStatusArchivo.id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $dataTipoStatusArchivo =  $queryult->execute();
            if($dataTipoStatusArchivo!=null){
                $entity->setIdStatusTipoestado($dataTipoStatusArchivo[0]);
            }else{
            return new JsonResponse(['msg'=>'No existen Registros de Tipo Status con el id : '.$data['id_status_tipoestado']],404);
            }
           
            $entity->setCantidadCaja($data['cantidad_caja']);
            $entity->setCantidadEstuche($data['cantidad_estuche']);
            $currentUserentrega =$entityManager->getRepository(User::class)->find($data['id_user_entrega']);
            $entity->setIdUserEntrega($currentUserentrega->getId());

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

            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setCreateAt(new \DateTime('now'));

            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa->getId());
                $em->persist($entity);
                $em->flush();

            //$data=array("tipoEntidad"=>"Proyecto","idEntidad"=>$entity->getId(),"createdBy"=>$currentUser->getId(),"sqlInstruction"=>"update","accion"=>1);
            //$this->traza->post($data,$validator,$helper);

            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
         
    }

    
    public function getArchivosById($id,$em){

        $entityManagerDefault = $this->getEntityManager();
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        $dataRol=[];
        /* if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        } */
        $query = $em->createQueryBuilder('a');
        $query->select("b,a,t,e,l")
        ->from('App\Entity\DocumentoDigital\Archivosd', 'a')
        ->innerJoin('a.iduserarchivos', 'b')
        ->innerJoin('a.id_tipo_archivo', 't')
        ->innerJoin('a.idestado', 'e')
        ->innerJoin('a.id_limited_bloqueo', 'l'); 
        $query->Where("a.id =".$id." " );
        /* if($data['word']!=null){
            $query->andWhere("a.titulo like '%".$data['word']."%' or a.nombre_original like '%".$data['word']."%' or a.hashtag like '%".$data['word']."%' ");
         } */
        $query->orderBy('a.id', 'ASC');   
        $query->getQuery();
        $queryult = $query->getQuery();
        $dataArchivo =  $queryult->execute();
        /* $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery()	
      	->setFirstResult($data['rowByPage'] *($data['page']-1))	
        ->setMaxResults($data['rowByPage']); */
        $dataarchivos=array();
         $archivo_bloqueado=[];
        foreach($dataArchivo as $clave=>$valor){
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
            //$archivocapturaDto->num_dela_estuches=  $valor->getNumDelaEstuches();

            $archivocapturaDto->fecha_extrema_inicio=  $valor->getFechaExtremaInicio();
            $archivocapturaDto->fecha_extrema_fin=  $valor->getFechaExtremaFin();

            //otro endpoint ********************************************************************************
            //$query = $em->createQueryBuilder();
            $query = $entityManagerDefault->createQueryBuilder();
            $allAppointmentsQuery = $query->select('estructuraorganizativa')
            ->from(EstructuraOrganizativa::class,'estructuraorganizativa')
            ->where("estructuraorganizativa.id ='".$valor->getIdControlArchivoDigital()->getIdEstructuraOrganizativa()->getPadreId()."'")
            ->addOrderBy('estructuraorganizativa.id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $data =  $queryult->execute();
            $cont=0;
            $id_padre=0;
            $strpadre='';
            foreach($data as $clave=>$valorpadre){
              $id_padre  =$valorpadre->getId();
              $strpadre =$valorpadre->getEstructuraOrganizativa();
            //$profesionDto->iddireccionalmacen=($valor->getIddireccionalmacen()!=null)?array("id"=>$valor->getIddireccionalmacen()->getId(),"Nombre"=>$valor->getIddireccionalmacen()->getNombre(),"Direccion"=>$valor->getIddireccionalmacen()->getDireccionzona()):[]; 
            } 

            $strpais='';
            $query = $entityManagerDefault->createQueryBuilder();
            $allAppointmentsQuery = $query->select('pais')
            ->from(Pais::class,'pais')
            ->where("pais.id ='".$valor->getIdControlArchivoDigital()->getIdPais()."'")
            ->addOrderBy('pais.id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $data =  $queryult->execute();
            $cont=0;
            foreach($data as $clave=>$valorpadre){
              $strpais =$valorpadre->getNombre();
            } 

            $strestadopais='';
            $query = $entityManagerDefault->createQueryBuilder();
            $allAppointmentsQuery = $query->select('estado')
            ->from(Estado::class,'estado')
            ->where("estado.id ='".$valor->getIdControlArchivoDigital()->getIdEstado()."'")
            ->addOrderBy('estado.id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $data =  $queryult->execute();
            foreach($data as $clave=>$valorpadre){
              $strestadopais =$valorpadre->getNombre();
            } 

            $strciudad='';
            $query = $entityManagerDefault->createQueryBuilder();
            $allAppointmentsQuery = $query->select('ciudad')
            ->from(Ciudad::class,'ciudad')
            ->where("ciudad.id ='".$valor->getIdControlArchivoDigital()->getIdCiudad()."'")
            ->addOrderBy('ciudad.id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $data =  $queryult->execute();
            foreach($data as $clave=>$valorpadre){
              $strciudad =$valorpadre->getNombre();
            } 

            $strregion='';
            $query = $entityManagerDefault->createQueryBuilder();
            $allAppointmentsQuery = $query->select('region')
            ->from(Region::class,'region')
            ->where("region.id ='".$valor->getIdControlArchivoDigital()->getIdregion()."'")
            ->addOrderBy('region.id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $data =  $queryult->execute();
            foreach($data as $clave=>$valorpadre){
                $strregion =$valorpadre->getDescregion();
            } 

            $currentUser =$entityManagerDefault->getRepository(User::class)->find($valor->getIdControlArchivoDigital()->getIdUserEntrega());
            $strnombre=$currentUser->getPrimerNombre().' '.$currentUser->getSegundoNombre();
            $strapellido=$currentUser->getPrimerApellido() .' '. $currentUser->getSegundoApellido();


            $val = $this->Estructura_Organizativa($valor->getIdControlArchivoDigital()->getIdEstructuraOrganizativa()->getId());
    
            $archivocapturaDto->id_control_archivo_digital=($valor->getIdControlArchivoDigital()!=null)?array("id"=>$valor->getIdControlArchivoDigital()->getId(),"Asuntos"=>$valor->getIdControlArchivoDigital()->getAsuntos()
            ,"num_expediente"=>$valor->getIdControlArchivoDigital()->getNumExpediente(),"argumento_justificacion"=>$valor->getIdControlArchivoDigital()->getArgumentoJustificacion()
            ,"id_Pais"=>$valor->getIdControlArchivoDigital()->getIdPais(),"Pais"=>$strpais
            ,"id_Estado"=>$valor->getIdControlArchivoDigital()->getIdEstado(),"Estado"=>$strestadopais,
            "id_ciudad"=>$valor->getIdControlArchivoDigital()->getIdCiudad(),"Ciudad"=>$strciudad
            ,"fecha_fin_conservac"=>$valor->getIdControlArchivoDigital()->getFechaFinConservac(),"sw_archivo_fisico"=>$valor->getIdControlArchivoDigital()->getSwArchivoFisico(),"fecha_documento"=>$valor->getIdControlArchivoDigital()->getFechaDocumento()

            ,"niveles"=>$val

            ,"id_region"=>$valor->getIdControlArchivoDigital()->getIdregion(),"region"=>$strregion
            ,"id_serie"=>$valor->getIdControlArchivoDigital()->getCodigoSerieSubserie()->getIdSerie()->getId(),"serie"=>$valor->getIdControlArchivoDigital()->getCodigoSerieSubserie()->getIdSerie()->getNombre()
            ,"id_subserie"=>$valor->getIdControlArchivoDigital()->getCodigoSerieSubserie()->getId(),"sub_serie"=>$valor->getIdControlArchivoDigital()->getCodigoSerieSubserie()->getNombreSubserie()
            ,"id_statustipoestado"=>$valor->getIdControlArchivoDigital()->getIdStatusTipoestado()->getId(),"nombre_status"=>$valor->getIdControlArchivoDigital()->getIdStatusTipoestado()->getNombreStatus()
            ,"cantidad_caja"=>$valor->getIdControlArchivoDigital()->getCantidadCaja(),
            "id_usuario_entrega"=>$valor->getIdControlArchivoDigital()->getIdUserEntrega(),"Nombres"=>$strnombre,"Apellidos"=>$strapellido
            //,"id_estuche"=>$valor->getIdControlArchivoDigital()->getIdcontenidoCaja()->getId(),"nombre_estuche"=>$valor->getIdControlArchivoDigital()->getIdcontenidoCaja()->getNombreEstuche()
            ,"id_padre_estructuraorganizativa"=>$id_padre,"padre_estructura_organizativa"=>$strpadre
            ,"id_estructuraorganizativa"=>$valor->getIdControlArchivoDigital()->getIdEstructuraOrganizativa()->getId(),"estructura_organizativa"=>$valor->getIdControlArchivoDigital()->getIdEstructuraOrganizativa()->getEstructuraOrganizativa()
            ):[]; 



            if($valor->getIdControlArchivoDigital()->getIdTipoAlmacen()!=null){
                $archivocapturaDto->id_control_archivo_digital=($valor->getIdControlArchivoDigital()!=null)?array("id"=>$valor->getIdControlArchivoDigital()->getId(),"Asuntos"=>$valor->getIdControlArchivoDigital()->getAsuntos()
                ,"num_expediente"=>$valor->getIdControlArchivoDigital()->getNumExpediente(),"argumento_justificacion"=>$valor->getIdControlArchivoDigital()->getArgumentoJustificacion()
                ,"id_Pais"=>$valor->getIdControlArchivoDigital()->getIdPais(),"Pais"=>$strpais
                ,"id_Estado"=>$valor->getIdControlArchivoDigital()->getIdEstado(),"Estado"=>$strestadopais,
                "id_ciudad"=>$valor->getIdControlArchivoDigital()->getIdCiudad(),"Ciudad"=>$strciudad
                ,"fecha_fin_conservac"=>$valor->getIdControlArchivoDigital()->getFechaFinConservac(),"sw_archivo_fisico"=>$valor->getIdControlArchivoDigital()->getSwArchivoFisico(),"fecha_documento"=>$valor->getIdControlArchivoDigital()->getFechaDocumento()
                ,"niveles"=>$val
                ,"id_tipo_almacen"=>$valor->getIdControlArchivoDigital()->getIdTipoAlmacen()->getId(),"nombre_almacen"=>$valor->getIdControlArchivoDigital()->getIdTipoAlmacen()->getNombrealmacen()
                ,"id_ubicacion_1"=>$valor->getIdControlArchivoDigital()->getIdubica1()->getId(),"ubicacion_1"=>$valor->getIdControlArchivoDigital()->getIdubica1()->getDescripcion()
                ,"id_ubicacion_2"=>$valor->getIdControlArchivoDigital()->getIdubica2()->getId(),"ubicacion_2"=>$valor->getIdControlArchivoDigital()->getIdubica2()->getDescripcion()
                ,"id_ubicacion_3"=>$valor->getIdControlArchivoDigital()->getIdubica3()->getId(),"ubicacion_3"=>$valor->getIdControlArchivoDigital()->getIdubica3()->getDescripcion()
                ,"id_region"=>$valor->getIdControlArchivoDigital()->getIdregion(),"region"=>$strregion
                ,"id_serie"=>$valor->getIdControlArchivoDigital()->getCodigoSerieSubserie()->getIdSerie()->getId(),"serie"=>$valor->getIdControlArchivoDigital()->getCodigoSerieSubserie()->getIdSerie()->getNombre()
                ,"id_subserie"=>$valor->getIdControlArchivoDigital()->getCodigoSerieSubserie()->getId(),"sub_serie"=>$valor->getIdControlArchivoDigital()->getCodigoSerieSubserie()->getNombreSubserie()
                ,"id_statustipoestado"=>$valor->getIdControlArchivoDigital()->getIdStatusTipoestado()->getId(),"nombre_status"=>$valor->getIdControlArchivoDigital()->getIdStatusTipoestado()->getNombreStatus()
                ,"cantidad_caja"=>$valor->getIdControlArchivoDigital()->getCantidadCaja(),
                "id_usuario_entrega"=>$valor->getIdControlArchivoDigital()->getIdUserEntrega(),"Nombres"=>$strnombre,"Apellidos"=>$strapellido
                //,"id_estuche"=>$valor->getIdControlArchivoDigital()->getIdcontenidoCaja()->getId(),"nombre_estuche"=>$valor->getIdControlArchivoDigital()->getIdcontenidoCaja()->getNombreEstuche()
                ,"id_padre_estructuraorganizativa"=>$id_padre,"padre_estructura_organizativa"=>$strpadre
                ,"id_estructuraorganizativa"=>$valor->getIdControlArchivoDigital()->getIdEstructuraOrganizativa()->getId(),"estructura_organizativa"=>$valor->getIdControlArchivoDigital()->getIdEstructuraOrganizativa()->getEstructuraOrganizativa()
                ):[]; 

            }else{

            $archivocapturaDto->id_control_archivo_digital=($valor->getIdControlArchivoDigital()!=null)?array("id"=>$valor->getIdControlArchivoDigital()->getId(),"Asuntos"=>$valor->getIdControlArchivoDigital()->getAsuntos()
            ,"num_expediente"=>$valor->getIdControlArchivoDigital()->getNumExpediente(),"argumento_justificacion"=>$valor->getIdControlArchivoDigital()->getArgumentoJustificacion()
            ,"id_Pais"=>$valor->getIdControlArchivoDigital()->getIdPais(),"Pais"=>$strpais
            ,"id_Estado"=>$valor->getIdControlArchivoDigital()->getIdEstado(),"Estado"=>$strestadopais,
            "id_ciudad"=>$valor->getIdControlArchivoDigital()->getIdCiudad(),"Ciudad"=>$strciudad
            ,"fecha_fin_conservac"=>$valor->getIdControlArchivoDigital()->getFechaFinConservac(),"sw_archivo_fisico"=>$valor->getIdControlArchivoDigital()->getSwArchivoFisico(),"fecha_documento"=>$valor->getIdControlArchivoDigital()->getFechaDocumento()

            ,"niveles"=>$val
            ,"id_tipo_almacen"=>null,"nombre_almacen"=>null
            ,"id_ubicacion_1"=>null,"ubicacion_1"=>null
            ,"id_ubicacion_2"=>null,"ubicacion_2"=>null
            ,"id_ubicacion_3"=>null,"ubicacion_3"=>null

            
            ,"id_region"=>$valor->getIdControlArchivoDigital()->getIdregion(),"region"=>$strregion
            ,"id_serie"=>$valor->getIdControlArchivoDigital()->getCodigoSerieSubserie()->getIdSerie()->getId(),"serie"=>$valor->getIdControlArchivoDigital()->getCodigoSerieSubserie()->getIdSerie()->getNombre()
            ,"id_subserie"=>$valor->getIdControlArchivoDigital()->getCodigoSerieSubserie()->getId(),"sub_serie"=>$valor->getIdControlArchivoDigital()->getCodigoSerieSubserie()->getNombreSubserie()
            ,"id_statustipoestado"=>$valor->getIdControlArchivoDigital()->getIdStatusTipoestado()->getId(),"nombre_status"=>$valor->getIdControlArchivoDigital()->getIdStatusTipoestado()->getNombreStatus()
            ,"cantidad_caja"=>$valor->getIdControlArchivoDigital()->getCantidadCaja(),
            "id_usuario_entrega"=>$valor->getIdControlArchivoDigital()->getIdUserEntrega(),"Nombres"=>$strnombre,"Apellidos"=>$strapellido
            //,"id_estuche"=>$valor->getIdControlArchivoDigital()->getIdcontenidoCaja()->getId(),"nombre_estuche"=>$valor->getIdControlArchivoDigital()->getIdcontenidoCaja()->getNombreEstuche()
            ,"id_padre_estructuraorganizativa"=>$id_padre,"padre_estructura_organizativa"=>$strpadre
            ,"id_estructuraorganizativa"=>$valor->getIdControlArchivoDigital()->getIdEstructuraOrganizativa()->getId(),"estructura_organizativa"=>$valor->getIdControlArchivoDigital()->getIdEstructuraOrganizativa()->getEstructuraOrganizativa()
            ):[]; 
            
            }

            $archivocapturaDto->idempresa_id=  $valor->getIdempresa();

            $dataarchivos[]=$archivocapturaDto;                
        }

        return New JsonResponse(["data"=>$dataarchivos]);


    }


    public function Estructura_Organizativa($idestructura){
    $dataEstructura=[];    
    $regSalida=false;
    $entityManager = $this->getEntityManager();
    $queryresp = $entityManager->createQueryBuilder();
    $estructuraQuery = $queryresp->select('eo.id, eo.padre_id, eo.estructura_organizativa')
        ->from(EstructuraOrganizativa::class, 'eo')
        ->where('eo.id = :id')
        ->setParameter('id', $idestructura)
        ->getQuery();
        $queryrespdata = $queryresp->getQuery();
        $dataresp =  $queryrespdata->execute();
        $regUnidad =  $dataresp[0]["padre_id"];

        $dataEstructura[] = array(
            "id" => $dataresp[0]["id"],
            "label" => $dataresp[0]["estructura_organizativa"]
        );

   if (!is_null($dataresp[0]["padre_id"])) {

    do {
        $queryRecursivo = $entityManager->createQueryBuilder();
        $estructuraQueryRec = $queryRecursivo->select('eo.id, eo.padre_id, eo.estructura_organizativa')
        ->from(EstructuraOrganizativa::class, 'eo')
        ->where('eo.id = :id')
        ->setParameter('id', $regUnidad)
        ->getQuery();
        $queryrecursivodata = $queryRecursivo->getQuery();
        $datarecursivo =  $queryrecursivodata->execute();

        if (is_null($datarecursivo[0]["padre_id"])) {
            $dataEstructura[] = array(
                "id" => $datarecursivo[0]["id"],
                "label" => $datarecursivo[0]["estructura_organizativa"]
            );
            $regSalida=true;
        }else{
            $regUnidad =  $datarecursivo[0]["padre_id"];
            $dataEstructura[] = array(
                "id" => $datarecursivo[0]["id"],
                "label" => $datarecursivo[0]["estructura_organizativa"]
            );
        }
        

   } while (!$regSalida);
}
   // Ordenar el array de menor a mayor por el campo 'id'
    usort($dataEstructura, function($a, $b) {
        return $a['id'] <=> $b['id'];
    });
   return $dataEstructura;

}


    // /**
    //  * @return ControlArchivoDigital[] Returns an array of ControlArchivoDigital objects
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
    public function findOneBySomeField($value): ?ControlArchivoDigital
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
