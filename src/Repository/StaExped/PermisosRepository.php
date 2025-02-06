<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\Permisos;
use App\Entity\StaExped\TipoMotivoPermiso;
use App\Entity\StaExped\TiposRespuestas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\StaExped\DatosPersonales;
Use App\Entity\Proyecto\Empresa;

Use App\Entity\User;
use App\Dto\StaExped\PermisosOutPutDto;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * @method Permisos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Permisos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Permisos[]    findAll()
 * @method Permisos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermisosRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Permisos::class);
    }


    public function getPermisosByCi($id,$em){
        //$entityManager = $this->getEntityManager();
        $dataDatosAcademico=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('permisos.id,permisos.id_datos_personales,
        permisos.id_motivo_permiso,permisos.autorizacion_permisos,permisos.fecha_desde,permisos.fecha_hasta')
        ->from(Permisos::class,'permisos') 
        ->Where('permisos.id_datos_personales='.$id)
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $permisosDto =new PermisosOutPutDto();
          $permisosDto->id=$valor["id"];
          $permisosDto->idDatosPersonales=$valor["id_datos_personales"];
          $permisosDto->idDatosMotivo=$valor["id_motivo_permiso"];
          $permisosDto->idDatosAutorizacion=$valor["autorizacion_permisos"];
          $permisosDto->idDatosFechaDesde=$valor["fecha_desde"]->format("Y-m-d");
          $permisosDto->idDatosFechaHasta=$valor["fecha_hasta"]->format("Y-m-d");
          
          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipo_motivo_permiso.id,tipo_motivo_permiso.permiso')
          ->from(TipoMotivoPermiso::class,'tipo_motivo_permiso')
          ->Where('tipo_motivo_permiso.id='.$valor["id_motivo_permiso"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $resp = $valorresp["permiso"];
            $permisosDto->motivo=array("id"=>$idresp,"nombre"=>$resp);                                      
           }

            $queryresp = $em->createQueryBuilder();
            $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
            ->from(TiposRespuestas::class,'tipos_respuestas')
            ->Where('tipos_respuestas.id='.$valor["autorizacion_permisos"])
            ->getQuery();
            $queryrespdata = $queryresp->getQuery();
            $dataresp =  $queryrespdata->execute();
            foreach($dataresp as $clave=>$valorresp){
              $idresp = $valorresp["id"];
              $resp = $valorresp["respuestas"];
              $permisosDto->autorizacion=array("id"=>$idresp,"nombre"=>$resp);                                      
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
        $entity=$helper->setParametersToEntity(new Permisos(),$data);
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
           $queryresp->delete(Permisos::class,'permisos')
          ->Where('permisos.id='.$id)
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Eliminado: '.$id],200);
    }


    /**
     * Upload Data Permisos.
     */
    public function uploadPermisos($file,$validator,$em,$formtpermt): JsonResponse  
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
                        $errores[]=array("message"=>"Autorizacion Permiso no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][2])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"Motivo Permiso  no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][3])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"Fecha Desde no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][4])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"Fecha Hasta no puede estar en blanco");
                    } 
                    
                    $comp = $this->valida_fecha($sheetData[$i][3],$sheetData[$i][4]);
                    if($comp==false){
                        $haveError=true;
                        $errores[]=array("message"=>"Verifique la fecha desde y fecha hasta inconsistencia al comparar");
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
                    $allAppointmentsQuery = $queryresp->select('tipo_motivo_permiso.id,tipo_motivo_permiso.permiso')
                    ->from(TipoMotivoPermiso::class,'tipo_motivo_permiso')
                    ->where("tipo_motivo_permiso.permiso='".$sheetData[$i][2]."'")
                    ->getQuery();
                    $queryrespdata = $queryresp->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {   
                        $idresp_tipo_permiso = $dataresp[0]["id"];
                        $resp_tipo_vacaciones = $dataresp[0]["permiso"];
                      }else{
                        //crear TiposRespuestas en caso de que no exista
                        $opcioRespuesta = new TipoMotivoPermiso;
                        $opcioRespuesta ->setPermiso($sheetData[$i][2]);
                        $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                        $opcioRespuesta->setCreateBy($currentUser->getUserName());
                        $opcioRespuesta->setCreateAt(new \DateTime());
                        $entityManager->persist($opcioRespuesta);
                        $entityManager->flush();
                        $idresp_tipo_permiso= $opcioRespuesta->getId();
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
                          $entity=new Permisos();
                          $entity->setIdDatosPersonales($iddatospersonales);
                          $entity->setAutorizacionPermisos($dataDocIngreso[0]);
                          $entity->setIdMotivoPermiso($idresp_tipo_permiso);
                          $entity->setFechaDesde(!is_null($sheetData[$i][3])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $sheetData[$i][3] )))):null);
                          $entity->setFechaHasta(!is_null($sheetData[$i][4])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $sheetData[$i][4] )))):null);
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

    function valida_fecha($fecha_inicial,$fecha_final)
    {
        if (strtotime($fecha_inicial) > strtotime($fecha_final)) {
            return false;
        }
        if (strtotime($fecha_final) < strtotime($fecha_inicial)) {
            return false;
        }
        if (strtotime($fecha_inicial) == strtotime($fecha_final)) {
            return true;
        }
        if (strtotime($fecha_inicial) < strtotime($fecha_final)) {
            return true;
        }
    }


    // /**
    //  * @return Permisos[] Returns an array of Permisos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Permisos
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
