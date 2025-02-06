<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\Vaciones;
use App\Entity\StaExped\TipoVacaciones;
use App\Entity\StaExped\TiposRespuestas;
use App\Entity\StaExped\DatosPersonales;
use App\Entity\StaExped\VacacionesObservacion;
Use App\Entity\Proyecto\Empresa;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Dto\StaExped\VacionesOutPutDto;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Vaciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vaciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vaciones[]    findAll()
 * @method Vaciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacionesRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Vaciones::class);
    }

    public function getVacacionesByCi($id,$em){
        //$entityManager = $this->getEntityManager();
        $dataDatosAcademico=[];
        $query = $em->createQueryBuilder();

        $allAppointmentsQuery = $query->select('vaciones.id,vaciones.id_datos_personales,
        vaciones.autorizacion_vacaciones,vaciones.id_tipo_vacaciones,vaciones.periodos_acumulados,vaciones.periodo_difrute,vaciones.fecha_desde,vaciones.fecha_hasta,vaciones.fecha_incorporacion')
        ->from(Vaciones::class,'vaciones') 
        ->Where('vaciones.id_datos_personales='.$id)
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $permisosDto =new VacionesOutPutDto();
          $permisosDto->id=$valor["id"];
          $permisosDto->idDatosPersonales=$valor["id_datos_personales"];
          $permisosDto->idDatosautorizacion_vacaciones=$valor["autorizacion_vacaciones"];
          $permisosDto->idDatosid_tipo_vacaciones=$valor["id_tipo_vacaciones"];
          $permisosDto->idDatosperiodos_acumulados=$valor["periodos_acumulados"];
          $permisosDto->idDatosperiodo_difrute=$valor["periodo_difrute"];
          $permisosDto->idDatosFechaDesde=$valor["fecha_desde"]->format("Y-m-d");
          $permisosDto->idDatosFechaHasta=$valor["fecha_hasta"]->format("Y-m-d");
          $permisosDto->idDatosfecha_incorporacion=$valor["fecha_incorporacion"]->format("Y-m-d");
          
          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipo_vacaciones.id,tipo_vacaciones.vacaciones')
          ->from(TipoVacaciones::class,'tipo_vacaciones')
          ->Where('tipo_vacaciones.id='.$valor["id_tipo_vacaciones"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $resp = $valorresp["vacaciones"];
            $permisosDto->Datostipovacaciones=array("id"=>$idresp,"nombre"=>$resp);                                      
           }

           $queryresp = $em->createQueryBuilder();
           $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
           ->from(TiposRespuestas::class,'tipos_respuestas')
           ->Where('tipos_respuestas.id='.$valor["autorizacion_vacaciones"])
           ->getQuery();
           $queryrespdata = $queryresp->getQuery();
           $dataresp =  $queryrespdata->execute();
           foreach($dataresp as $clave=>$valorresp){
             $idresp = $valorresp["id"];
             $resp = $valorresp["respuestas"];
              $permisosDto->Datosautorizacion_vacaciones=array("id"=>$idresp,"nombre"=>$resp);                                      
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
        $entity=$helper->setParametersToEntity(new Vaciones(),$data);
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
     * Delete Especialidades Areas.
     */
    public function delete($id,$validator,$helper,$em): JsonResponse  
    {
        $queryresp = $em->createQueryBuilder();
           $queryresp->delete(Vaciones::class,'vaciones')
          ->Where('vaciones.id='.$id)
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Eliminado: '.$id],200);
    }



/**
     * Upload Data Vacaciones.
     */
    public function uploadVacaciones($file,$validator,$em,$formtpermt): JsonResponse  
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
                        $errores[]=array("message"=>"Autorización Vacaciones no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][2])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"Tipo Vacaciones no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][3])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"Fecha Desde no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][4])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"Fecha Hasta no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][5])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"Fecha Incorporación no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][6])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"Periodos Acumulados no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][7])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"Periodo Difrute no puede estar en blanco");
                    } 

                    $comp = $this->valida_fecha($sheetData[$i][3],$sheetData[$i][4]);
                    if($comp==false){
                        $haveError=true;
                        $errores[]=array("message"=>"Verifique la fecha desde y fecha hasta inconsistencia al comparar");
                    }

                    $comp = $this->valida_fecha($sheetData[$i][4],$sheetData[$i][5]);
                    if($comp==false){
                        $haveError=true;
                        $errores[]=array("message"=>"Verifique la fecha hasta y fecha incorporación inconsistencia al comparar");
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

                       if ($ir > 0) {
                          break;
                       } 
                    }


                    $queryresp = $em->createQueryBuilder();
                    $allAppointmentsQuery = $queryresp->select('tipo_vacaciones.id,tipo_vacaciones.vacaciones')
                    ->from(TipoVacaciones::class,'tipo_vacaciones')
                    ->where("tipo_vacaciones.vacaciones='".$sheetData[$i][2]."'")
                    ->getQuery();
                    $queryrespdata = $queryresp->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {   
                        $idresp_tipo_vacaciones = $dataresp[0]["id"];
                        $resp_tipo_vacaciones = $dataresp[0]["vacaciones"];
                      }else{
                        //crear TiposRespuestas en caso de que no exista
                        $opcioRespuesta = new TipoVacaciones;
                        $opcioRespuesta ->setVacaciones($sheetData[$i][2]);
                        $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                        $opcioRespuesta->setCreateBy($currentUser->getUserName());
                        $opcioRespuesta->setCreateAt(new \DateTime());
                        $entityManager->persist($opcioRespuesta);
                        $entityManager->flush();
                        $idresp_tipo_vacaciones= $opcioRespuesta->getId();
                      } 
                      
                    //validar que el usuario no tenga el registro creado identico ****
                    /* $querydocingres = $em->createQueryBuilder();
                    $allAppointmentsQuery = $querydocingres->select('otros')
                    ->from(Otros::class,'otros')
                    ->Where('otros.id_datos_personales='.$iddatospersonales)
                    ->getQuery();
                    $queryrespdata = $querydocingres->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {                 
                        $haveError=true;
                        $errores[]=array("message"=>"El usuario ya tiene Otros asignado verifique: ".$sheetData[$i][0]);
                    }  */

                    //****************************************************************/
                    if (!$haveError) {
                          $entity=new Vaciones();
                          $entity->setIdDatosPersonales($iddatospersonales);
                          $entity->setAutorizacionVacaciones($dataDocIngreso[0]);
                          $entity->setPeriodosAcumulados($sheetData[$i][6]);
                          $entity->setIdTipoVacaciones($idresp_tipo_vacaciones);
                          $entity->setFechaDesde(!is_null($sheetData[$i][3])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $sheetData[$i][3] )))):null);
                          $entity->setFechaHasta(!is_null($sheetData[$i][4])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $sheetData[$i][4] )))):null);
                          $entity->setFechaIncorporacion(!is_null($sheetData[$i][5])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $sheetData[$i][5] )))):null);
                          $entity->setPeriodoDifrute($sheetData[$i][7]);

                          $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                          $entity->setCreateBy($currentUser->getUserName());
                          $entity->setCreateAt(new \DateTime());
                          $entityManager->persist($entity);
                          $entityManager->flush();

                          if ($sheetData[$i][5]) {

                            $query = $em->createQueryBuilder();
                            $allAppointmentsQuery = $query->select('vacaciones_observacion.id,vacaciones_observacion.id_datos_personales,
                            vacaciones_observacion.observacion')
                            ->from(VacacionesObservacion::class,'vacaciones_observacion') 
                            ->Where('vacaciones_observacion.id_datos_personales='.$iddatospersonales)
                            ->getQuery();
                            $queryult = $query->getQuery();
                            $dataresp =  $queryult->execute();
                            if (count($dataresp)>0) { 
                                $queryresp = $em->createQueryBuilder();
                                $queryresp->update(VacacionesObservacion::class,'vacaciones_observacion')
                               ->set('vacaciones_observacion.observacion', ':observacion')
                               ->Where('vacaciones_observacion.id='.$dataresp[0]["id"])
                               ->setParameter('observacion', $sheetData[$i][8])
                               ->getQuery();
                               $queryrespdata = $queryresp->getQuery();
                               $dataresp =  $queryrespdata->execute();
                            }else{
                                $entity=new VacacionesObservacion();
                                $entity->setIdDatosPersonales($iddatospersonales);
                                $entity->setObservacion($sheetData[$i][8]);
                                $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                                $entity->setCreateBy($currentUser->getUserName());
                                $entity->setCreateAt(new \DateTime());
                                $entityManager->persist($entity);
                                $entityManager->flush();
                            }
                          }

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

    function valida_fecha($fecha_inicial,$fecha_final)
    {
        if (strtotime($fecha_inicial) > strtotime($fecha_final)) {
            return false;
        }
        if (strtotime($fecha_final) < strtotime($fecha_inicial)) {
            return false;
        }
        if (strtotime($fecha_inicial) < strtotime($fecha_final)) {
            return true;
        }
    }

    // /**
    //  * @return Vaciones[] Returns an array of Vaciones objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vaciones
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
