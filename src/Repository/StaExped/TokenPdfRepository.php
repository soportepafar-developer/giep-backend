<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\TokenPdf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Entity\StaExped\DatosPersonales;
use App\Entity\StaExped\TiposRespuestas;
use App\Entity\StaExped\EstudiosAcademicos;
use App\Entity\StaExped\Profesion;
use App\Entity\StaExped\EspecialidadesAreas;
use App\Entity\StaExped\Area;
use App\Entity\StaExped\DocumentosIngresos;
use App\Entity\StaExped\Departamento;
use App\Entity\StaExped\Region;
use App\Entity\Cargo;
use App\Entity\StaExped\ControlesVarios;
use App\Entity\StaExped\HistMovTransferencias;
use App\Entity\StaExped\HistMovPromocion;
use App\Entity\StaExped\Vaciones;
use App\Entity\StaExped\TipoVacaciones;
use App\Entity\StaExped\VacacionesObservacion;
use App\Entity\StaExped\Permisos;
use App\Entity\StaExped\TipoMotivoPermiso;
use App\Entity\StaExped\Reposo;
use App\Entity\StaExped\TipoMotivoReposo;
use App\Entity\StaExped\TipoReposo;
use App\Entity\StaExped\FideiComiso;
use App\Entity\StaExped\SeguridadSaludLaboral;
use App\Entity\StaExped\Otros;
use App\Entity\StaExped\CategoriasOtros;
use App\Entity\StaExped\TipoMotivoExpediente;
use App\Dto\StaExped\AreaOutPutDto;
use App\Dto\StaExped\EstudiosAcademicosOutPutDto;
use App\Dto\StaExped\DatosPersonalesOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
//use App\Service\Fpdfreporte;
use App\Entity\Proyecto\Empresa;

/**
 * @method TokenPdf|null find($id, $lockMode = null, $lockVersion = null)
 * @method TokenPdf|null findOneBy(array $criteria, array $orderBy = null)
 * @method TokenPdf[]    findAll()
 * @method TokenPdf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenPdfRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TokenPdf::class);
    }

     /**
     * Create Reportes .
     */
    public function post($tipreporte,$validator,$helper,$em,$Fpdfreporte,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo){
      $entityManagerDefault = $this->getEntityManager();
      //$data = json_decode($arrusers,true);
      $dataDatosPersonales=[];
       $query = $em->createQueryBuilder();
       $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
     if($empresa)
        $idemp = $empresa->getId(); 
        $allAppointmentsQuery = $query->select('datos_personales.id,datos_personales.cedula,datos_personales.fecha_ingreso
        ,datos_personales.autorizacion_ingreso,datos_personales.familiar_empresa,datos_personales.createAt,datos_personales.idempresa')
        ->from(DatosPersonales::class,'datos_personales')
        ->Where('datos_personales.idempresa='.$idemp)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        
        foreach($data as $clave=>$valor){
            $cont++;
          
          $datospersonalesDto=$valor["id"];
          $datosnumeroDocumento=$valor["cedula"];

          $entity =$entityManagerDefault->getRepository(User::class)->findBy([
            'numeroDocumento' => $valor["cedula"]
          ]);
          if (count($entity)>0) {                 
            $chequeado=true;
            $chequeo[]=array("message"=>"La cedula Existe con el usuario: ".$entity[0]->getPrimerNombre()." ".$entity[0]->getPrimerApellido()." ".$entity[0]->getId());
          }else{  
            $ee='';
          }    

          $respfamiliar_empresa='';
          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
          ->from(TiposRespuestas::class,'tipos_respuestas')
          ->Where('tipos_respuestas.id='.$valor["familiar_empresa"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $respfamiliar_empresa = $valorresp["respuestas"];
           }
          $respautorizacion_ingreso='';
          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
          ->from(TiposRespuestas::class,'tipos_respuestas')
          ->Where('tipos_respuestas.id='.$valor["autorizacion_ingreso"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $respautorizacion_ingreso = $valorresp["respuestas"];
           }

           
           if($entity[0]->getFechaNacimiento()!=null){           
            $userFechaNacimiento=$entity[0]->getFechaNacimiento()->format("Y-m-d");
           }else{
            $userFechaNacimiento='Null';
           }

           if($entity[0]->getPrimerNombre()!=null){           
            $userPrimerNombre=ucwords(strtolower(utf8_decode($entity[0]->getPrimerNombre())));
           }else{
            $userPrimerNombre='';
           }
           if($entity[0]->getSegundoNombre()!=null){           
            $userSegundoNombre=ucwords(strtolower(utf8_decode($entity[0]->getSegundoNombre())));
           }else{
            $userSegundoNombre='';
           }
           if($entity[0]->getPrimerApellido()!=null){           
            $userPrimerApellido=ucwords(strtolower(utf8_decode($entity[0]->getPrimerApellido())));
           }else{
            $userPrimerApellido='';
           }
           if($entity[0]->getSegundoApellido()!=null){           
            $userSegundoApellido=ucwords(strtolower(utf8_decode($entity[0]->getSegundoApellido())));
           }else{
            $userSegundoApellido='';
           }

          //$dataDatosPersonales = array($valor["id"],$valor["cedula"]);
          //$dataDatosPersonales = array($valor["id"],$valor["cedula"],$entity[0]->getPrimerNombre() .' '.$entity[0]->getSegundoNombre(),$valor["fecha_ingreso"]->format("Y-m-d"),utf8_decode($resp),utf8_decode($resp));
          $dataDatosPersonales = array($cont,$valor["cedula"],$userPrimerNombre .' '.$userSegundoNombre,$userPrimerApellido .' '.$userSegundoApellido,$userFechaNacimiento,$valor["fecha_ingreso"]->format("Y-m-d"),utf8_decode($respfamiliar_empresa),utf8_decode($respautorizacion_ingreso),$valor["createAt"]->format("Y-m-d"));
          
          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
          //$brochureBroadcast = $Fpdfreporte->pushDatamyTablasRepotes($dataDatosPersonales);
           // break;  
        }

        $idusers = $this->security->getUser()->getId();
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $opcioTokenPdf = new TokenPdf();
        $opcioTokenPdf->setIdUser($idusers);
        $opcioTokenPdf->setDatosQr($nonmarchivo);
        $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
        $opcioTokenPdf->setCreateAt(new \DateTime());
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        if($empresa)
          $entity->setIdempresa($empresa);
        $entityManager->persist($opcioTokenPdf);
        $entityManager->flush(); 

        return array("data"=>$data);

        /* if ($tipreporte==1) {
            //reporte general
            foreach(json_decode($arrusers,true) as $valor){
                $dim = $valor;
            }
        }else{
            //reporte filtrados


        } */
  
       // return new JsonResponse(['msg'=>'Registro Creado','id'=>$arrusers],200);
    }


/**
     * Create Reportes Items.
     */
 public function postItems($tipreporte,$validator,$helper,$em,$Fpdfreporte,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo,$id_datos_personales){
      $entityManagerDefault = $this->getEntityManager();
      //$data = json_decode($arrusers,true);
      $dataDatosPersonales=[];
      //$ci=20;
      $fecha_actual= date("Y/m/d");

switch ($titulotb) {
  case "Datos Personales":
        
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('datos_personales.id,datos_personales.cedula,datos_personales.fecha_ingreso
        ,datos_personales.autorizacion_ingreso,datos_personales.familiar_empresa,datos_personales.createAt')
        ->from(DatosPersonales::class,'datos_personales')
        ->Where('datos_personales.id='.$id_datos_personales)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        
        foreach($data as $clave=>$valor){
            $cont++;
          
          $datospersonalesDto=$valor["id"];
          $datosnumeroDocumento=$valor["cedula"];

          $entity =$entityManagerDefault->getRepository(User::class)->findBy([
            'numeroDocumento' => $valor["cedula"]
          ]);
          if (count($entity)>0) {                 
            $chequeado=true;
            $chequeo[]=array("message"=>"La cedula Existe con el usuario: ".$entity[0]->getPrimerNombre()." ".$entity[0]->getPrimerApellido()." ".$entity[0]->getId());
          }else{  
            $ee='';
          }    

          $respfamiliar_empresa='';
          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
          ->from(TiposRespuestas::class,'tipos_respuestas')
          ->Where('tipos_respuestas.id='.$valor["familiar_empresa"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $respfamiliar_empresa = $valorresp["respuestas"];
           }
          $respautorizacion_ingreso='';
          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
          ->from(TiposRespuestas::class,'tipos_respuestas')
          ->Where('tipos_respuestas.id='.$valor["autorizacion_ingreso"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $respautorizacion_ingreso = $valorresp["respuestas"];
           }

           if($entity[0]->getFechaNacimiento()!=null){           
            $userFechaNacimiento=$entity[0]->getFechaNacimiento()->format("Y-m-d");
           }else{
            $userFechaNacimiento='Null';
           }

           if($entity[0]->getPrimerNombre()!=null){           
            $userPrimerNombre=ucwords(strtolower(utf8_decode($entity[0]->getPrimerNombre())));
           }else{
            $userPrimerNombre='';
           }
           if($entity[0]->getSegundoNombre()!=null){           
            $userSegundoNombre=ucwords(strtolower(utf8_decode($entity[0]->getSegundoNombre())));
           }else{
            $userSegundoNombre='';
           }
           if($entity[0]->getPrimerApellido()!=null){           
            $userPrimerApellido=ucwords(strtolower(utf8_decode($entity[0]->getPrimerApellido())));
           }else{
            $userPrimerApellido='';
           }
           if($entity[0]->getSegundoApellido()!=null){           
            $userSegundoApellido=ucwords(strtolower(utf8_decode($entity[0]->getSegundoApellido())));
           }else{
            $userSegundoApellido='';
           }

          //$dataDatosPersonales = array($valor["id"],$valor["cedula"]);
          //$dataDatosPersonales = array($valor["id"],$valor["cedula"],$entity[0]->getPrimerNombre() .' '.$entity[0]->getSegundoNombre(),$valor["fecha_ingreso"]->format("Y-m-d"),utf8_decode($resp),utf8_decode($resp));
          $dataDatosPersonales = array($cont,$valor["cedula"],$userPrimerNombre .' '.$userSegundoNombre,$userPrimerApellido .' '.$userSegundoApellido,$userFechaNacimiento,$valor["fecha_ingreso"]->format("Y-m-d"),utf8_decode($respfamiliar_empresa),utf8_decode($respautorizacion_ingreso),$valor["createAt"]->format("Y-m-d"));
          
          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
          //$brochureBroadcast = $Fpdfreporte->pushDatamyTablasRepotes($dataDatosPersonales);
           // break;  
        }

        $idusers = $this->security->getUser()->getId();
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $opcioTokenPdf = new TokenPdf();
        $opcioTokenPdf->setIdUser($idusers);
        $opcioTokenPdf->setDatosQr($nonmarchivo);
        $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
        $opcioTokenPdf->setCreateAt(new \DateTime());
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        if($empresa)
          $entity->setIdempresa($empresa);
        $entityManager->persist($opcioTokenPdf);
        $entityManager->flush(); 
        break;  
case "Estudios Academicos":
       //**************************************************
      // DEFINICIÓN DE UNA TABLA DE DATOS
      //**************************************************
      //TITULO DE LA TABLA DE DATOS!!!
      $titulotb =$titulotb;
     //ALTO CELDAS!!!
      $arrtbaltoceldas = array(8,8);
     //ANCHO CELDAS!!!
      $arrtbanchoceldas = array(155,25);
     //la alineación de cada COLUMNA!!!
      $arrtbalineacioncolumna = array('C','C');
     //INICA NUEVO REPORTE CREAR CABECERA Y FOOTER!!!
     //$brochureBroadcast = $Fpdfreporte->pushCabecera_Pie_Repotes($tituloReporte,$logo,$logoqr,$nonmarchivo);
     //NOMBRES DE LAS COLUMNAS!!!
      $arrtbcabecera = array('0' => 'Profesión','1' => 'Fecha Graduado');
     //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
      $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);
     //LLAMADA PARA LA INYECCION DE DATOS!!!
     //$data = $repository->post($request->get('users'),$request->get('tiporeporte'),$validator,$helper,$em,$Fpdfreporte,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);                     

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('estudios_academicos.id,estudios_academicos.idprofesion,estudios_academicos.iddatos_personales
        ,estudios_academicos.fecha_graduado')
        ->from(EstudiosAcademicos::class,'estudios_academicos')
        ->Where('estudios_academicos.iddatos_personales='.$id_datos_personales)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        
        foreach($data as $clave=>$valor){
            $cont++;
          $respProfesion='';
          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('profesion')
          ->from(Profesion::class,'profesion')
          ->Where('profesion.id='.$valor["idprofesion"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp->getId();
            $respProfesion = $valorresp->getDescprofesion();
           }
          $dataDatosPersonales = array(utf8_decode($respProfesion),$valor["fecha_graduado"]->format("Y-m-d"));
          $dime='si';
          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
        }
        break;
 case "Especialidades en Areas":

          //**************************************************
                // DEFINICIÓN DE UNA TABLA DE DATOS
                //**************************************************
                //TITULO DE LA TABLA DE DATOS!!!
                $titulotb =$titulotb;
               //ALTO CELDAS!!!
                $arrtbaltoceldas = array(8);
               //ANCHO CELDAS!!!
                $arrtbanchoceldas = array(180);
               //la alineación de cada COLUMNA!!!
                $arrtbalineacioncolumna = array('C');
               //INICA NUEVO REPORTE CREAR CABECERA Y FOOTER!!!
               //$brochureBroadcast = $Fpdfreporte->pushCabecera_Pie_Repotes($tituloReporte,$logo,$logoqr,$nonmarchivo);
               //NOMBRES DE LAS COLUMNAS!!!
                $arrtbcabecera = array('0' => 'Áreas');
               //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
                $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);
               //LLAMADA PARA LA INYECCION DE DATOS!!!
               //$data = $repository->post($request->get('users'),$request->get('tiporeporte'),$validator,$helper,$em,$Fpdfreporte,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);                     

                  $query = $em->createQueryBuilder();
                  $allAppointmentsQuery = $query->select('especialidades_areas.id,especialidades_areas.id_area,especialidades_areas.id_personales')
                  ->from(EspecialidadesAreas::class,'especialidades_areas')
                  ->Where('especialidades_areas.id_personales='.$id_datos_personales)
                  ->getQuery();
                  $queryult = $query->getQuery();
                  $data =  $queryult->execute();
                  $cont=0;
                  
                  foreach($data as $clave=>$valor){
                      $cont++;
                    $respProfesion='';
                    $queryresp = $em->createQueryBuilder();
                    $allAppointmentsQuery = $queryresp->select('area')
                    ->from(Area::class,'area')
                    ->Where('area.id='.$valor["id_area"])
                    ->getQuery();
                    $queryrespdata = $queryresp->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    foreach($dataresp as $clave=>$valorresp){
                      $idresp = $valorresp->getId();
                      $respArea = $valorresp->getDescarea();
                     }
                    $dataDatosPersonales = array(utf8_decode($respArea));
                    $dime='si';
                    $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
                 } 
              break;          
case "Documentos de ingresos":
     //**************************************************
      // DEFINICIÓN DE UNA TABLA DE DATOS
      //**************************************************
      //TITULO DE LA TABLA DE DATOS!!!
      $titulotb =$titulotb;
     //ALTO CELDAS!!!
      $arrtbaltoceldas = array(8,8,8,8,8,8,8);
     //ANCHO CELDAS!!!
      $arrtbanchoceldas = array(25,26,21,21,28,31,28);
     //la alineación de cada COLUMNA!!!
      $arrtbalineacioncolumna = array('C','C','C','C','C','C','C');
      $arrtbcabecera = array('0' => 'Solicitud Empleo','1' => 'Sintesis Curricular','2' => 'Copia Cédula','3' => 'Const Trabajo',
      '4' => 'Informacion Fiscal','5' => 'Referencias Laborales','6' => 'Declaración Jurada');
     //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
      $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('documentos_ingresos')
        ->from(DocumentosIngresos::class,'documentos_ingresos')
        ->Where('documentos_ingresos.id_datos_personales='.$id_datos_personales)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $cont++;
          $SolicitudEmpleo =  $this->Respuestas($valor->getSolicitudEmpleo(),$em);
          $SintesisCurricular=  $this->Respuestas($valor->getSintesisCurricular(),$em);
          $CopiaCedula=  $this->Respuestas($valor->getCopiaCedula(),$em);
          $ConstanciaTrabajo=  $this->Respuestas($valor->getConstanciaTrabajo(),$em);
          $RegInformacionFiscal=  $this->Respuestas($valor->getRegInformacionFiscal(),$em);
          $VerificacionRefLaborales=  $this->Respuestas($valor->getVerificacionRefLaborales(),$em);
          $CertificacionDeclaracionJurada=  $this->Respuestas($valor->getCertificacionDeclaracionJurada(),$em);
          $Licencia=  $this->Respuestas($valor->getLicencia(),$em);
          $CertificadoMedico=  $this->Respuestas($valor->getCertificadoMedico(),$em);
          $PuntoCuenta=  $this->Respuestas($valor->getPuntoCuenta(),$em);
          $PoseerTitulo=  $this->Respuestas($valor->getPoseerTitulo(),$em);
          $Confidencialidad=  $this->Respuestas($valor->getIdConfidencialidad(),$em);
          $DescripcionCargo=  $this->Respuestas($valor->getDescripcionCargo(),$em);

           $queryrespcargo = $entityManagerDefault->createQueryBuilder();
           $allAppointmentsQuery = $queryrespcargo->select('cargo.id,cargo.descripcion')
          ->from(Cargo::class,'cargo')
          ->Where('cargo.id='.$valor->getIdCargo())  
          ->getQuery();
          $queryrespdata = $queryrespcargo->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorcargo){
            $idresp = $valorcargo["id"];
            $respcargos = $valorcargo["descripcion"];
          }
           
          $queryreg = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryreg->select('region')
          ->from(Region::class,'region')
          ->Where('region.id='.$valor->getIdRegion())
          ->getQuery();
          $queryult = $queryreg->getQuery();
          $data =  $queryult->execute();
          $cont=0;
          foreach($data as $clave=>$valorreg){
            $idregion=$valorreg->getId();
            $descregion=$valorreg->getDescregion();
        }

           $query = $em->createQueryBuilder();
           $allAppointmentsQuery = $query->select('departamento')
           ->from(Departamento::class,'departamento')
           ->Where('departamento.id='.$valor->getIdDepartamento())
           ->getQuery();
           $queryult = $query->getQuery();
           $data =  $queryult->execute();
           $cont=0;
           foreach($data as $clave=>$valordept){
             $idrespdeaprt=$valordept->getId();
             $desdepartamento=$valordept->getDescdepartamento();
         }

          $queryresparea = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresparea->select('area')
          ->from(Area::class,'area')
          ->Where('area.id='.$valor->getIdArea())
          ->getQuery();
          $queryrespdata = $queryresparea->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorarea){
            $idresparea = $valorarea->getId();
            $respArea = $valorarea->getDescarea();
           }
          $dataDatosPersonales = array(utf8_decode($SolicitudEmpleo),utf8_decode($SintesisCurricular),utf8_decode($CopiaCedula),utf8_decode($ConstanciaTrabajo),utf8_decode($RegInformacionFiscal)
          ,utf8_decode($VerificacionRefLaborales),utf8_decode($CertificacionDeclaracionJurada));
          $dime='si';
          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
          $titulotb ="";
          //ALTO CELDAS!!!
          $arrtbaltoceldas = array(8,8,8,8,8,8);
          //ANCHO CELDAS!!!
          $arrtbanchoceldas = array(28,28,28,28,30,38);
          //la alineación de cada COLUMNA!!!
          $arrtbalineacioncolumna = array('C','C','C','C','C','C');
          $arrtbcabecera = array('0' => 'Licencia','1' => 'Certificado Medico','2' => 'Punto Cuenta','3' => 'Poseer Título','4' => 'Descripcion Cargo'
          ,'5' => 'Contrato Confidencialidad'); 
          //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
          $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);
          
          $dataDatosPersonales = array(utf8_decode($Licencia),utf8_decode($CertificadoMedico),utf8_decode($PuntoCuenta),utf8_decode($PoseerTitulo)
          ,utf8_decode($DescripcionCargo),utf8_decode($Confidencialidad));  
          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);

          $titulotb ="";
          //ALTO CELDAS!!!
          $arrtbaltoceldas = array(8,8,8,8);
          //ANCHO CELDAS!!!
          $arrtbanchoceldas = array(45,45,45,45);
          //la alineación de cada COLUMNA!!!
          $arrtbalineacioncolumna = array('C','C','C','C');
          $arrtbcabecera = array('0' => 'Cargo','1' => 'Region','2' => 'Departamento','3' => 'Área'); 
          //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
          $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);
          $dataDatosPersonales = array(utf8_decode($respcargos),utf8_decode($descregion),utf8_decode($desdepartamento),utf8_decode($respArea));  
          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
       } 
      break;
 case "Controles varios":
      //**************************************************
      // DEFINICIÓN DE UNA TABLA DE DATOS
      //**************************************************
      //TITULO DE LA TABLA DE DATOS!!!
      $titulotb =$titulotb;
     //ALTO CELDAS!!!
      $arrtbaltoceldas = array(8,8,8);
     //ANCHO CELDAS!!!
      $arrtbanchoceldas = array(60,60,60);
     //la alineación de cada COLUMNA!!!
      $arrtbalineacioncolumna = array('C','C','C');
      $arrtbcabecera = array('0' => 'Normas Internas','1' => 'Inscrito Ivss','2' => 'Forma Ari');
     //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
      $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('controles_varios')
        ->from(ControlesVarios::class,'controles_varios')
        ->Where('controles_varios.id_datos_personales='.$id_datos_personales)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $cont++;
          $NormasInternas =  $this->Respuestas($valor->getNormasInternas(),$em);
          $InscritoIvss=  $this->Respuestas($valor->getInscritoIvss(),$em);
          $FormaAri=  $this->Respuestas($valor->getFormaAri(),$em);

          $dataDatosPersonales = array(utf8_decode($NormasInternas),utf8_decode($InscritoIvss),utf8_decode($FormaAri));  

          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);


       } 
      break;

 case "Movimientos (Transferencia)":
      //**************************************************
      // DEFINICIÓN DE UNA TABLA DE DATOS
      //**************************************************
      //TITULO DE LA TABLA DE DATOS!!!
      $titulotb =$titulotb;
     //ALTO CELDAS!!!
      $arrtbaltoceldas = array(8,8,8,8);
     //ANCHO CELDAS!!!
      $arrtbanchoceldas = array(45,45,45,45);
     //la alineación de cada COLUMNA!!!
      $arrtbalineacioncolumna = array('C','C','C','C');
      $arrtbcabecera = array('0' => 'Departamento','1' => 'Área','2' => 'Region','3' => 'Fecha Transferencia');
     //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
      $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('hist_mov_transferencias')
        ->from(HistMovTransferencias::class,'hist_mov_transferencias')
        ->Where('hist_mov_transferencias.id_datos_personales='.$id_datos_personales)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $cont++;
          $queryreg = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryreg->select('region')
          ->from(Region::class,'region')
          ->Where('region.id='.$valor->getIdRegion())
          ->getQuery();
          $queryult = $queryreg->getQuery();
          $data =  $queryult->execute();
          $cont=0;
          foreach($data as $clave=>$valorreg){
            $idregion=$valorreg->getId();
            $descregion=$valorreg->getDescregion();
        }

           $query = $em->createQueryBuilder();
           $allAppointmentsQuery = $query->select('departamento')
           ->from(Departamento::class,'departamento')
           ->Where('departamento.id='.$valor->getIdDepartamento())
           ->getQuery();
           $queryult = $query->getQuery();
           $data =  $queryult->execute();
           $cont=0;
           foreach($data as $clave=>$valordept){
             $idrespdeaprt=$valordept->getId();
             $desdepartamento=$valordept->getDescdepartamento();
         }

          $queryresparea = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresparea->select('area')
          ->from(Area::class,'area')
          ->Where('area.id='.$valor->getIdArea())
          ->getQuery();
          $queryrespdata = $queryresparea->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorarea){
            $idresparea = $valorarea->getId();
            $respArea = $valorarea->getDescarea();
           }

          $dataDatosPersonales = array(utf8_decode($desdepartamento),utf8_decode($respArea),utf8_decode($descregion),$valor->getFechaTransferencia()->format("Y-m-d"));  

          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
       } 
      break;

 case "Movimientos (Promoción)":
      //**************************************************
      // DEFINICIÓN DE UNA TABLA DE DATOS
      //**************************************************
      //TITULO DE LA TABLA DE DATOS!!!
      $titulotb =$titulotb;
     //ALTO CELDAS!!!
      $arrtbaltoceldas = array(8,8);
     //ANCHO CELDAS!!!
      $arrtbanchoceldas = array(90,90);
     //la alineación de cada COLUMNA!!!
      $arrtbalineacioncolumna = array('C','C');
      $arrtbcabecera = array('0' => 'Cargo','1' => 'Fecha Fecha Promoción');
     //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
      $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('hist_mov_transferencias')
        ->from(HistMovPromocion::class,'hist_mov_transferencias')
        ->Where('hist_mov_transferencias.id_datos_personales='.$id_datos_personales)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $cont++;
          $queryrespcargo = $entityManagerDefault->createQueryBuilder();
          $allAppointmentsQuery = $queryrespcargo->select('cargo.id,cargo.descripcion')
         ->from(Cargo::class,'cargo')
         ->Where('cargo.id='.$valor->getIdCargo())  
         ->getQuery();
         $queryrespdata = $queryrespcargo->getQuery();
         $dataresp =  $queryrespdata->execute();
         foreach($dataresp as $clave=>$valorcargo){
           $idresp = $valorcargo["id"];
           $respcargos = $valorcargo["descripcion"];
         }
          
          
          
          $dataDatosPersonales = array(utf8_decode($respcargos),$valor->getFechaPromocion()->format("Y-m-d"));  

          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
       } 
      break;

 case "Movimientos (Vacaciones)":
      //**************************************************
      // DEFINICIÓN DE UNA TABLA DE DATOS
      //**************************************************
      //TITULO DE LA TABLA DE DATOS!!!
      $titulotb =$titulotb;
     //ALTO CELDAS!!!
      $arrtbaltoceldas = array(8,8,8,8,8,8,8,8);
     //ANCHO CELDAS!!!
      $arrtbanchoceldas = array(20,20,32,28,20,20,20,20);
     //la alineación de cada COLUMNA!!!
      $arrtbalineacioncolumna = array('C','C','C','C','C','C','C','C');
      $arrtbcabecera = array('0' => 'Autorización','1' => 'Vacaciones','2' => 'Periodos Acumulados','3' => 'Periodo Difrute',
      '4' => 'Tiempo Difrute','5' => 'Desde','6' => 'Hasta','7' => 'Incorporación');
     //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
      $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('vaciones')
        ->from(Vaciones::class,'vaciones')
        ->Where('vaciones.id_datos_personales='.$id_datos_personales)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $cont++;
          $queryrespcargo = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryrespcargo->select('tipo_vacaciones')
         ->from(TipoVacaciones::class,'tipo_vacaciones')
         ->Where('tipo_vacaciones.id='.$valor->getIdTipoVacaciones())  
         ->getQuery();
         $queryrespdata = $queryrespcargo->getQuery();
         $dataresp =  $queryrespdata->execute();
         foreach($dataresp as $clave=>$valorcargo){
           $idresp = $valorcargo->getId();
           $resptipovacaciones = $valorcargo->getVacaciones();
         }
         $AutorizacionVacaciones=  $this->Respuestas($valor->getAutorizacionVacaciones(),$em);
         $fecha_des=$valor->getFechaDesde()->format("Y-m-d");
         $fecha_hat=$valor->getFechaHasta()->format("Y-m-d");
         $Tiempdifrute=$this->dias_pasados($fecha_des,$fecha_hat);
         $dataDatosPersonales = array(utf8_decode($AutorizacionVacaciones),utf8_decode($resptipovacaciones),utf8_decode($valor->getPeriodosAcumulados()),$valor->getPeriodoDifrute(),$Tiempdifrute,$valor->getFechaDesde()->format("Y-m-d"),$valor->getFechaHasta()->format("Y-m-d"),$valor->getFechaIncorporacion()->format("Y-m-d"));  
          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
       } 

       $queryrespobervac = $em->createQueryBuilder();
       $allAppointmentsQuery = $queryrespobervac->select('vacaciones_observacion')
      ->from(VacacionesObservacion::class,'vacaciones_observacion')
      ->Where('vacaciones_observacion.id_datos_personales='.$id_datos_personales)  
      ->getQuery();
      $queryrespdata = $queryrespobervac->getQuery();
      $dataresp =  $queryrespdata->execute();
      foreach($dataresp as $clave=>$valorcargo){
        $idresp = $valorcargo->getId();
        $respobservacion = $valorcargo->getObservacion();
      }

       //**************************************************
      // DEFINICIÓN DE UNA TABLA DE DATOS
      //**************************************************
      //TITULO DE LA TABLA DE DATOS!!!
       $titulotb ="";
      //ALTO CELDAS!!!
       $arrtbaltoceldas = array(8);
      //ANCHO CELDAS!!!
       $arrtbanchoceldas = array(180);
      //la alineación de cada COLUMNA!!!
       $arrtbalineacioncolumna = array('C');
       $arrtbcabecera = array('0' => 'Observación');
      //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
       $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr); 
       
       $dataDatosPersonales = array(utf8_decode($respobservacion));  

       $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);


      break;

 case "Movimientos (Permisos)":

      //**************************************************
      // DEFINICIÓN DE UNA TABLA DE DATOS
      //**************************************************
      //TITULO DE LA TABLA DE DATOS!!!
      $titulotb =$titulotb;
     //ALTO CELDAS!!!
      $arrtbaltoceldas = array(8,8,8,8,8);
     //ANCHO CELDAS!!!
      $arrtbanchoceldas = array(20,89,23,28,20);
     //la alineación de cada COLUMNA!!!
      $arrtbalineacioncolumna = array('C','C','C','C','C');
      $arrtbcabecera = array('0' => 'Autorización','1' => 'Motivo Permiso','2' => 'Tiempo Permiso','3' => 'Desde','4' => 'Hasta');
     //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
      $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('Permisos')
        ->from(Permisos::class,'Permisos')
        ->Where('Permisos.id_datos_personales='.$id_datos_personales)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $cont++;
          $queryrespcargo = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryrespcargo->select('tipo_motivo_permiso')
         ->from(TipoMotivoPermiso::class,'tipo_motivo_permiso')
         ->Where('tipo_motivo_permiso.id='.$valor->getIdMotivoPermiso())  
         ->getQuery();
         $queryrespdata = $queryrespcargo->getQuery();
         $dataresp =  $queryrespdata->execute();
         foreach($dataresp as $clave=>$valorcargo){
           $idresp = $valorcargo->getId();
           $resptipopermiso = $valorcargo->getPermiso();
         }
         $AutorizacionPermisos=  $this->Respuestas($valor->getAutorizacionPermisos(),$em);
         $fecha_des=$valor->getFechaDesde()->format("Y-m-d");
         $fecha_hat=$valor->getFechaHasta()->format("Y-m-d");
         $Tiempdifrute=$this->dias_pasados($fecha_des,$fecha_hat);
         $dataDatosPersonales = array(utf8_decode($AutorizacionPermisos),utf8_decode($resptipopermiso),$Tiempdifrute,$valor->getFechaDesde()->format("Y-m-d"),$valor->getFechaHasta()->format("Y-m-d"));  
         $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
          
       } 
      break;

      case "Movimientos (Reposos)":    

      //**************************************************
      // DEFINICIÓN DE UNA TABLA DE DATOS
      //**************************************************
      //TITULO DE LA TABLA DE DATOS!!!
      $titulotb =$titulotb;
     //ALTO CELDAS!!!
      $arrtbaltoceldas = array(8,8,8,8,8);
     //ANCHO CELDAS!!!
      $arrtbanchoceldas = array(54,54,23,28,20);
     //la alineación de cada COLUMNA!!!
      $arrtbalineacioncolumna = array('C','C','C','C','C');
      $arrtbcabecera = array('0' => 'Motivo Reposo','1' => 'Tipo Reposo','2' => 'Tiempo Permiso','3' => 'Desde','4' => 'Hasta');
     //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
      $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('reposo')
        ->from(Reposo::class,'reposo')
        ->Where('reposo.id_datos_personales='.$id_datos_personales)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $cont++;
          $queryrespcargo = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryrespcargo->select('tipo_motivo_reposo')
         ->from(TipoMotivoReposo::class,'tipo_motivo_reposo')
         ->Where('tipo_motivo_reposo.id='.$valor->getIdMotivoReposo())  
         ->getQuery();
         $queryrespdata = $queryrespcargo->getQuery();
         $dataresp =  $queryrespdata->execute();
         foreach($dataresp as $clave=>$valorcargo){
           $idresp = $valorcargo->getId();
           $MotivoReposo = $valorcargo->getMotivo();
         }

         $queryrespcargo = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryrespcargo->select('tipo_reposo')
         ->from(TipoReposo::class,'tipo_reposo')
         ->Where('tipo_reposo.id='.$valor->getIdTiporeposo())  
         ->getQuery();
         $queryrespdata = $queryrespcargo->getQuery();
         $dataresp =  $queryrespdata->execute();
         foreach($dataresp as $clave=>$valorcargo){
           $idresp = $valorcargo->getId();
           $resptiporeposo = $valorcargo->getTiporeposo();
         }
         
         $fecha_des=$valor->getFechaDesde()->format("Y-m-d");
         $fecha_hat=$valor->getFechaHasta()->format("Y-m-d");
         $Tiempdifrute=$this->dias_pasados($fecha_des,$fecha_hat);
         $dataDatosPersonales = array(utf8_decode($MotivoReposo),utf8_decode($resptiporeposo),$Tiempdifrute,$valor->getFechaDesde()->format("Y-m-d"),$valor->getFechaHasta()->format("Y-m-d"));  

          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
          
       } 
      break;

 case "Fideicomiso":    

  //**************************************************
      // DEFINICIÓN DE UNA TABLA DE DATOS
      //**************************************************
      //TITULO DE LA TABLA DE DATOS!!!
      $titulotb =$titulotb;
     //ALTO CELDAS!!!
      $arrtbaltoceldas = array(8,8);
     //ANCHO CELDAS!!!
      $arrtbanchoceldas = array(90,90);
     //la alineación de cada COLUMNA!!!
      $arrtbalineacioncolumna = array('C','C');
      $arrtbcabecera = array('0' => 'Deposito prestaciones sociales','1' => 'Recibo Anual Intereses');
     //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
      $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('fidei_comiso')
        ->from(FideiComiso::class,'fidei_comiso')
        ->Where('fidei_comiso.id_datos_personales='.$id_datos_personales)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $cont++;
          $ConstDeposPrestacSociales =  $this->Respuestas($valor->getConstDeposPrestacSociales(),$em);
          $ReciboAnualIntereses=  $this->Respuestas($valor->getReciboAnualIntereses(),$em);

          $dataDatosPersonales = array(utf8_decode($ConstDeposPrestacSociales),utf8_decode($ReciboAnualIntereses));  

          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);


       } 
      break;
  
 case "Seguridad y Salud Laboral":    

//**************************************************
      // DEFINICIÓN DE UNA TABLA DE DATOS
      //**************************************************
      //TITULO DE LA TABLA DE DATOS!!!
      $titulotb =$titulotb;
     //ALTO CELDAS!!!
      $arrtbaltoceldas = array(8,8,8,8,8,8);
     //ANCHO CELDAS!!!
      $arrtbanchoceldas = array(20,30,30,35,30,35);
     //la alineación de cada COLUMNA!!!
      $arrtbalineacioncolumna = array('C','C','C','C','C','C');
      $arrtbcabecera = array('0' => 'Ruta Metro','1' => 'Analisis de seguridad','2' => 'Equipo Proteccion','3' => 'Examenes Ocupacionales',
      '4' => 'Normas Seguridad','5' => 'Copia Registro Delegado');
     //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
      $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('seguridad_salud_laboral')
        ->from(SeguridadSaludLaboral::class,'seguridad_salud_laboral')
        ->Where('seguridad_salud_laboral.id_datos_personales='.$id_datos_personales)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $cont++;
          $RutaMetro=  $this->Respuestas($valor->getRutaMetro(),$em);
          $AnalisisSeguroTrabajo=  $this->Respuestas($valor->getAnalisisSeguroTrabajo(),$em);
          $EntregaEquipoProteccion=  $this->Respuestas($valor->getEntregaEquipoProteccion(),$em);
          $ConstanciaExamenesOcupacionales=  $this->Respuestas($valor->getConstanciaExamenesOcupacionales(),$em);
          $ConstanciaNormasSeguridad=  $this->Respuestas($valor->getConstanciaNormasSeguridad(),$em);
          $CopiaRegistroDelegado=  $this->Respuestas($valor->getCopiaRegistroDelegado(),$em);

          $Tiempdifrute=0;
          
          $dataDatosPersonales = array(utf8_decode($RutaMetro),utf8_decode($AnalisisSeguroTrabajo),utf8_decode($EntregaEquipoProteccion),utf8_decode($ConstanciaExamenesOcupacionales),utf8_decode($ConstanciaNormasSeguridad),utf8_decode($CopiaRegistroDelegado));  

          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
       }
       break;

 case "Otros":    
      
//**************************************************
      // DEFINICIÓN DE UNA TABLA DE DATOS
      //**************************************************
      //TITULO DE LA TABLA DE DATOS!!!
      $titulotb =$titulotb;
     //ALTO CELDAS!!!
      $arrtbaltoceldas = array(8,8,8,8,8);
     //ANCHO CELDAS!!!
      $arrtbanchoceldas = array(27,42,15,40,55);
     //la alineación de cada COLUMNA!!!
      $arrtbalineacioncolumna = array('C','C','C','C','C');
      $arrtbcabecera = array('0' => 'Expedientes Legal','1' => 'Curso desarrollo Área Laboral','2' => 'Categoría','3' => 'Motivo Expediente',
      '4' => 'Observación');
     //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
      $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('otros')
        ->from(Otros::class,'otros')
        ->Where('otros.id_datos_personales='.$id_datos_personales)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $cont++;
          $ExpedientesLegal=  $this->Respuestas($valor->getExpedientesLegal(),$em);
          $CursoDesarrolloAreaLaboral=  $this->Respuestas($valor->getCursoDesarrolloAreaLaboral(),$em);
          
          $queryrespcargo = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryrespcargo->select('categorias_otros')
         ->from(CategoriasOtros::class,'categorias_otros')
         ->Where('categorias_otros.id='.$valor->getIdCategoriaOtros())  
         ->getQuery();
         $queryrespdata = $queryrespcargo->getQuery();
         $dataresp =  $queryrespdata->execute();
         foreach($dataresp as $clave=>$valorcargo){
           $idresp = $valorcargo->getId();
           $CategoriasOtros = $valorcargo->getTipcategoria();
         }

          $querymotivoexp = $em->createQueryBuilder();
          $allAppointmentsQuery = $querymotivoexp->select('tipo_motivo_expediente')
         ->from(TipoMotivoExpediente::class,'tipo_motivo_expediente')
         ->Where('tipo_motivo_expediente.id='.$valor->getMotivo())  
         ->getQuery();
         $queryrespdata = $querymotivoexp->getQuery();
         $dataresp =  $queryrespdata->execute();
         foreach($dataresp as $clave=>$valorcargo){
           $idresp = $valorcargo->getId();
           $Motivoexpediente = $valorcargo->getMotivoexpediente();
         }
          $Tiempdifrute=0;
          $dataDatosPersonales = array(utf8_decode($ExpedientesLegal),utf8_decode($CursoDesarrolloAreaLaboral),utf8_decode($CategoriasOtros),utf8_decode($Motivoexpediente),utf8_decode($valor->getProfesionOrientadaAreaServicio()));  
          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
       }
       break;
   }
    
   return array("data"=>$data);
}


/**
* Create Reportes Detalles .
*/
public function postDetalles($validator,$helper,$em,$logoqr,$headers,$nonmarchivo,$codQr){
        $entityManagerDefault = $this->getEntityManager();
        $datadetalles=[];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
            $sheet->setCellValueByColumnAndRow($i + 1, 1, $headers[$i]);
        }

      $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
       if($empresa)
        $idemp = $empresa->getId();
          $sql = "SELECT d.`id`,d.`cedula`,d.`fecha_ingreso`,d.`familiar_empresa`,d.`autorizacion_ingreso`,d.`idempresa`
          FROM `datos_personales` d where  idempresa =".$idemp." ";   

        $conn = $em->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result= $stmt->fetchAll();
        $conrg=1;
        //$result= $stmt->executeQuery();
        foreach($result as $claveResult=>$valorResult){

          $idprofesion=0;

          $fech_ingreso1 = strtotime($valorResult["fecha_ingreso"]);
          $fech_ingreso = date('Y-m-d', $fech_ingreso1);
            
            $currentUser =$entityManagerDefault->getRepository(User::class)->findBy([
                'numeroDocumento' => $valorResult["cedula"]
            ]);

            if (count($currentUser)>0) {                 
                $cedula = $currentUser[0]->getNumeroDocumento();
                $nombres = $currentUser[0]->getPrimerNombre().' '.$currentUser[0]->getSegundoNombre();
                $epellidos = $currentUser[0]->getPrimerApellido().' '.$currentUser[0]->getSegundoApellido();
                if(!is_null($currentUser[0]->getFechaNacimiento())) {
                  $fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
                }else{
                  $fech_nacimiento = null;
                }
                

                $sexo = $currentUser[0]->getSexo();
            }
            
            $query = $em->createQueryBuilder();

            $allAppointmentsQuery = $query->select('documentos_ingresos.id,documentos_ingresos.id_datos_personales,
            documentos_ingresos.solicitud_empleo,documentos_ingresos.sintesis_curricular,documentos_ingresos.copia_cedula,documentos_ingresos.constancia_trabajo,
            documentos_ingresos.reg_informacion_fiscal,documentos_ingresos.verificacion_ref_laborales,documentos_ingresos.certificacion_declaracion_jurada,
            documentos_ingresos.licencia,documentos_ingresos.certificado_medico,documentos_ingresos.punto_cuenta,documentos_ingresos.poseer_titulo,
            documentos_ingresos.descripcion_cargo,documentos_ingresos.id_confidencialidad,documentos_ingresos.id_cargo,documentos_ingresos.id_departamento,
            documentos_ingresos.id_area,documentos_ingresos.id_region')
            ->from(DocumentosIngresos::class,'documentos_ingresos') 
            ->Where('documentos_ingresos.id_datos_personales='.$valorResult["id"])
            //->addOrderBy('id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $data =  $queryult->execute();
            $Solicitud_empleo='';
            $Sintesis_curricular='';
            $Copia_cedula='';
            $Constancia_trabajo='';
            $Reg_informacion_fiscal='';
            $Verificacion_ref_laborales='';
            $Certificacion_declaracion_jurada='';
            $Licencia='';
            $Certificado_medico='';
            $Punto_cuenta='';
            $Poseer_titulo='';
            $Descripcion_cargo='';
            $id_confidencialidad='';
            $respCargo='';
            $respDepartamento='';
            $respArea='';
            $respRegion='';
            $Normas_internas='';
            $Forma_ari='';
            $Inscrito_ivss='';
            $Const_depos_prestac_sociales='';
            $Recibo_anual_intereses='';
            $Ruta_metro='';
            $Analisis_seguro_trabajo='';
            $Entrega_equipo_proteccion='';
            $Constancia_examenes_ocupacionales='';
            $Constancia_normas_seguridad='';
            $Copia_registro_delegado='';
            $Expedientes_legal='';
            $Curso_desarrollo_area_laboral='';
            $respcategoria='';
            $respmotivoexpediente='';
            $Observ='';

            foreach($data as $clave=>$valor){
              $Solicitud_empleo= $this->Respuestas($valor["solicitud_empleo"],$em);
              $Sintesis_curricular= $this->Respuestas($valor["sintesis_curricular"],$em);
              $Copia_cedula= $this->Respuestas($valor["copia_cedula"],$em);
              $Constancia_trabajo= $this->Respuestas($valor["constancia_trabajo"],$em);
              $Reg_informacion_fiscal= $this->Respuestas($valor["reg_informacion_fiscal"],$em);
              $Verificacion_ref_laborales= $this->Respuestas($valor["verificacion_ref_laborales"],$em);
              $Certificacion_declaracion_jurada= $this->Respuestas($valor["certificacion_declaracion_jurada"],$em);
              $Licencia= $this->Respuestas($valor["licencia"],$em);
              $Certificado_medico= $this->Respuestas($valor["certificado_medico"],$em);
              $Punto_cuenta= $this->Respuestas($valor["punto_cuenta"],$em);
              $Poseer_titulo= $this->Respuestas($valor["poseer_titulo"],$em);
              $Descripcion_cargo= $this->Respuestas($valor["descripcion_cargo"],$em);
              $id_confidencialidad= $this->Respuestas($valor["id_confidencialidad"],$em);

              $queryresp = $em->createQueryBuilder();
              $allAppointmentsQuery = $queryresp->select('departamento.id,departamento.descdepartamento')
              ->from(Departamento::class,'departamento')
              ->Where('departamento.id='.$valor["id_departamento"])
              ->getQuery();
              $queryrespdata = $queryresp->getQuery();
              $dataresp =  $queryrespdata->execute();
              foreach($dataresp as $clave=>$valorresp){
                  $idresp = $valorresp["id"];
                  $respDepartamento = $valorresp["descdepartamento"];
              }
  
            
  
              $queryresp = $em->createQueryBuilder();
              $allAppointmentsQuery = $queryresp->select('region.id,region.descregion')
              ->from(Region::class,'region')
              ->Where('region.id='.$valor["id_region"])
              ->getQuery();
              $queryrespdata = $queryresp->getQuery();
              $dataresp =  $queryrespdata->execute();
              foreach($dataresp as $clave=>$valorresp){
                $idresp = $valorresp["id"];
                $respRegion = $valorresp["descregion"];
               }
   
             $queryresp = $em->createQueryBuilder();
             $allAppointmentsQuery = $queryresp->select('area.id,area.descarea,area.iddepartamentos')
             ->from(Area::class,'area')
             ->Where('area.id='.$valor["id_area"])
             ->getQuery();
             $queryrespdata = $queryresp->getQuery();
             $dataresp =  $queryrespdata->execute();
             foreach($dataresp as $clave=>$valorresp){
               $idresp = $valorresp["id"];
               $respArea = $valorresp["descarea"];
              }
  
              $queryresp = $entityManagerDefault->createQueryBuilder();
              $allAppointmentsQuery = $queryresp->select('cargo.id,cargo.descripcion')
              ->from(Cargo::class,'cargo')
              ->Where('cargo.id='.$valor["id_cargo"])
              ->getQuery();
              $queryrespdata = $queryresp->getQuery();
              $dataresp =  $queryrespdata->execute();
              foreach($dataresp as $clave=>$valorresp){
                  $idresp = $valorresp["id"];
                  $respCargo = $valorresp["descripcion"];
              } 

            }    



            $query = $em->createQueryBuilder();

            $allAppointmentsQuery = $query->select('controles_varios.id,controles_varios.id_datos_personales,controles_varios.normas_internas
            ,controles_varios.inscrito_ivss,controles_varios.forma_ari') 
            ->from(ControlesVarios::class,'controles_varios') 
            ->Where('controles_varios.id_datos_personales='.$valorResult["id"])
            //->addOrderBy('id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $data =  $queryult->execute();
            foreach($data as $clave=>$valor){
              $Normas_internas= $this->Respuestas($valor["normas_internas"],$em);
              $Forma_ari= $this->Respuestas($valor["forma_ari"],$em);
              $Inscrito_ivss= $this->Respuestas($valor["inscrito_ivss"],$em);
            }

            $query = $em->createQueryBuilder();

            $allAppointmentsQuery = $query->select('fidei_comiso.id,fidei_comiso.id_datos_personales,fidei_comiso.const_depos_prestac_sociales
            ,fidei_comiso.recibo_anual_intereses') 
            ->from(FideiComiso::class,'fidei_comiso') 
            ->Where('fidei_comiso.id_datos_personales='.$valorResult["id"])
            //->addOrderBy('id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $data =  $queryult->execute();
            foreach($data as $clave=>$valor){
              $Const_depos_prestac_sociales= $this->Respuestas($valor["const_depos_prestac_sociales"],$em);
              $Recibo_anual_intereses= $this->Respuestas($valor["recibo_anual_intereses"],$em);
            }

            $query = $em->createQueryBuilder();

            $allAppointmentsQuery = $query->select('seguridad_salud_laboral.id,seguridad_salud_laboral.id_datos_personales,
            seguridad_salud_laboral.ruta_metro,seguridad_salud_laboral.analisis_seguro_trabajo,seguridad_salud_laboral.entrega_equipo_proteccion
            ,seguridad_salud_laboral.constancia_examenes_ocupacionales,seguridad_salud_laboral.constancia_normas_seguridad,
            seguridad_salud_laboral.copia_registro_delegado')
            ->from(SeguridadSaludLaboral::class,'seguridad_salud_laboral') 
            ->Where('seguridad_salud_laboral.id_datos_personales='.$valorResult["id"])
            //->addOrderBy('id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $data =  $queryult->execute();
            foreach($data as $clave=>$valor){
              $Ruta_metro= $this->Respuestas($valor["ruta_metro"],$em);
              $Analisis_seguro_trabajo= $this->Respuestas($valor["analisis_seguro_trabajo"],$em);
              $Entrega_equipo_proteccion= $this->Respuestas($valor["entrega_equipo_proteccion"],$em);
              $Constancia_examenes_ocupacionales= $this->Respuestas($valor["constancia_examenes_ocupacionales"],$em);
              $Constancia_normas_seguridad= $this->Respuestas($valor["constancia_normas_seguridad"],$em);
              $Copia_registro_delegado= $this->Respuestas($valor["copia_registro_delegado"],$em);
            }


            $query = $em->createQueryBuilder();

   
            $allAppointmentsQuery = $query->select('otros.id,otros.id_datos_personales,otros.expedientes_legal,otros.motivo
            ,otros.curso_desarrollo_area_laboral,otros.profesion_orientada_area_servicio,otros.id_categoria_otros')
            ->from(Otros::class,'otros') 
            ->Where('otros.id_datos_personales='.$valorResult["id"])
            //->addOrderBy('id', 'ASC')
            ->getQuery();
            $queryult = $query->getQuery();
            $data =  $queryult->execute();
            foreach($data as $clave=>$valor){
              $Expedientes_legal= $this->Respuestas($valor["expedientes_legal"],$em);
              $Curso_desarrollo_area_laboral= $this->Respuestas($valor["curso_desarrollo_area_laboral"],$em);
              $Observ =  $valor["profesion_orientada_area_servicio"];

              $queryresp = $em->createQueryBuilder();
              $allAppointmentsQuery = $queryresp->select('tipo_motivo_expediente.id,tipo_motivo_expediente.motivoexpediente')
              ->from(TipoMotivoExpediente::class,'tipo_motivo_expediente')
              ->Where('tipo_motivo_expediente.id='.$valor["motivo"])
              ->getQuery();
              $queryrespdata = $queryresp->getQuery();
              $dataresp =  $queryrespdata->execute();
              foreach($dataresp as $clave=>$valorresp){
                $idresp = $valorresp["id"];
                $respmotivoexpediente = $valorresp["motivoexpediente"];
               }
    
    
              $queryresp = $em->createQueryBuilder();
                $allAppointmentsQuery = $queryresp->select('categorias_otros.id,categorias_otros.tipcategoria')
                ->from(CategoriasOtros::class,'categorias_otros')
                ->Where('categorias_otros.id='.$valor["id_categoria_otros"])
                ->getQuery();
                $queryrespdata = $queryresp->getQuery();
                $dataresp =  $queryrespdata->execute();
                foreach($dataresp as $clave=>$valorresp){
                  $idresp = $valorresp["id"];
                  $respcategoria = $valorresp["tipcategoria"];
                 }
          }


            $Familiarempresa= $this->Respuestas($valorResult["familiar_empresa"],$em);
            $Autorizacioningreso= $this->Respuestas($valorResult["autorizacion_ingreso"],$em);
         

            $datadetalles[] = array('0' => $conrg,'1' => $cedula,'2' => $nombres,'3' => $epellidos,'4' => $fech_nacimiento
            ,'5' => $fech_ingreso,'6' => $Familiarempresa,'7' => $Autorizacioningreso,'8' => $Solicitud_empleo,'9' => $Sintesis_curricular
            ,'10' => $Copia_cedula,'11' => $Constancia_trabajo,'12' => $Reg_informacion_fiscal,'13' => $Verificacion_ref_laborales
            ,'14' => $Certificacion_declaracion_jurada,'15' => $Licencia,'16' => $Certificado_medico,'17' => $Punto_cuenta
            ,'18' => $Poseer_titulo,'19' => $Descripcion_cargo,'20' => $id_confidencialidad,'21' => $respCargo,'22' => $respDepartamento
            ,'23' => $respArea,'24' => $respRegion,'25' => $Normas_internas,'26' => $Forma_ari,'27' => $Inscrito_ivss,'28' => $Const_depos_prestac_sociales
            ,'29' => $Recibo_anual_intereses,'30' => $Ruta_metro,'31' => $Analisis_seguro_trabajo,'32' => $Entrega_equipo_proteccion,
            '33' => $Constancia_examenes_ocupacionales,'34' => $Constancia_normas_seguridad,'35' => $Copia_registro_delegado
            ,'36' => $Expedientes_legal,'37' => $Curso_desarrollo_area_laboral,'38' => $respcategoria,'39' => $respmotivoexpediente
            ,'40' => $Observ
          
          
          );
            $conrg++;
        }

        $cuantos = count($datadetalles);
        $cont=2;  
        $j = 0;
        for ($i = 0, $l = sizeof($datadetalles); $i < $l; $i++) { // row $i
          $j = 0;
          foreach ($datadetalles[$i] as $k => $v) { // column $j
              $sheet->setCellValueByColumnAndRow($j + 1, ($i + 1 + 1), $v);
              $j++;
          }
        }
    
  $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Paid');
  $drawing->setDescription('Paid');
  $drawing->setPath($codQr); /* put your path and image here */
  $drawing->setCoordinates('FF65000');
  $drawing->setOffsetX(110);
  $drawing->setRotation(0);
  $drawing->getShadow()->setVisible(true);
  $drawing->getShadow()->setDirection(45);
  $drawing->setWorksheet($spreadsheet->getActiveSheet());

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment; filename="'. urlencode($nonmarchivo).'"');
  $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
  $objWriter->save('../public/dowload/'.$nonmarchivo.'.xlsx');

  $idusers = $this->security->getUser()->getId();
    $entityManager = $em;
    $entityManagerDefault = $this->getEntityManager();
    $opcioTokenPdf = new TokenPdf();
    $opcioTokenPdf->setIdUser($idusers);
    $opcioTokenPdf->setDatosQr($nonmarchivo);
    $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
    $opcioTokenPdf->setCreateAt(new \DateTime());
    $entityManager->persist($opcioTokenPdf);
    $entityManager->flush(); 

  $urles="../public/dowload/".$nonmarchivo.".xlsx";
  $imgbinary = fread(fopen($urles, "r"), filesize($urles));
  $filetype = "Xlsx";
  $base64_arch = 'data:@file/vnd.openxmlformats-officedocument.spreadsheetml/'. $filetype .';base64,' . base64_encode($imgbinary);  
  return $dataDatosArchivo = array($base64_arch,$nonmarchivo,$filetype);




}


/**
* Create Reportes Detalles Estudios Academicos .
*/
public function postDetallesEstudiosAcademicos($validator,$helper,$em,$logoqr,$headers,$nonmarchivo,$codQr){
  $entityManagerDefault = $this->getEntityManager();
  $datadetalles=[];

  $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();
  for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
      $sheet->setCellValueByColumnAndRow($i + 1, 1, $headers[$i]);
  }
   
 $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
  if($empresa)
     $idemp = $empresa->getId();
     $sql = "SELECT d.`id`,d.`cedula`,d.`fecha_ingreso`,d.`familiar_empresa`,d.`autorizacion_ingreso`,d.`idempresa`
     FROM `datos_personales` d where  idempresa =".$idemp." ";   

  $conn = $em->getConnection();
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result= $stmt->fetchAll();
  $conrg=1;
  //$result= $stmt->executeQuery();
  foreach($result as $claveResult=>$valorResult){

    $idprofesion=0;

    $fech_ingreso1 = strtotime($valorResult["fecha_ingreso"]);
    $fech_ingreso = date('Y-m-d', $fech_ingreso1);
      
      $currentUser =$entityManagerDefault->getRepository(User::class)->findBy([
          'numeroDocumento' => $valorResult["cedula"]
      ]);

      if (count($currentUser)>0) {                 
          $cedula = $currentUser[0]->getNumeroDocumento();
          $nombres = $currentUser[0]->getPrimerNombre().' '.$currentUser[0]->getSegundoNombre();
          $epellidos = $currentUser[0]->getPrimerApellido().' '.$currentUser[0]->getSegundoApellido();
          if(!is_null($currentUser[0]->getFechaNacimiento())) {
            $fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          }else{
            $fech_nacimiento = null;
          }
          //$fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          $sexo = $currentUser[0]->getSexo();
      }
      

      $dataDatosAcademico=[];
      $query = $em->createQueryBuilder();
      $allAppointmentsQuery = $query->select('estudios_academicos.id,estudios_academicos.iddatos_personales,
      estudios_academicos.idprofesion,estudios_academicos.fecha_graduado')
      ->from(EstudiosAcademicos::class,'estudios_academicos') 
      ->Where('estudios_academicos.iddatos_personales='.$valorResult["id"])
      //->addOrderBy('id', 'ASC')
      ->getQuery();
      $queryult = $query->getQuery();
      $data =  $queryult->execute();
      foreach($data as $clave=>$valor){
        $queryresp = $em->createQueryBuilder();
        $allAppointmentsQuery = $queryresp->select('profesion.id,profesion.descprofesion')
        ->from(Profesion::class,'profesion')
        ->Where('profesion.id='.$valor["idprofesion"])
        ->getQuery();
        $queryrespdata = $queryresp->getQuery();
        $dataresp =  $queryrespdata->execute();
        foreach($dataresp as $clave=>$valorresp){
          $idresp = $valorresp["id"];
          $resp = $valorresp["descprofesion"];
           $dataDatosAcademico[] = array('0' => $resp,'1' => $valor["fecha_graduado"]->format("Y-m-d"));                            
         }
        }
               
      $Familiarempresa= $this->Respuestas($valorResult["familiar_empresa"],$em);
      $Autorizacioningreso= $this->Respuestas($valorResult["autorizacion_ingreso"],$em);
      
      $datadetalles[] = array('0' => $conrg,'1' => $cedula,'2' => $nombres,'3' => $epellidos,'4' => $fech_nacimiento
      ,'5' => $fech_ingreso,'6' => $Familiarempresa,'7' => $Autorizacioningreso,'8' => $dataDatosAcademico);
      $conrg++;
  }

  $cuantos = count($datadetalles);
  $cont=2;  
  $contl=2;
  $contsalid=0;
  $j = 0;
  for ($i = 0, $l = sizeof($datadetalles); $i < $l; $i++) { // row $i
    $j = 0;
    foreach ($datadetalles[$i] as $k => $v) { // column $j
      if($j ==8){
        $jm = $j;
        
        if(count($v) ==0){
          $contl++;    
        }
     for ($ie = 0, $le = sizeof($v); $ie < $le; $ie++) { // row $i
        $jm = $j;
        foreach ($v[$ie] as $p => $w) { // column $j
          $sheet->setCellValueByColumnAndRow($jm + 1, $contl, $w);
          $jm++;
        }
        $contl++;    
     }
      }else{
        $sheet->setCellValueByColumnAndRow($j + 1, $contl, $v);
      }
        $j++;
    }
    
  }
  
  $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Paid');
    $drawing->setDescription('Paid');
    $drawing->setPath($codQr); /* put your path and image here */
    $drawing->setCoordinates('FF65000');
    $drawing->setOffsetX(110);
    $drawing->setRotation(0);
    $drawing->getShadow()->setVisible(true);
    $drawing->getShadow()->setDirection(45);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment; filename="'. urlencode($nonmarchivo).'"');
  $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
  $objWriter->save('../public/dowload/'.$nonmarchivo.'.xlsx');

  $idusers = $this->security->getUser()->getId();
    $entityManager = $em;
    $entityManagerDefault = $this->getEntityManager();
    $opcioTokenPdf = new TokenPdf();
    $opcioTokenPdf->setIdUser($idusers);
    $opcioTokenPdf->setDatosQr($nonmarchivo);
    $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
    $opcioTokenPdf->setCreateAt(new \DateTime());
    $entityManager->persist($opcioTokenPdf);
    $entityManager->flush(); 

  $urles="../public/dowload/".$nonmarchivo.".xlsx";
  $imgbinary = fread(fopen($urles, "r"), filesize($urles));
  $filetype = "Xlsx";
  $base64_arch = 'data:@file/vnd.openxmlformats-officedocument.spreadsheetml/'. $filetype .';base64,' . base64_encode($imgbinary);  
    return $dataDatosArchivo = array($base64_arch,$nonmarchivo,$filetype);


}

/**
* Create Reportes Detalles Especialidades Areas.
*/
public function postDetallesEspecialidadesArea($validator,$helper,$em,$logoqr,$headers,$nonmarchivo,$codQr){
  $entityManagerDefault = $this->getEntityManager();
  $datadetalles=[];

  $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();
  for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
      $sheet->setCellValueByColumnAndRow($i + 1, 1, $headers[$i]);
  }
   
// $idemp = $this->security->getUser()->getIdempresa();

 
  $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
  if($empresa)
     $idemp = $empresa->getId();
     $sql = "SELECT d.`id`,d.`cedula`,d.`fecha_ingreso`,d.`familiar_empresa`,d.`autorizacion_ingreso`,d.`idempresa`
     FROM `datos_personales` d where  idempresa =".$idemp." ";   

  $conn = $em->getConnection();
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result= $stmt->fetchAll();
  $conrg=1;
  //$result= $stmt->executeQuery();
  foreach($result as $claveResult=>$valorResult){

    $idprofesion=0;

    $fech_ingreso1 = strtotime($valorResult["fecha_ingreso"]);
    $fech_ingreso = date('Y-m-d', $fech_ingreso1);
      
      $currentUser =$entityManagerDefault->getRepository(User::class)->findBy([
          'numeroDocumento' => $valorResult["cedula"]
      ]);

      if (count($currentUser)>0) {                 
          $cedula = $currentUser[0]->getNumeroDocumento();
          $nombres = $currentUser[0]->getPrimerNombre().' '.$currentUser[0]->getSegundoNombre();
          $epellidos = $currentUser[0]->getPrimerApellido().' '.$currentUser[0]->getSegundoApellido();
          if(!is_null($currentUser[0]->getFechaNacimiento())) {
            $fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          }else{
            $fech_nacimiento = null;
          }
          //$fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          $sexo = $currentUser[0]->getSexo();
      }
      

      $dataDatosArea=[];
      $query = $em->createQueryBuilder();
      $allAppointmentsQuery = $query->select('especialidades_areas.id,especialidades_areas.id_personales,
      especialidades_areas.id_area')
      ->from(EspecialidadesAreas::class,'especialidades_areas') 
      ->Where('especialidades_areas.id_personales='.$valorResult["id"])
      //->addOrderBy('id', 'ASC')
      ->getQuery();
      $queryult = $query->getQuery();
      $data =  $queryult->execute();
      foreach($data as $clave=>$valor){

        $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('area.id,area.descarea,area.iddepartamentos')
          ->from(Area::class,'area')
          ->Where('area.id='.$valor["id_area"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $resp = $valorresp["descarea"];
            $dataDatosArea[] = array('0' => $resp);                            
           }

               
      $Familiarempresa= $this->Respuestas($valorResult["familiar_empresa"],$em);
      $Autorizacioningreso= $this->Respuestas($valorResult["autorizacion_ingreso"],$em);
      
      $datadetalles[] = array('0' => $conrg,'1' => $cedula,'2' => $nombres,'3' => $epellidos,'4' => $fech_nacimiento
      ,'5' => $fech_ingreso,'6' => $Familiarempresa,'7' => $Autorizacioningreso,'8' => $dataDatosArea);
      $conrg++;

  }

  $cuantos = count($datadetalles);
  $cont=2;  
  $contl=2;
  $contsalid=0;
  $j = 0;
  for ($i = 0, $l = sizeof($datadetalles); $i < $l; $i++) { // row $i
    $j = 0;
    foreach ($datadetalles[$i] as $k => $v) { // column $j
      if($j ==8){
        $jm = $j;
        
        if(count($v) ==0){
          $contl++;    
        }
     for ($ie = 0, $le = sizeof($v); $ie < $le; $ie++) { // row $i
        $jm = $j;
        foreach ($v[$ie] as $p => $w) { // column $j
          $sheet->setCellValueByColumnAndRow($jm + 1, $contl, $w);
          $jm++;
        }
        $contl++;    
     }
      }else{
        $sheet->setCellValueByColumnAndRow($j + 1, $contl, $v);
      }
        $j++;
    }
  }

}
//fin de registros

//$sheet->setCellValueByColumnAndRow(0, 110, $codQr);
$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Paid');
$drawing->setDescription('Paid');
$drawing->setPath($codQr); /* put your path and image here */
$drawing->setCoordinates('FF65000');
$drawing->setOffsetX(110);
$drawing->setRotation(0);
$drawing->getShadow()->setVisible(true);
$drawing->getShadow()->setDirection(45);
$drawing->setWorksheet($spreadsheet->getActiveSheet());


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($nonmarchivo).'"');
$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$objWriter->save('../public/dowload/'.$nonmarchivo.'.xlsx');

$idusers = $this->security->getUser()->getId();
$entityManager = $em;
$entityManagerDefault = $this->getEntityManager();
$opcioTokenPdf = new TokenPdf();
$opcioTokenPdf->setIdUser($idusers);
$opcioTokenPdf->setDatosQr($nonmarchivo);
$currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
$opcioTokenPdf->setCreateAt(new \DateTime());
$entityManager->persist($opcioTokenPdf);
$entityManager->flush(); 

$urles="../public/dowload/".$nonmarchivo.".xlsx";
$imgbinary = fread(fopen($urles, "r"), filesize($urles));
$filetype = "Xlsx";
$base64_arch = 'data:@file/vnd.openxmlformats-officedocument.spreadsheetml/'. $filetype .';base64,' . base64_encode($imgbinary);  
return $dataDatosArchivo = array($base64_arch,$nonmarchivo,$filetype);


}


/**
* Create Reportes Detalles Movimientos Transferencias.
*/
public function postDetallesMovimientosTransferencias($validator,$helper,$em,$logoqr,$headers,$nonmarchivo,$codQr){
  $entityManagerDefault = $this->getEntityManager();
  $datadetalles=[];

  $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();
  for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
      $sheet->setCellValueByColumnAndRow($i + 1, 1, $headers[$i]);
  }
   
  $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
  if($empresa)
     $idemp = $empresa->getId();
     $sql = "SELECT d.`id`,d.`cedula`,d.`fecha_ingreso`,d.`familiar_empresa`,d.`autorizacion_ingreso`,d.`idempresa`
     FROM `datos_personales` d where  idempresa =".$idemp." ";   

$conn = $em->getConnection();
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result= $stmt->fetchAll();
  $conrg=1;
  //$result= $stmt->executeQuery();
  foreach($result as $claveResult=>$valorResult){

    $idprofesion=0;

    $fech_ingreso1 = strtotime($valorResult["fecha_ingreso"]);
    $fech_ingreso = date('Y-m-d', $fech_ingreso1);
      
      $currentUser =$entityManagerDefault->getRepository(User::class)->findBy([
          'numeroDocumento' => $valorResult["cedula"]
      ]);

      if (count($currentUser)>0) {                 
          $cedula = $currentUser[0]->getNumeroDocumento();
          $nombres = $currentUser[0]->getPrimerNombre().' '.$currentUser[0]->getSegundoNombre();
          $epellidos = $currentUser[0]->getPrimerApellido().' '.$currentUser[0]->getSegundoApellido();
          if(!is_null($currentUser[0]->getFechaNacimiento())) {
            $fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          }else{
            $fech_nacimiento = null;
          }
          //$fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          $sexo = $currentUser[0]->getSexo();
      }
      

      $dataDatosTransferencias=[];
      $query = $em->createQueryBuilder();
      $allAppointmentsQuery = $query->select('hist_mov_transferencias.id,hist_mov_transferencias.id_datos_personales,
      hist_mov_transferencias.id_departamento,hist_mov_transferencias.id_area,hist_mov_transferencias.id_region,
      hist_mov_transferencias.fecha_transferencia')
      ->from(HistMovTransferencias::class,'hist_mov_transferencias') 
      ->Where('hist_mov_transferencias.id_datos_personales='.$valorResult["id"])
      //->addOrderBy('id', 'ASC')
      ->getQuery();
      $queryult = $query->getQuery();
      $data =  $queryult->execute();
      foreach($data as $clave=>$valor){

        $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('departamento.id,departamento.descdepartamento')
          ->from(Departamento::class,'departamento')
          ->Where('departamento.id='.$valor["id_departamento"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $respDepartamentos = $valorresp["descdepartamento"];
           }

           $queryresp = $em->createQueryBuilder();
           $allAppointmentsQuery = $queryresp->select('region.id,region.descregion')
           ->from(Region::class,'region')
           ->Where('region.id='.$valor["id_region"])
           ->getQuery();
           $queryrespdata = $queryresp->getQuery();
           $dataresp =  $queryrespdata->execute();
           foreach($dataresp as $clave=>$valorresp){
             $idresp = $valorresp["id"];
             $respRegion = $valorresp["descregion"];
            }

          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('area.id,area.descarea,area.iddepartamentos')
          ->from(Area::class,'area')
          ->Where('area.id='.$valor["id_area"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $respArea = $valorresp["descarea"];
           }

      $dataDatosTransferencias[] = array('0' => $valor["fecha_transferencia"]->format("Y-m-d"),'1' => $respRegion,'2' => $respDepartamentos,'3' => $respArea);                            

               
      $Familiarempresa= $this->Respuestas($valorResult["familiar_empresa"],$em);
      $Autorizacioningreso= $this->Respuestas($valorResult["autorizacion_ingreso"],$em);
      
      $datadetalles[] = array('0' => $conrg,'1' => $cedula,'2' => $nombres,'3' => $epellidos,'4' => $fech_nacimiento
      ,'5' => $fech_ingreso,'6' => $Familiarempresa,'7' => $Autorizacioningreso,'8' => $dataDatosTransferencias);

      $conrg++;

  }

  $cuantos = count($datadetalles);
  $cont=2;  
  $contl=2;
  $contsalid=0;
  $j = 0;
  for ($i = 0, $l = sizeof($datadetalles); $i < $l; $i++) { // row $i
    $j = 0;
    foreach ($datadetalles[$i] as $k => $v) { // column $j
      if($j ==8){
        $jm = $j;
        
        if(count($v) ==0){
          $contl++;    
        }
     for ($ie = 0, $le = sizeof($v); $ie < $le; $ie++) { // row $i
        $jm = $j;
        foreach ($v[$ie] as $p => $w) { // column $j
          $sheet->setCellValueByColumnAndRow($jm + 1, $contl, $w);
          $jm++;
        }
        $contl++;    
     }
      }else{
        $sheet->setCellValueByColumnAndRow($j + 1, $contl, $v);
      }
        $j++;
    }
  }

}
//fin de registros

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Paid');
$drawing->setDescription('Paid');
$drawing->setPath($codQr); /* put your path and image here */
$drawing->setCoordinates('FF65000');
$drawing->setOffsetX(110);
$drawing->setRotation(0);
$drawing->getShadow()->setVisible(true);
$drawing->getShadow()->setDirection(45);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($nonmarchivo).'"');
$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$objWriter->save('../public/dowload/'.$nonmarchivo.'.xlsx');

$idusers = $this->security->getUser()->getId();
$entityManager = $em;
$entityManagerDefault = $this->getEntityManager();
$opcioTokenPdf = new TokenPdf();
$opcioTokenPdf->setIdUser($idusers);
$opcioTokenPdf->setDatosQr($nonmarchivo);
$currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
$opcioTokenPdf->setCreateAt(new \DateTime());
$entityManager->persist($opcioTokenPdf);
$entityManager->flush(); 

$urles="../public/dowload/".$nonmarchivo.".xlsx";
$imgbinary = fread(fopen($urles, "r"), filesize($urles));
$filetype = "Xlsx";
$base64_arch = 'data:@file/vnd.openxmlformats-officedocument.spreadsheetml/'. $filetype .';base64,' . base64_encode($imgbinary);  
return $dataDatosArchivo = array($base64_arch,$nonmarchivo,$filetype);


}


/**
* Create Reportes Detalles Promocion.
*/
public function postDetallesPromocion($validator,$helper,$em,$logoqr,$headers,$nonmarchivo,$codQr){
  $entityManagerDefault = $this->getEntityManager();
  $datadetalles=[];

  $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();
  for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
      $sheet->setCellValueByColumnAndRow($i + 1, 1, $headers[$i]);
  }
   
  $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
  if($empresa)
     $idemp = $empresa->getId();
     $sql = "SELECT d.`id`,d.`cedula`,d.`fecha_ingreso`,d.`familiar_empresa`,d.`autorizacion_ingreso`,d.`idempresa`
     FROM `datos_personales` d where  idempresa =".$idemp." ";   

  $conn = $em->getConnection();
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result= $stmt->fetchAll();
  $conrg=1;
  //$result= $stmt->executeQuery();
  foreach($result as $claveResult=>$valorResult){

    $idprofesion=0;

    $fech_ingreso1 = strtotime($valorResult["fecha_ingreso"]);
    $fech_ingreso = date('Y-m-d', $fech_ingreso1);
      
      $currentUser =$entityManagerDefault->getRepository(User::class)->findBy([
          'numeroDocumento' => $valorResult["cedula"]
      ]);

      if (count($currentUser)>0) {                 
          $cedula = $currentUser[0]->getNumeroDocumento();
          $nombres = $currentUser[0]->getPrimerNombre().' '.$currentUser[0]->getSegundoNombre();
          $epellidos = $currentUser[0]->getPrimerApellido().' '.$currentUser[0]->getSegundoApellido();
          if(!is_null($currentUser[0]->getFechaNacimiento())) {
            $fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          }else{
            $fech_nacimiento = null;
          }
          //$fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          $sexo = $currentUser[0]->getSexo();
      }
      

      $dataDatosAcademico=[];
      $query = $em->createQueryBuilder();
      $allAppointmentsQuery = $query->select('hist_mov_promocion.id,hist_mov_promocion.id_datos_personales,
      hist_mov_promocion.id_cargo,hist_mov_promocion.fecha_promocion')
      ->from(HistMovPromocion::class,'hist_mov_promocion') 
      ->Where('hist_mov_promocion.id_datos_personales='.$valorResult["id"])
      //->addOrderBy('id', 'ASC')
      ->getQuery();
      $queryult = $query->getQuery();
      $data =  $queryult->execute();
      foreach($data as $clave=>$valor){
        $queryresp = $entityManagerDefault->createQueryBuilder();
        $allAppointmentsQuery = $queryresp->select('cargo.id,cargo.descripcion')
        ->from(Cargo::class,'cargo')
        ->Where('cargo.id='.$valor["id_cargo"])
        ->getQuery();
        $queryrespdata = $queryresp->getQuery();
        $dataresp =  $queryrespdata->execute();
        foreach($dataresp as $clave=>$valorresp){
          $idresp = $valorresp["id"];
          $respCargo = $valorresp["descripcion"];
         }

        $dataDatosAcademico[] = array('0' => $respCargo,'1' => $valor["fecha_promocion"]->format("Y-m-d")); 
    } 

               
      $Familiarempresa= $this->Respuestas($valorResult["familiar_empresa"],$em);
      $Autorizacioningreso= $this->Respuestas($valorResult["autorizacion_ingreso"],$em);

      $datadetalles[] = array('0' => $conrg,'1' => $cedula,'2' => $nombres,'3' => $epellidos,'4' => $fech_nacimiento
      ,'5' => $fech_ingreso,'6' => $Familiarempresa,'7' => $Autorizacioningreso,'8' => $dataDatosAcademico);
      $conrg++;

  }

  $cuantos = count($datadetalles);
  $cont=2;  
  $contl=2;
  $contsalid=0;
  $j = 0;
  for ($i = 0, $l = sizeof($datadetalles); $i < $l; $i++) { // row $i
    $j = 0;
    foreach ($datadetalles[$i] as $k => $v) { // column $j
      if($j ==8){
        $jm = $j;
        
        if(count($v) ==0){
          $contl++;    
        }
     for ($ie = 0, $le = sizeof($v); $ie < $le; $ie++) { // row $i
        $jm = $j;
        foreach ($v[$ie] as $p => $w) { // column $j
          $sheet->setCellValueByColumnAndRow($jm + 1, $contl, $w);
          $jm++;
        }
        $contl++;    
     }
      }else{
        $sheet->setCellValueByColumnAndRow($j + 1, $contl, $v);
      }
        $j++;
    }
  }
 
  $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Paid');
    $drawing->setDescription('Paid');
    $drawing->setPath($codQr); /* put your path and image here */
    $drawing->setCoordinates('FF65000');
    $drawing->setOffsetX(110);
    $drawing->setRotation(0);
    $drawing->getShadow()->setVisible(true);
    $drawing->getShadow()->setDirection(45);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment; filename="'. urlencode($nonmarchivo).'"');
  $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
  $objWriter->save('../public/dowload/'.$nonmarchivo.'.xlsx');

  $idusers = $this->security->getUser()->getId();
    $entityManager = $em;
    $entityManagerDefault = $this->getEntityManager();
    $opcioTokenPdf = new TokenPdf();
    $opcioTokenPdf->setIdUser($idusers);
    $opcioTokenPdf->setDatosQr($nonmarchivo);
    $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
    $opcioTokenPdf->setCreateAt(new \DateTime());
    $entityManager->persist($opcioTokenPdf);
    $entityManager->flush(); 

  $urles="../public/dowload/".$nonmarchivo.".xlsx";
  $imgbinary = fread(fopen($urles, "r"), filesize($urles));
  $filetype = "Xlsx";
  $base64_arch = 'data:@file/vnd.openxmlformats-officedocument.spreadsheetml/'. $filetype .';base64,' . base64_encode($imgbinary);  
    return $dataDatosArchivo = array($base64_arch,$nonmarchivo,$filetype);


}

/**
* Create Reportes Detalles Vacaciones.
*/
public function postDetallesVacaciones($validator,$helper,$em,$logoqr,$headers,$nonmarchivo,$codQr){
  $entityManagerDefault = $this->getEntityManager();
  $datadetalles=[];

  $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();
  for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
      $sheet->setCellValueByColumnAndRow($i + 1, 1, $headers[$i]);
  }
   
  $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
  if($empresa)
     $idemp = $empresa->getId();
     $sql = "SELECT d.`id`,d.`cedula`,d.`fecha_ingreso`,d.`familiar_empresa`,d.`autorizacion_ingreso`,d.`idempresa`
     FROM `datos_personales` d where  idempresa =".$idemp." ";   

  $conn = $em->getConnection();
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result= $stmt->fetchAll();
  $conrg=1;
  //$result= $stmt->executeQuery();
  foreach($result as $claveResult=>$valorResult){

    $idprofesion=0;

    $fech_ingreso1 = strtotime($valorResult["fecha_ingreso"]);
    $fech_ingreso = date('Y-m-d', $fech_ingreso1);
      
      $currentUser =$entityManagerDefault->getRepository(User::class)->findBy([
          'numeroDocumento' => $valorResult["cedula"]
      ]);

      if (count($currentUser)>0) {                 
          $cedula = $currentUser[0]->getNumeroDocumento();
          $nombres = $currentUser[0]->getPrimerNombre().' '.$currentUser[0]->getSegundoNombre();
          $epellidos = $currentUser[0]->getPrimerApellido().' '.$currentUser[0]->getSegundoApellido();
          if(!is_null($currentUser[0]->getFechaNacimiento())) {
            $fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          }else{
            $fech_nacimiento = null;
          }
          //$fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          $sexo = $currentUser[0]->getSexo();
      }
      $dataDatosAcademico=[];

        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('vaciones.id,vaciones.id_datos_personales,
        vaciones.autorizacion_vacaciones,vaciones.id_tipo_vacaciones,vaciones.periodos_acumulados,vaciones.periodo_difrute,vaciones.fecha_desde,vaciones.fecha_hasta,vaciones.fecha_incorporacion')
        ->from(Vaciones::class,'vaciones') 
        ->Where('vaciones.id_datos_personales='.$valorResult["id"])
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $Tipo_vacaciones=$valor["id_tipo_vacaciones"];
          $Periodos_acumulados=$valor["periodos_acumulados"];
          $Periodo_difrute=$valor["periodo_difrute"];
          $Fecha_desde=$valor["fecha_desde"]->format("Y-m-d");
          $Fecha_hasta=$valor["fecha_hasta"]->format("Y-m-d");
          $Fecha_incorporacion=$valor["fecha_incorporacion"]->format("Y-m-d");
          $Autorizacion_vacaciones= $this->Respuestas($valor["autorizacion_vacaciones"],$em);
          $Tiempdifrute=$this->dias_pasados($Fecha_desde,$Fecha_hasta);
          
          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipo_vacaciones.id,tipo_vacaciones.vacaciones')
          ->from(TipoVacaciones::class,'tipo_vacaciones')
          ->Where('tipo_vacaciones.id='.$valor["id_tipo_vacaciones"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $resptipovacaciones = $valorresp["vacaciones"];
           }


           $dataDatosAcademico[] = array('0' => $resptipovacaciones,'1' => $Autorizacion_vacaciones
           ,'2' => $Fecha_desde,'3' => $Fecha_hasta,'4' => $Fecha_incorporacion
           ,'5' => $Periodos_acumulados,'6' => $Periodo_difrute,'7' => $Tiempdifrute
          ); 
      }


      
               
      $Familiarempresa= $this->Respuestas($valorResult["familiar_empresa"],$em);
      $Autorizacioningreso= $this->Respuestas($valorResult["autorizacion_ingreso"],$em);

      $datadetalles[] = array('0' => $conrg,'1' => $cedula,'2' => $nombres,'3' => $epellidos,'4' => $fech_nacimiento
      ,'5' => $fech_ingreso,'6' => $Familiarempresa,'7' => $Autorizacioningreso,'8' => $dataDatosAcademico);
      $conrg++;

  }

  $cuantos = count($datadetalles);
  $cont=2;  
  $contl=2;
  $contsalid=0;
  $j = 0;
  for ($i = 0, $l = sizeof($datadetalles); $i < $l; $i++) { // row $i
    $j = 0;
    foreach ($datadetalles[$i] as $k => $v) { // column $j
      if($j ==8){
        $jm = $j;
        
        if(count($v) ==0){
          $contl++;    
        }
     for ($ie = 0, $le = sizeof($v); $ie < $le; $ie++) { // row $i
        $jm = $j;
        foreach ($v[$ie] as $p => $w) { // column $j
          $sheet->setCellValueByColumnAndRow($jm + 1, $contl, $w);
          $jm++;
        }
        $contl++;    
     }
      }else{
        $sheet->setCellValueByColumnAndRow($j + 1, $contl, $v);
      }
        $j++;
    }
  }
  
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Paid');
    $drawing->setDescription('Paid');
    $drawing->setPath($codQr); /* put your path and image here */
    $drawing->setCoordinates('FF65000');
    $drawing->setOffsetX(110);
    $drawing->setRotation(0);
    $drawing->getShadow()->setVisible(true);
    $drawing->getShadow()->setDirection(45);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment; filename="'. urlencode($nonmarchivo).'"');
  $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
  $objWriter->save('../public/dowload/'.$nonmarchivo.'.xlsx');

  $idusers = $this->security->getUser()->getId();
    $entityManager = $em;
    $entityManagerDefault = $this->getEntityManager();
    $opcioTokenPdf = new TokenPdf();
    $opcioTokenPdf->setIdUser($idusers);
    $opcioTokenPdf->setDatosQr($nonmarchivo);
    $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
    $opcioTokenPdf->setCreateAt(new \DateTime());
    $entityManager->persist($opcioTokenPdf);
    $entityManager->flush(); 

  $urles="../public/dowload/".$nonmarchivo.".xlsx";
  $imgbinary = fread(fopen($urles, "r"), filesize($urles));
  $filetype = "Xlsx";
  $base64_arch = 'data:@file/vnd.openxmlformats-officedocument.spreadsheetml/'. $filetype .';base64,' . base64_encode($imgbinary);  
    return $dataDatosArchivo = array($base64_arch,$nonmarchivo,$filetype);


}


/**
* Create Reportes Detalles Permisos.
*/
public function postDetallesPermisos($validator,$helper,$em,$logoqr,$headers,$nonmarchivo,$codQr){
  $entityManagerDefault = $this->getEntityManager();
  $datadetalles=[];

  $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();
  for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
      $sheet->setCellValueByColumnAndRow($i + 1, 1, $headers[$i]);
  }
   
  $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
  if($empresa)
     $idemp = $empresa->getId();
     $sql = "SELECT d.`id`,d.`cedula`,d.`fecha_ingreso`,d.`familiar_empresa`,d.`autorizacion_ingreso`,d.`idempresa`
     FROM `datos_personales` d where  idempresa =".$idemp." ";   

  $conn = $em->getConnection();
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result= $stmt->fetchAll();
  $conrg=1;
  //$result= $stmt->executeQuery();
  foreach($result as $claveResult=>$valorResult){

    $idprofesion=0;

    $fech_ingreso1 = strtotime($valorResult["fecha_ingreso"]);
    $fech_ingreso = date('Y-m-d', $fech_ingreso1);
      
      $currentUser =$entityManagerDefault->getRepository(User::class)->findBy([
          'numeroDocumento' => $valorResult["cedula"]
      ]);

      if (count($currentUser)>0) {                 
          $cedula = $currentUser[0]->getNumeroDocumento();
          $nombres = $currentUser[0]->getPrimerNombre().' '.$currentUser[0]->getSegundoNombre();
          $epellidos = $currentUser[0]->getPrimerApellido().' '.$currentUser[0]->getSegundoApellido();
          if(!is_null($currentUser[0]->getFechaNacimiento())) {
            $fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          }else{
            $fech_nacimiento = null;
          }
          //$fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          $sexo = $currentUser[0]->getSexo();
      }
      $dataDatosAcademico=[];

      $query = $em->createQueryBuilder();
      $allAppointmentsQuery = $query->select('permisos.id,permisos.id_datos_personales,
      permisos.id_motivo_permiso,permisos.autorizacion_permisos,permisos.fecha_desde,permisos.fecha_hasta')
      ->from(Permisos::class,'permisos') 
      ->Where('permisos.id_datos_personales='.$valorResult["id"])
      //->addOrderBy('id', 'ASC')
      ->getQuery();
      $queryult = $query->getQuery();
      $data =  $queryult->execute();
      foreach($data as $clave=>$valor){
        $Fecha_desde=$valor["fecha_desde"]->format("Y-m-d");
        $Fecha_hasta=$valor["fecha_hasta"]->format("Y-m-d");
        $Autorizacion_permisos= $this->Respuestas($valor["autorizacion_permisos"],$em);
        $Tiempdifrute=$this->dias_pasados($Fecha_desde,$Fecha_hasta);

        $queryresp = $em->createQueryBuilder();
        $allAppointmentsQuery = $queryresp->select('tipo_motivo_permiso.id,tipo_motivo_permiso.permiso')
        ->from(TipoMotivoPermiso::class,'tipo_motivo_permiso')
        ->Where('tipo_motivo_permiso.id='.$valor["id_motivo_permiso"])
        ->getQuery();
        $queryrespdata = $queryresp->getQuery();
        $dataresp =  $queryrespdata->execute();
        foreach($dataresp as $clave=>$valorresp){
          $idresp = $valorresp["id"];
          $respmotivopermiso = $valorresp["permiso"];
         }

          
         $dataDatosAcademico[] = array('0' => $respmotivopermiso,'1' => $Autorizacion_permisos
         ,'2' => $Fecha_desde,'3' => $Fecha_hasta,'4' => $Tiempdifrute
        ); 
        
    }
               
      $Familiarempresa= $this->Respuestas($valorResult["familiar_empresa"],$em);
      $Autorizacioningreso= $this->Respuestas($valorResult["autorizacion_ingreso"],$em);

      $datadetalles[] = array('0' => $conrg,'1' => $cedula,'2' => $nombres,'3' => $epellidos,'4' => $fech_nacimiento
      ,'5' => $fech_ingreso,'6' => $Familiarempresa,'7' => $Autorizacioningreso,'8' => $dataDatosAcademico);
      $conrg++;
 }

  $cuantos = count($datadetalles);
  $cont=2;  
  $contl=2;
  $contsalid=0;
  $j = 0;
  for ($i = 0, $l = sizeof($datadetalles); $i < $l; $i++) { // row $i
    $j = 0;
    foreach ($datadetalles[$i] as $k => $v) { // column $j
      if($j ==8){
        $jm = $j;
        
        if(count($v) ==0){
          $contl++;    
        }
     for ($ie = 0, $le = sizeof($v); $ie < $le; $ie++) { // row $i
        $jm = $j;
        foreach ($v[$ie] as $p => $w) { // column $j
          $sheet->setCellValueByColumnAndRow($jm + 1, $contl, $w);
          $jm++;
        }
        $contl++;    
     }
      }else{
        $sheet->setCellValueByColumnAndRow($j + 1, $contl, $v);
      }
        $j++;
    }
  }
  
  $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Paid');
    $drawing->setDescription('Paid');
    $drawing->setPath($codQr); /* put your path and image here */
    $drawing->setCoordinates('FF65000');
    $drawing->setOffsetX(110);
    $drawing->setRotation(0);
    $drawing->getShadow()->setVisible(true);
    $drawing->getShadow()->setDirection(45);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment; filename="'. urlencode($nonmarchivo).'"');
  $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
  $objWriter->save('../public/dowload/'.$nonmarchivo.'.xlsx');

  $idusers = $this->security->getUser()->getId();
    $entityManager = $em;
    $entityManagerDefault = $this->getEntityManager();
    $opcioTokenPdf = new TokenPdf();
    $opcioTokenPdf->setIdUser($idusers);
    $opcioTokenPdf->setDatosQr($nonmarchivo);
    $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
    $opcioTokenPdf->setCreateAt(new \DateTime());
    $entityManager->persist($opcioTokenPdf);
    $entityManager->flush(); 

  $urles="../public/dowload/".$nonmarchivo.".xlsx";
  $imgbinary = fread(fopen($urles, "r"), filesize($urles));
  $filetype = "Xlsx";
  $base64_arch = 'data:@file/vnd.openxmlformats-officedocument.spreadsheetml/'. $filetype .';base64,' . base64_encode($imgbinary);  
    return $dataDatosArchivo = array($base64_arch,$nonmarchivo,$filetype);


}


/**
* Create Reportes Detalles Reposos.
*/
public function postDetallesReposos($validator,$helper,$em,$logoqr,$headers,$nonmarchivo,$codQr){
  $entityManagerDefault = $this->getEntityManager();
  $datadetalles=[];

  $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();
  for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
      $sheet->setCellValueByColumnAndRow($i + 1, 1, $headers[$i]);
  }
   
  $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
  if($empresa)
     $idemp = $empresa->getId();
     $sql = "SELECT d.`id`,d.`cedula`,d.`fecha_ingreso`,d.`familiar_empresa`,d.`autorizacion_ingreso`,d.`idempresa`
     FROM `datos_personales` d where  idempresa =".$idemp." ";   

  $conn = $em->getConnection();
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result= $stmt->fetchAll();
  $conrg=1;
  //$result= $stmt->executeQuery();
  foreach($result as $claveResult=>$valorResult){

    $idprofesion=0;

    $fech_ingreso1 = strtotime($valorResult["fecha_ingreso"]);
    $fech_ingreso = date('Y-m-d', $fech_ingreso1);
      
      $currentUser =$entityManagerDefault->getRepository(User::class)->findBy([
          'numeroDocumento' => $valorResult["cedula"]
      ]);

      if (count($currentUser)>0) {                 
          $cedula = $currentUser[0]->getNumeroDocumento();
          $nombres = $currentUser[0]->getPrimerNombre().' '.$currentUser[0]->getSegundoNombre();
          $epellidos = $currentUser[0]->getPrimerApellido().' '.$currentUser[0]->getSegundoApellido();
          if(!is_null($currentUser[0]->getFechaNacimiento())) {
            $fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          }else{
            $fech_nacimiento = null;
          }
          //$fech_nacimiento = $currentUser[0]->getFechaNacimiento()->format("Y-m-d");
          $sexo = $currentUser[0]->getSexo();
      }
      $dataDatosAcademico=[];


      $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('reposo.id,reposo.id_datos_personales,
        reposo.id_tiporeposo,reposo.id_motivo_reposo,reposo.fecha_desde,reposo.fecha_hasta')
        ->from(Reposo::class,'reposo') 
        ->Where('reposo.id_datos_personales='.$valorResult["id"])
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $Fecha_desde=$valor["fecha_desde"]->format("Y-m-d");
          $Fecha_hasta=$valor["fecha_hasta"]->format("Y-m-d");
          $Tiempdifrute=$this->dias_pasados($Fecha_desde,$Fecha_hasta);

          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipo_reposo.id,tipo_reposo.tiporeposo')
          ->from(TipoReposo::class,'tipo_reposo')
          ->Where('tipo_reposo.id='.$valor["id_tiporeposo"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $resptiporeposos = $valorresp["tiporeposo"];
           }

            $queryresp = $em->createQueryBuilder();
            $allAppointmentsQuery = $queryresp->select('tipo_motivo_reposo.id,tipo_motivo_reposo.motivo')
            ->from(TipoMotivoReposo::class,'tipo_motivo_reposo')
            ->Where('tipo_motivo_reposo.id='.$valor["id_motivo_reposo"])
            ->getQuery();
            $queryrespdata = $queryresp->getQuery();
            $dataresp =  $queryrespdata->execute();
            foreach($dataresp as $clave=>$valorresp){
              $idresp = $valorresp["id"];
              $respmotivoreposos = $valorresp["motivo"];
             }

             $dataDatosAcademico[] = array('0' => $resptiporeposos,'1' => $respmotivoreposos
             ,'2' => $Fecha_desde,'3' => $Fecha_hasta,'4' => $Tiempdifrute
            ); 
      }
               
      $Familiarempresa= $this->Respuestas($valorResult["familiar_empresa"],$em);
      $Autorizacioningreso= $this->Respuestas($valorResult["autorizacion_ingreso"],$em);

      $datadetalles[] = array('0' => $conrg,'1' => $cedula,'2' => $nombres,'3' => $epellidos,'4' => $fech_nacimiento
      ,'5' => $fech_ingreso,'6' => $Familiarempresa,'7' => $Autorizacioningreso,'8' => $dataDatosAcademico);
      $conrg++;

  }

  $cuantos = count($datadetalles);
  $cont=2;  
  $contl=2;
  $contsalid=0;
  $j = 0;
  for ($i = 0, $l = sizeof($datadetalles); $i < $l; $i++) { // row $i
    $j = 0;
    foreach ($datadetalles[$i] as $k => $v) { // column $j
      if($j ==8){
        $jm = $j;
        
        if(count($v) ==0){
          $contl++;    
        }
     for ($ie = 0, $le = sizeof($v); $ie < $le; $ie++) { // row $i
        $jm = $j;
        foreach ($v[$ie] as $p => $w) { // column $j
          $sheet->setCellValueByColumnAndRow($jm + 1, $contl, $w);
          $jm++;
        }
        $contl++;    
     }
      }else{
        $sheet->setCellValueByColumnAndRow($j + 1, $contl, $v);
      }
        $j++;
    }
  }
  
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing(); 
    $drawing->setName('Paid');
    $drawing->setDescription('Paid');
    $drawing->setPath($codQr); /* put your path and image here */
    $drawing->setCoordinates('FF65000');
    $drawing->setOffsetX(110);
    $drawing->setRotation(0);
    $drawing->getShadow()->setVisible(true);
    $drawing->getShadow()->setDirection(45);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment; filename="'. urlencode($nonmarchivo).'"');
  $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
  $objWriter->save('../public/dowload/'.$nonmarchivo.'.xlsx');

    $idusers = $this->security->getUser()->getId();
    $entityManager = $em;
    $entityManagerDefault = $this->getEntityManager();
    $opcioTokenPdf = new TokenPdf();
    $opcioTokenPdf->setIdUser($idusers);
    $opcioTokenPdf->setDatosQr($nonmarchivo);
    $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
    $opcioTokenPdf->setCreateAt(new \DateTime());
    $entityManager->persist($opcioTokenPdf);
    $entityManager->flush(); 

  $urles="../public/dowload/".$nonmarchivo.".xlsx";
  $imgbinary = fread(fopen($urles, "r"), filesize($urles));
  $filetype = "Xlsx";
  $base64_arch = 'data:@file/vnd.openxmlformats-officedocument.spreadsheetml/'. $filetype .';base64,' . base64_encode($imgbinary);  
    return $dataDatosArchivo = array($base64_arch,$nonmarchivo,$filetype);


}

 
 
public function Respuestas($idresp,$em){
  
  $queryresp = $em->createQueryBuilder();
  $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
  ->from(TiposRespuestas::class,'tipos_respuestas')
  ->Where('tipos_respuestas.id='.$idresp)
  ->getQuery();
  $queryrespdata = $queryresp->getQuery();
  $dataresp =  $queryrespdata->execute();
  foreach($dataresp as $clave=>$valorresp){
    $idresptipresp = $valorresp["id"];
    $Resput  = $valorresp["respuestas"];
   }
   return $Resput;
  
}

function dias_pasados($fecha_inicial,$fecha_final)
{
$dias = (strtotime($fecha_inicial)-strtotime($fecha_final))/86400;
$dias = abs($dias); $dias = floor($dias);
$dias++;
return $dias;
}


    public function getValidExpByExp($expdientes,$em){
      $dataDatosPersonales=[];
      $query = $em->createQueryBuilder();
      $allAppointmentsQuery = $query->select('token_pdf.id,token_pdf.idUser,token_pdf.datosQr')
      ->from(TokenPdf::class,'token_pdf')
      ->where("token_pdf.datosQr ='".$expdientes."'")
      //->addOrderBy('id', 'ASC')
      ->getQuery();
      $queryult = $query->getQuery();
      $data =  $queryult->execute();
      if ($data) {
        return new JsonResponse(['msg'=>'Expediente valido = '.$expdientes],200);
      }else{
        return new JsonResponse(['msg'=>'No existen Registros'],409);  
      }   
    }

   /**
     * Reportes Carga Expedientes .
     */
    public function getReportesCargaUsuario($users,$fechadesde,$fechahasta,$validator,$helper,$em,$Fpdfreporte,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo){
      $entityManagerDefault = $this->getEntityManager();
      $fechad = date("Y-m-d", strtotime($fechadesde));
      $fechah = date("Y-m-d", strtotime($fechahasta));
      $sql = "SELECT datos_personales.id,datos_personales.cedula,datos_personales.fecha_ingreso
     ,datos_personales.autorizacion_ingreso,datos_personales.familiar_empresa,datos_personales.create_At,datos_personales.create_By FROM datos_personales WHERE datos_personales.create_By='".$users."' AND  DATE(datos_personales.create_At) between "." '".$fechad."' AND '".$fechah."'  ";
      $conn = $em->getConnection();
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $data= $stmt->fetchAll();
      $dataDatosPersonales=[];
      $cont=0;
    foreach($data as $clave=>$valor){
            $cont++;
          
          $datospersonalesDto=$valor["id"];
          $datosnumeroDocumento=$valor["cedula"];

          $entity =$entityManagerDefault->getRepository(User::class)->findBy([
            'numeroDocumento' => $valor["cedula"]
          ]);
          if (count($entity)>0) {                 
            $chequeado=true;
            $chequeo[]=array("message"=>"La cedula Existe con el usuario: ".$entity[0]->getPrimerNombre()." ".$entity[0]->getPrimerApellido()." ".$entity[0]->getId());
          }else{  
            $ee='';
          }    

          $respfamiliar_empresa='';
          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
          ->from(TiposRespuestas::class,'tipos_respuestas')
          ->Where('tipos_respuestas.id='.$valor["familiar_empresa"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $respfamiliar_empresa = $valorresp["respuestas"];
           }
          $respautorizacion_ingreso='';
          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
          ->from(TiposRespuestas::class,'tipos_respuestas')
          ->Where('tipos_respuestas.id='.$valor["autorizacion_ingreso"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $respautorizacion_ingreso = $valorresp["respuestas"];
           }

           
           if($entity[0]->getFechaNacimiento()!=null){           
            $userFechaNacimiento=$entity[0]->getFechaNacimiento()->format("Y-m-d");
           }else{
            $userFechaNacimiento='Null';
           }

           if($entity[0]->getPrimerNombre()!=null){           
            $userPrimerNombre=ucwords(strtolower(utf8_decode($entity[0]->getPrimerNombre())));
           }else{
            $userPrimerNombre='';
           }
           if($entity[0]->getSegundoNombre()!=null){           
            $userSegundoNombre=ucwords(strtolower(utf8_decode($entity[0]->getSegundoNombre())));
           }else{
            $userSegundoNombre='';
           }
           if($entity[0]->getPrimerApellido()!=null){           
            $userPrimerApellido=ucwords(strtolower(utf8_decode($entity[0]->getPrimerApellido())));
           }else{
            $userPrimerApellido='';
           }
           if($entity[0]->getSegundoApellido()!=null){           
            $userSegundoApellido=ucwords(strtolower(utf8_decode($entity[0]->getSegundoApellido())));
           }else{
            $userSegundoApellido='';
           }
          $dataDatosPersonales = array($cont,$valor["cedula"],$userPrimerNombre .' '.$userSegundoNombre,$userPrimerApellido .' '.$userSegundoApellido,$userFechaNacimiento,date("Y-m-d", strtotime($valor["fecha_ingreso"])),utf8_decode($respfamiliar_empresa),utf8_decode($respautorizacion_ingreso),date("Y-m-d", strtotime($valor["create_At"])));
          
          $brochureBroadcast = $Fpdfreporte->pushDataTablasRepotes($dataDatosPersonales,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);
        }

        $idusers = $this->security->getUser()->getId();
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
      if($empresa)
         $idemp = $empresa->getId();
        $opcioTokenPdf = new TokenPdf();
        $opcioTokenPdf->setIdUser($idusers);
        $opcioTokenPdf->setDatosQr($nonmarchivo);
        $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
        $opcioTokenPdf->setCreateAt(new \DateTime());
        $entityManager->persist($opcioTokenPdf);
        $entityManager->flush(); 

        return array("data"=>$data);

    }

     /**
     * Listar Uusarios de cargas de expedientes.
     */
    public function findList($em)
    {
        $dataUsuariocarga=[];
        $sql = "SELECT DISTINCT datos_personales.create_By FROM datos_personales";
         $conn = $em->getConnection();
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         $data= $stmt->fetchAll();
         $dataDatosPersonales=[];
         $cont=0;
         foreach($data as $clave=>$valor){
          $cont++;
          $dataUsuariocarga[] = array('id' => $cont,'userload' => $valor["create_By"]); 
         }

       return array("data"=>$dataUsuariocarga);
    }


    // /**
    //  * @return TokenPdf[] Returns an array of TokenPdf objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TokenPdf
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

