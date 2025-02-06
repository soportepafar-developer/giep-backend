<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\DocumentosIngresos;
use App\Entity\StaExped\TiposRespuestas;
use App\Entity\Cargo;
use App\Entity\Status;
use App\Entity\StaExped\Departamento;
use App\Entity\StaExped\Area;
use App\Entity\StaExped\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\StaExped\DatosPersonales;
Use App\Entity\Proyecto\Empresa;

Use App\Entity\User;
use App\Dto\StaExped\DocumentosIngresosOutPutDto;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method DocumentosIngresos|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentosIngresos|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentosIngresos[]    findAll()
 * @method DocumentosIngresos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentosIngresosRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, DocumentosIngresos::class);
    }


    public function getDocumentosIngresosByCi($id,$em){
        $entityManager = $this->getEntityManager();
        $dataDatosAcademico=[];
        $query = $em->createQueryBuilder();

        $allAppointmentsQuery = $query->select('documentos_ingresos.id,documentos_ingresos.id_datos_personales,
        documentos_ingresos.solicitud_empleo,documentos_ingresos.sintesis_curricular,documentos_ingresos.copia_cedula,documentos_ingresos.constancia_trabajo,
        documentos_ingresos.reg_informacion_fiscal,documentos_ingresos.verificacion_ref_laborales,documentos_ingresos.certificacion_declaracion_jurada,
        documentos_ingresos.licencia,documentos_ingresos.certificado_medico,documentos_ingresos.punto_cuenta,documentos_ingresos.poseer_titulo,
        documentos_ingresos.descripcion_cargo,documentos_ingresos.id_confidencialidad,documentos_ingresos.id_cargo,documentos_ingresos.id_departamento,
        documentos_ingresos.id_area,documentos_ingresos.id_region')
        ->from(DocumentosIngresos::class,'documentos_ingresos') 
        ->Where('documentos_ingresos.id_datos_personales='.$id)
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $permisosDto =new DocumentosIngresosOutPutDto();
          $permisosDto->id=$valor["id"];
          $permisosDto->idDatosPersonales=$valor["id_datos_personales"];
          $permisosDto->idDatossolicitud_empleo=$valor["solicitud_empleo"];
          $permisosDto->idDatossintesis_curricular=$valor["sintesis_curricular"];
          $permisosDto->idDatoscopia_cedula=$valor["copia_cedula"];
          $permisosDto->idDatosconstancia_trabajo=$valor["constancia_trabajo"];
          $permisosDto->idDatosreg_informacion_fiscal=$valor["reg_informacion_fiscal"];
          $permisosDto->idDatosverificacion_ref_laborales=$valor["verificacion_ref_laborales"];
          $permisosDto->idDatoscertificacion_declaracion_jurada=$valor["certificacion_declaracion_jurada"];
          $permisosDto->idDatoslicencia=$valor["licencia"];
          $permisosDto->idDatoscertificado_medico=$valor["certificado_medico"];
          $permisosDto->idDatospunto_cuenta=$valor["punto_cuenta"];
          $permisosDto->idDatosposeer_titulo=$valor["poseer_titulo"];
          $permisosDto->idDatosdescripcion_cargo=$valor["descripcion_cargo"];
          $permisosDto->idDatosid_confidencialidad=$valor["id_confidencialidad"];
          $permisosDto->idDatosid_cargo=$valor["id_cargo"];
          $permisosDto->idDatosid_departamento=$valor["id_departamento"];
          $permisosDto->idDatosid_area=$valor["id_area"];
          $permisosDto->idDatosid_region=$valor["id_region"];

           $queryresp = $em->createQueryBuilder();
           $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
           ->from(TiposRespuestas::class,'tipos_respuestas')
           ->Where('tipos_respuestas.id='.$valor["solicitud_empleo"])
           ->getQuery();
           $queryrespdata = $queryresp->getQuery();
           $dataresp =  $queryrespdata->execute();
           foreach($dataresp as $clave=>$valorresp){
             $idresp = $valorresp["id"];
             $resp = $valorresp["respuestas"];
              $permisosDto->Datossolicitud_empleo=array("id"=>$idresp,"nombre"=>$resp);                                      
            }

            $queryresp = $em->createQueryBuilder();
           $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
           ->from(TiposRespuestas::class,'tipos_respuestas')
           ->Where('tipos_respuestas.id='.$valor["sintesis_curricular"])
           ->getQuery();
           $queryrespdata = $queryresp->getQuery();
           $dataresp =  $queryrespdata->execute();
           foreach($dataresp as $clave=>$valorresp){
             $idresp = $valorresp["id"];
             $resp = $valorresp["respuestas"];
              $permisosDto->Datossintesis_curricular=array("id"=>$idresp,"nombre"=>$resp);                                      
            }

            
            $queryresp = $em->createQueryBuilder();
           $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
           ->from(TiposRespuestas::class,'tipos_respuestas')
           ->Where('tipos_respuestas.id='.$valor["copia_cedula"])
           ->getQuery();
           $queryrespdata = $queryresp->getQuery();
           $dataresp =  $queryrespdata->execute();
           foreach($dataresp as $clave=>$valorresp){
             $idresp = $valorresp["id"];
             $resp = $valorresp["respuestas"];
              $permisosDto->Datoscopia_cedula=array("id"=>$idresp,"nombre"=>$resp);                                      
            }

            $queryresp = $em->createQueryBuilder();
            $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
            ->from(TiposRespuestas::class,'tipos_respuestas')
            ->Where('tipos_respuestas.id='.$valor["constancia_trabajo"])
            ->getQuery();
            $queryrespdata = $queryresp->getQuery();
            $dataresp =  $queryrespdata->execute();
            foreach($dataresp as $clave=>$valorresp){
              $idresp = $valorresp["id"];
              $resp = $valorresp["respuestas"];
               $permisosDto->Datosconstancia_trabajo=array("id"=>$idresp,"nombre"=>$resp);                                      
             }

             $queryresp = $em->createQueryBuilder();
            $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
            ->from(TiposRespuestas::class,'tipos_respuestas')
            ->Where('tipos_respuestas.id='.$valor["reg_informacion_fiscal"])
            ->getQuery();
            $queryrespdata = $queryresp->getQuery();
            $dataresp =  $queryrespdata->execute();
            foreach($dataresp as $clave=>$valorresp){
              $idresp = $valorresp["id"];
              $resp = $valorresp["respuestas"];
               $permisosDto->Datosreg_informacion_fiscal=array("id"=>$idresp,"nombre"=>$resp);                                      
             }

             $queryresp = $em->createQueryBuilder();
             $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
             ->from(TiposRespuestas::class,'tipos_respuestas')
             ->Where('tipos_respuestas.id='.$valor["verificacion_ref_laborales"])
             ->getQuery();
             $queryrespdata = $queryresp->getQuery();
             $dataresp =  $queryrespdata->execute();
             foreach($dataresp as $clave=>$valorresp){
               $idresp = $valorresp["id"];
               $resp = $valorresp["respuestas"];
                $permisosDto->Datosverificacion_ref_laborales=array("id"=>$idresp,"nombre"=>$resp);                                      
              }

              $queryresp = $em->createQueryBuilder();
             $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
             ->from(TiposRespuestas::class,'tipos_respuestas')
             ->Where('tipos_respuestas.id='.$valor["certificacion_declaracion_jurada"])
             ->getQuery();
             $queryrespdata = $queryresp->getQuery();
             $dataresp =  $queryrespdata->execute();
             foreach($dataresp as $clave=>$valorresp){
               $idresp = $valorresp["id"];
               $resp = $valorresp["respuestas"];
                $permisosDto->Datoscertificacion_declaracion_jurada=array("id"=>$idresp,"nombre"=>$resp);                                      
              }

              $queryresp = $em->createQueryBuilder();
             $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
             ->from(TiposRespuestas::class,'tipos_respuestas')
             ->Where('tipos_respuestas.id='.$valor["licencia"])
             ->getQuery();
             $queryrespdata = $queryresp->getQuery();
             $dataresp =  $queryrespdata->execute();
             foreach($dataresp as $clave=>$valorresp){
               $idresp = $valorresp["id"];
               $resp = $valorresp["respuestas"];
                $permisosDto->Datoslicencia=array("id"=>$idresp,"nombre"=>$resp);                                      
              }

              $queryresp = $em->createQueryBuilder();
             $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
             ->from(TiposRespuestas::class,'tipos_respuestas')
             ->Where('tipos_respuestas.id='.$valor["certificado_medico"])
             ->getQuery();
             $queryrespdata = $queryresp->getQuery();
             $dataresp =  $queryrespdata->execute();
             foreach($dataresp as $clave=>$valorresp){
               $idresp = $valorresp["id"];
               $resp = $valorresp["respuestas"];
                $permisosDto->Datoscertificado_medico=array("id"=>$idresp,"nombre"=>$resp);                                      
              }

              $queryresp = $em->createQueryBuilder();
             $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
             ->from(TiposRespuestas::class,'tipos_respuestas')
             ->Where('tipos_respuestas.id='.$valor["punto_cuenta"])
             ->getQuery();
             $queryrespdata = $queryresp->getQuery();
             $dataresp =  $queryrespdata->execute();
             foreach($dataresp as $clave=>$valorresp){
               $idresp = $valorresp["id"];
               $resp = $valorresp["respuestas"];
                $permisosDto->Datospunto_cuenta=array("id"=>$idresp,"nombre"=>$resp);                                      
              }

              $queryresp = $em->createQueryBuilder();
             $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
             ->from(TiposRespuestas::class,'tipos_respuestas')
             ->Where('tipos_respuestas.id='.$valor["poseer_titulo"])
             ->getQuery();
             $queryrespdata = $queryresp->getQuery();
             $dataresp =  $queryrespdata->execute();
             foreach($dataresp as $clave=>$valorresp){
               $idresp = $valorresp["id"];
               $resp = $valorresp["respuestas"];
                $permisosDto->Datosposeer_titulo=array("id"=>$idresp,"nombre"=>$resp);                                      
              }

              $queryresp = $em->createQueryBuilder();
             $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
             ->from(TiposRespuestas::class,'tipos_respuestas')
             ->Where('tipos_respuestas.id='.$valor["descripcion_cargo"])
             ->getQuery();
             $queryrespdata = $queryresp->getQuery();
             $dataresp =  $queryrespdata->execute();
             foreach($dataresp as $clave=>$valorresp){
               $idresp = $valorresp["id"];
               $resp = $valorresp["respuestas"];
                $permisosDto->Datosdescripcion_cargo=array("id"=>$idresp,"nombre"=>$resp);                                      
              }

              $queryresp = $em->createQueryBuilder();
              $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
              ->from(TiposRespuestas::class,'tipos_respuestas')
              ->Where('tipos_respuestas.id='.$valor["id_confidencialidad"])
              ->getQuery();
              $queryrespdata = $queryresp->getQuery();
              $dataresp =  $queryrespdata->execute();
              foreach($dataresp as $clave=>$valorresp){
                $idresp = $valorresp["id"];
                $resp = $valorresp["respuestas"];
                 $permisosDto->Datosid_confidencialidad=array("id"=>$idresp,"nombre"=>$resp);                                      
               }

              
                $queryresp = $em->createQueryBuilder();
                $allAppointmentsQuery = $queryresp->select('departamento.id,departamento.descdepartamento')
                ->from(Departamento::class,'departamento')
                ->Where('departamento.id='.$valor["id_departamento"])
                ->getQuery();
                $queryrespdata = $queryresp->getQuery();
                $dataresp =  $queryrespdata->execute();
                foreach($dataresp as $clave=>$valorresp){
                    $idresp = $valorresp["id"];
                    $resp = $valorresp["descdepartamento"];
                    $permisosDto->departamento=array("id"=>$idresp,"nombre"=>$resp);                                      
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
                  $resp = $valorresp["descregion"];
                   $permisosDto->region=array("id"=>$idresp,"nombre"=>$resp);                                      
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
                 $resp = $valorresp["descarea"];
                  $permisosDto->area=array("id"=>$idresp,"nombre"=>$resp);                                      
                }

                $queryresp = $entityManager->createQueryBuilder();
                $allAppointmentsQuery = $queryresp->select('cargo.id,cargo.descripcion')
                ->from(Cargo::class,'cargo')
                ->Where('cargo.id='.$valor["id_cargo"])
                ->getQuery();
                $queryrespdata = $queryresp->getQuery();
                $dataresp =  $queryrespdata->execute();
                foreach($dataresp as $clave=>$valorresp){
                    $idresp = $valorresp["id"];
                    $resp = $valorresp["descripcion"];
                    $permisosDto->Datosid_cargo=array("id"=>$idresp,"nombre"=>$resp);                                      
                }


                

          $dataDatosAcademico[]=$permisosDto;
      }
       return array("data"=>$dataDatosAcademico);
    }



    /**
     * Create Datos Especialidades Areas.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new DocumentosIngresos(),$data);
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
               $entity->setIdempresa($empresa->getId());
            $entity->setCreateAt(new \DateTime());
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }

     /**
     * Update Datos Otros.
     */
    public function put($data,$id,$validator,$helper,$em): JsonResponse  
    {
        $queryresp = $em->createQueryBuilder();
           $queryresp->update(DocumentosIngresos::class,'documentos_ingresos')
          ->set('documentos_ingresos.solicitud_empleo', ':solicitud_empleo')
          ->set('documentos_ingresos.sintesis_curricular', ':sintesis_curricular')
          ->set('documentos_ingresos.copia_cedula', ':copia_cedula')
          ->set('documentos_ingresos.constancia_trabajo', ':constancia_trabajo')
          ->set('documentos_ingresos.reg_informacion_fiscal', ':reg_informacion_fiscal')
          ->set('documentos_ingresos.verificacion_ref_laborales', ':verificacion_ref_laborales')
          ->set('documentos_ingresos.certificacion_declaracion_jurada', ':certificacion_declaracion_jurada')
          ->set('documentos_ingresos.licencia', ':licencia')
          ->set('documentos_ingresos.certificado_medico', ':certificado_medico')
          ->set('documentos_ingresos.punto_cuenta', ':punto_cuenta')
          ->set('documentos_ingresos.poseer_titulo', ':poseer_titulo')
          ->set('documentos_ingresos.descripcion_cargo', ':descripcion_cargo')
          ->set('documentos_ingresos.id_cargo', ':id_cargo')
          ->set('documentos_ingresos.id_confidencialidad', ':id_confidencialidad')
          ->set('documentos_ingresos.id_departamento', ':id_departamento')
          ->set('documentos_ingresos.id_area', ':id_area')
          ->set('documentos_ingresos.id_region', ':id_region')
          ->Where('documentos_ingresos.id='.$id)
          ->setParameter('solicitud_empleo', $data['solicitud_empleo'])
          ->setParameter('sintesis_curricular', $data['sintesis_curricular'])
          ->setParameter('copia_cedula', $data['copia_cedula'])
          ->setParameter('constancia_trabajo', $data['constancia_trabajo'])
          ->setParameter('reg_informacion_fiscal', $data['reg_informacion_fiscal'])
          ->setParameter('verificacion_ref_laborales', $data['verificacion_ref_laborales'])
          ->setParameter('certificacion_declaracion_jurada', $data['certificacion_declaracion_jurada'])
          ->setParameter('licencia', $data['licencia'])
          ->setParameter('certificado_medico', $data['certificado_medico'])
          ->setParameter('punto_cuenta', $data['punto_cuenta'])
          ->setParameter('poseer_titulo', $data['poseer_titulo'])
          ->setParameter('descripcion_cargo', $data['descripcion_cargo'])
          ->setParameter('id_cargo', $data['id_cargo'])
          ->setParameter('id_confidencialidad', $data['id_confidencialidad'])
          ->setParameter('id_departamento', $data['id_departamento'])
          ->setParameter('id_area', $data['id_area'])
          ->setParameter('id_region', $data['id_region'])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Actualizado: '.$id],200);
    }

      /**
     * Delete Especialidades Areas.
     */
    public function delete($id,$validator,$helper,$em): JsonResponse  
    {
        $queryresp = $em->createQueryBuilder();
           $queryresp->delete(DocumentosIngresos::class,'documentos_ingresos')
          ->Where('documentos_ingresos.id='.$id)
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Eliminado: '.$id],200);
    }


   /**
     * Upload Data Documentos Ingresos.
     */
    public function loadDocumentosIngresos($file,$validator,$em,$formtpermt): JsonResponse  
    {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $reader = '';
        if($formtpermt=="xlsx"){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }else{
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }
        $spreadsheet = $reader->load($file); 
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $entityManagerDefault = $this->getEntityManager();
        $procesados=0;  
        $iddatospersonales=0;
        $Noprocesados=array();
        $haveError=false;
        if (!empty($sheetData)) {
            for ($i=1; $i<count($sheetData); $i++) { //skipping first row
                    if(strtolower(trim($sheetData[$i][0])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"La cedula no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][1])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"La Solicitud Empleo no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][2])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"La Sintesis Curricular no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][3])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"La Copia Cedula no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][4])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"La Constancia Trabajo no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][5])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"La Informacion Fiscal no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][6])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"La Referencias Laborales no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][7])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"La Declaración Jurada no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][8])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"La Licencia no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][9])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"El Certificado Medico no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][10])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"El Punto Cuenta no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][11])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"Poseer Título no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][12])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"Descripcion Cargo no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][13])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"Cargo no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][14])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"Region no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][15])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"Departamento no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][16])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"Area no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][17])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"Confidencialidad no puede estar en blanco");
                    } 

                    //************* VALIDACIONES FIJAS ********************************* */

                    $entity =$entityManagerDefault->getRepository(User::class)->findBy([
                        'numeroDocumento' => $sheetData[$i][0]
                    ]);
                    if (count($entity)>0) {                 
                        $cedula = trim($sheetData[$i][0]);
                        $cedula=preg_replace('([^0-9])', '', $cedula);
                    }else{
                        $haveError=true;
                        $errores[]=array("message"=>"La cedula no Existe en la entidad usuario: ".$sheetData[$i][0]);
                        $Noprocesados[]=array( "Cedula"=>$sheetData[$i][0],"errores"=>$errores);
                    }    

                    $querydaperso = $em->createQueryBuilder();
                    $allAppointmentsQuery = $querydaperso->select('datos_personales.id,datos_personales.cedula')
                    ->from(DatosPersonales::class,'datos_personales')
                    ->Where('datos_personales.cedula='.$sheetData[$i][0])
                    ->getQuery();
                    $queryult = $querydaperso->getQuery();
                    $data =  $queryult->execute();
                    if (count($data)>0) {                 
                        $iddatospersonales= $data[0]["id"];
                    }else{
                        $haveError=true;
                        $errores[]=array("message"=>"La cedula no Existe en la entidad datos_personales: ".$sheetData[$i][0]);
                    } 
                    //************* FIN VALIDACIONES FIJAS ***************************** */

                    $dataDocIngreso=[];
                    for ($ir = 1; ; $ir++) {

                      $queryresparea = $em->createQueryBuilder();
                      $allAppointmentsQuery = $queryresparea->select('tipos_respuestas')
                      ->from(TiposRespuestas::class,'tipos_respuestas')
                      ->where("tipos_respuestas.respuestas='".$sheetData[$i][$ir]."'")
                      ->getQuery();
                      $queryrespdata = $queryresparea->getQuery();
                      $dataresp =  $queryrespdata->execute();
                      if (count($dataresp)>0) {                 
                          $idresp = $dataresp[0]->getId();
                          $resp = $dataresp[0]->getRespuestas();
                      }else{
                        //crear TiposRespuestas en caso de que no exista
                        $opcioRespuesta = new TiposRespuestas;
                        $opcioRespuesta ->setRespuestas($sheetData[$i][$ir]);
                        $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                        $opcioRespuesta->setCreateBy($currentUser->getUserName());
                        $opcioRespuesta->setCreateAt(new \DateTime());
                        $entityManager->persist($opcioRespuesta);
                        $entityManager->flush();
   
                        $idrespcargo= $opcioRespuesta->getId();
                      } 

                      //$dataDocIngreso[] = array($idresp); 
                      $dataDocIngreso[] = $idresp; 

                      if ($ir > 12) {
                          break;
                      }
                    }



                    $queryresparea = $entityManagerDefault->createQueryBuilder();
                    $allAppointmentsQuery = $queryresparea->select('cargo')
                    ->from(Cargo::class,'cargo')
                    ->where("cargo.descripcion='".$sheetData[$i][14]."'")
                    ->getQuery();
                    $queryrespdata = $queryresparea->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {                 
                        $idrespcargo = $dataresp[0]->getId();
                        $respcargo = $dataresp[0]->getDescripcion();
                    }else{
                      //crear cargo en caso de que no exista
                      $opcioCargo = new Cargo;
                      $opcioCargo ->setDescripcion($sheetData[$i][14]);
                      $opcioCargo->setIdStatus($entityManagerDefault->getRepository(Status::class)->find(1)); 
                      $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                      $opcioCargo->setCreateBy($currentUser->getUserName());
                      $opcioCargo->setCreateAt(new \DateTime());
                      $entityManager->persist($opcioCargo);
                      $entityManager->flush();
 
                      $idrespcargo= $opcioCargo->getId();
                    } 


                    $queryresparea = $em->createQueryBuilder();
                    $allAppointmentsQuery = $queryresparea->select('region')
                    ->from(Region::class,'region')
                    ->where("region.descregion='".$sheetData[$i][15]."'")
                    ->getQuery();
                    $queryrespdata = $queryresparea->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {                 
                        $idrespregion = $dataresp[0]->getId();
                        $respregion = $dataresp[0]->getDescregion();
                    }else{
                      //crear cargo en caso de que no exista
                      $opcioRegion = new Region;
                      $opcioRegion ->setDescregion($sheetData[$i][15]);
                      $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                      $opcioRegion->setCreateBy($currentUser->getUserName());
                      $opcioRegion->setCreateAt(new \DateTime());
                      $entityManager->persist($opcioRegion);
                      $entityManager->flush();
 
                      $idrespregion= $opcioRegion->getId();
                    } 
                    

                    
                    $query = $em->createQueryBuilder();
                    $allAppointmentsQuery = $query->select('departamento')
                    ->from(Departamento::class,'departamento')
                    ->where("departamento.descdepartamento='".$sheetData[$i][16]."'")
                    ->getQuery();
                    $queryult = $query->getQuery();
                    $data =  $queryult->execute();
                    $cont=0;
                    if (count($data)>0) {                 
                        $idrespdeaprt=$data[0]->getId();
                        $desdepartamento=$data[0]->getDescdepartamento();
                    }else{
                        //crear departamento en caso de que no exista
                        $opcioDepartamento = new Departamento();
                        $opcioDepartamento ->setDescdepartamento($sheetData[$i][16]);
                        $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                        $opcioDepartamento->setCreateBy($currentUser->getUserName());
                        $opcioDepartamento->setCreateAt(new \DateTime());
                        $entityManager->persist($opcioDepartamento);
                        $entityManager->flush();

                        $idrespdeaprt= $opcioDepartamento->getId();
                        //$haveError=true;
                        //$errores[]=array("message"=>"La cedula no Existe en la entidad datos_personales: ".$sheetData[$i][0]);
                    } 

                    $queryresparea = $em->createQueryBuilder();
                    $allAppointmentsQuery = $queryresparea->select('area')
                    ->from(Area::class,'area')
                    ->where("area.descarea='".$sheetData[$i][17]."'")
                    ->andWhere("area.iddepartamentos=$idrespdeaprt")
                    ->getQuery();
                    $queryrespdata = $queryresparea->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {                 
                        $idresparea = $dataresp[0]->getId();
                        $respArea = $dataresp[0]->getDescarea();
                    }else{
                      //crear area en caso de que no exista
                      $opcioArea = new Area;
                      $opcioArea ->setIddepartamentos($idrespdeaprt);
                      $opcioArea ->setDescarea($sheetData[$i][17]);
                      $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                      $opcioArea->setCreateBy($currentUser->getUserName());
                      $opcioArea->setCreateAt(new \DateTime());
                      $entityManager->persist($opcioArea);
                      $entityManager->flush();
 
                      $idresparea= $opcioArea->getId();
                      //$haveError=true;
                      //$errores[]=array("message"=>"La cedula no Existe en la entidad datos_personales: ".$sheetData[$i][0]);
                    } 

                    //validar que el usuario no tenga el registro creado identico ****
                    $querydocingres = $em->createQueryBuilder();
                    $allAppointmentsQuery = $querydocingres->select('documentos_ingresos')
                    ->from(DocumentosIngresos::class,'documentos_ingresos')
                    ->Where('documentos_ingresos.id_datos_personales='.$iddatospersonales)
                    ->getQuery();
                    $queryrespdata = $querydocingres->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {                 
                        $haveError=true;
                        $errores[]=array("message"=>"El usuario ya tiene Docuemntos de Ingesos asignada verifique: ".$sheetData[$i][0]);
                    } 

                    //****************************************************************/
                    if (!$haveError) {
                          $entity=new DocumentosIngresos();
                          $entity->setIdDatosPersonales($iddatospersonales);
                          $entity->setSolicitudEmpleo($dataDocIngreso[0]);
                          $entity->setSintesisCurricular($dataDocIngreso[1]);
                          $entity->setCopiaCedula($dataDocIngreso[2]);
                          $entity->setConstanciaTrabajo($dataDocIngreso[3]);
                          $entity->setRegInformacionFiscal($dataDocIngreso[4]);
                          $entity->setVerificacionRefLaborales($dataDocIngreso[5]);
                          $entity->setCertificacionDeclaracionJurada($dataDocIngreso[6]);
                          $entity->setLicencia($dataDocIngreso[7]);
                          $entity->setCertificadoMedico($dataDocIngreso[8]);
                          $entity->setPuntoCuenta($dataDocIngreso[9]);
                          $entity->setPoseerTitulo($dataDocIngreso[10]);
                          $entity->setDescripcionCargo($dataDocIngreso[11]);
                          $entity->setIdConfidencialidad($dataDocIngreso[12]);
                          $entity->setIdCargo($idrespcargo);
                          $entity->setIdDepartamento($idrespdeaprt);
                          $entity->setIdArea($idresparea);
                          $entity->setIdRegion($idrespregion);
                          $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                          $entity->setCreateBy($currentUser->getUserName());
                          $entity->setCreateAt(new \DateTime());
                          $entityManager->persist($entity);
                          $entityManager->flush();
                          $procesados++;
                     }else{
                        $Noprocesados[]=array("Cedula"=>$sheetData[$i][0],"errores"=>$errores);
                     }
                        $haveError=false;
                        $errores=array();
                                
                    }
                //}    
            }
            $cantreg=count($sheetData);
            $cantreg--;
            return new JsonResponse(["count"=>$cantreg,"CantidadRegistrosProcesados"=>$procesados,"UsuariosNoProcesados"=>$Noprocesados]);

    }





    // /**
    //  * @return DocumentosIngresos[] Returns an array of DocumentosIngresos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DocumentosIngresos
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getReporte($em,$param)
    {
        $data=array();
        $where = "where 1=1 ";
        $whereRango="";
        if(isset($param["department"]))
          $where .= " and id=".$param["department"];

       if(isset($param["startDate"])&& isset($param["endDate"]) && isset($param["department"])){
          $whereRango .= " and c.create_at between '".$param["startDate"]."' and '".$param["endDate"]."' ";
        }else  if(isset($param["startDate"])&& isset($param["endDate"]) && !isset($param["department"])){
          $whereRango .= " c.create_at between '".$param["startDate"]."' and '".$param["endDate"]."' ";
        }else  if(isset($param["startDate"])&& !isset($param["endDate"]) && isset($param["department"])){
          $whereRango .= " and c.create_at >= '".$param["startDate"]."' ";
        }else  if(!isset($param["startDate"])&& isset($param["department"])){
          $whereRango="  and 1 = 1";      
        }else  if(isset($param["startDate"])&& !isset($param["endDate"]) && !isset($param["department"])){
          $whereRango .= " c.create_at >= '".$param["startDate"]."'";

        }else{
          $whereRango="  1 = 1";
        }
        $sql = " SELECT * FROM departamento $where ";
        $conn = $em->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resultDepartamento= $stmt->fetchAll();

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


        if(isset($param["department"])){
          foreach($resultDepartamento as $clave=>$valor){
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.solicitud_empleo = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where a.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Solicitud Empleo","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.sintesis_curricular = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where a.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Sintesis Curricular","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.copia_cedula = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where a.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Copia de Cédula","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
           $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.constancia_trabajo = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where a.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Constancia de Trabajo","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.reg_informacion_fiscal = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where a.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Registro de Información Fiscal","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.verificacion_ref_laborales = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where a.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Verificación Referencias Laborales","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.certificacion_declaracion_jurada = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where a.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Certificación Declaración Jurada","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.certificacion_declaracion_jurada = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where a.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Certificación Declaración Jurada","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.certificacion_declaracion_jurada = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where a.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Certificación Declaración Jurada","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.id_confidencialidad = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where a.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Contrato de Confidencialidad","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.poseer_titulo = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where a.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Posee Titulo","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
           $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.descripcion_cargo = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where a.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Descripcion de Cargo","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
            
           $sql = " SELECT respuestas as label,count(*) as value 
            FROM controles_varios a inner join
            tipos_respuestas b on a.normas_internas = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where f.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Normas Internas de la Empresa","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value 
            FROM controles_varios a inner join
            tipos_respuestas b on a.inscrito_ivss = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where f.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Planilla de Inscripción IVSS","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value 
            FROM controles_varios a inner join
            tipos_respuestas b on a.forma_ari = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where f.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Planilla ARI","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value 
            FROM seguridad_salud_laboral a inner join
            tipos_respuestas b on a.ruta_metro = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where f.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Ruta Metro","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value 
            FROM seguridad_salud_laboral a inner join
            tipos_respuestas b on a.analisis_seguro_trabajo = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where f.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Análisis seguro de trabajo","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
            
            
            $sql = " SELECT respuestas as label,count(*) as value 
            FROM seguridad_salud_laboral a inner join
            tipos_respuestas b on a.constancia_examenes_ocupacionales = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where f.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
               $data["entidades"][]=array("entidad"=>"Constancia de orden de exámenes ocupacionales","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
            }
  
            $sql = " SELECT respuestas as label,count(*) as value 
            FROM seguridad_salud_laboral a inner join
            tipos_respuestas b on a.constancia_normas_seguridad = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where f.id_departamento=".$valor['id']." $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
             
           
            if(count($result)){
  
  
              $data["entidades"][]=array("entidad"=>"Constancia de entrega del reglamento de normas de seguridad","departamento"=>$valor['descdepartamento'], "resultado"=>$result);
              
  
            }
  
            $sql = " SELECT count(*) as total 
            FROM documentos_ingresos f
            inner join datos_personales c on f.id_datos_personales = c.id
            inner join departamento d on f.id_departamento = d.id
            where f.id_departamento=".$valor['id']."  $whereRango"; 
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $resultMuestra= $stmt->fetchAll();
  
          }  
        }else{

            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.solicitud_empleo = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where  $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Solicitud Empleo", "resultado"=>$result);
            }
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.sintesis_curricular = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where  $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Sintesis Curricular", "resultado"=>$result);
            }
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.copia_cedula = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where  $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Copia de Cédula", "resultado"=>$result);
            }
  
           $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.constancia_trabajo = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where  $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Constancia de Trabajo", "resultado"=>$result);
            }
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.reg_informacion_fiscal = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Registro de Información Fiscal", "resultado"=>$result);
            }
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.verificacion_ref_laborales = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Verificación Referencias Laborales", "resultado"=>$result);
            }
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.certificacion_declaracion_jurada = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where  $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Certificación Declaración Jurada", "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.certificacion_declaracion_jurada = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where  $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Certificación Declaración Jurada", "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.certificacion_declaracion_jurada = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where  $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Certificación Declaración Jurada", "resultado"=>$result);
            }
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.id_confidencialidad = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Contrato de Confidencialidad", "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.poseer_titulo = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where  $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Posee Titulo", "resultado"=>$result);
            }
  
           $sql = " SELECT respuestas as label,count(*) as value FROM documentos_ingresos a inner join
            tipos_respuestas b on a.descripcion_cargo = b.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on a.id_departamento = d.id
            where  $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Descripcion de Cargo", "resultado"=>$result);
            }
  
            
           $sql = " SELECT respuestas as label,count(*) as value 
            FROM controles_varios a inner join
            tipos_respuestas b on a.normas_internas = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where  $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Normas Internas de la Empresa", "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value 
            FROM controles_varios a inner join
            tipos_respuestas b on a.inscrito_ivss = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Planilla de Inscripción IVSS", "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value 
            FROM controles_varios a inner join
            tipos_respuestas b on a.forma_ari = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where  $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Planilla ARI", "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value 
            FROM seguridad_salud_laboral a inner join
            tipos_respuestas b on a.ruta_metro = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where  $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Ruta Metro", "resultado"=>$result);
            }
  
  
            $sql = " SELECT respuestas as label,count(*) as value 
            FROM seguridad_salud_laboral a inner join
            tipos_respuestas b on a.analisis_seguro_trabajo = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
              $data["entidades"][]=array("entidad"=>"Análisis seguro de trabajo", "resultado"=>$result);
            }
            
            
            $sql = " SELECT respuestas as label,count(*) as value 
            FROM seguridad_salud_laboral a inner join
            tipos_respuestas b on a.constancia_examenes_ocupacionales = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where  $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
            if(count($result)){
               $data["entidades"][]=array("entidad"=>"Constancia de orden de exámenes ocupacionales", "resultado"=>$result);
            }
  
            $sql = " SELECT respuestas as label,count(*) as value 
            FROM seguridad_salud_laboral a inner join
            tipos_respuestas b on a.constancia_normas_seguridad = b.id
            inner join documentos_ingresos f on f.id = a.id
            inner join datos_personales c on a.id = c.id
            inner join departamento d on f.id_departamento = d.id
            where $whereRango
            GROUP BY respuestas  ";
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result= $stmt->fetchAll();
    
             
           
            if(count($result)){
  
  
              $data["entidades"][]=array("entidad"=>"Constancia de entrega del reglamento de normas de seguridad", "resultado"=>$result);
              
  
            }
  
            $sql = " SELECT count(*) as total 
            FROM documentos_ingresos f
            inner join datos_personales c on f.id_datos_personales = c.id
            inner join departamento d on f.id_departamento = d.id
            where $whereRango"; 
            $conn = $em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $resultMuestra= $stmt->fetchAll();
        }
        if(count($data)){

          $total_items = count($data['entidades']);
          $total_pages = ceil($total_items / $results_per_page); 
          $offset = ($page - 1) * $results_per_page;
          $data["count"]=$total_items;
          $data["muestra"]=$resultMuestra[0]["total"];
          $result = array_slice($data['entidades'], $offset, $results_per_page);
          $data["entidades"]=$result;


        }
        return $data;
    }

}
