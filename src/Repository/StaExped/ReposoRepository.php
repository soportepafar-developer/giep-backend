<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\Reposo;
use App\Entity\StaExped\TipoReposo;
use App\Entity\StaExped\TipoMotivoReposo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\StaExped\DatosPersonales;
Use App\Entity\Proyecto\Empresa;

Use App\Entity\User;
use App\Dto\StaExped\ReposoOutPutDto;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Reposo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reposo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reposo[]    findAll()
 * @method Reposo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReposoRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Reposo::class);
    }


    public function getReposoByCi($id,$em){
        //$entityManager = $this->getEntityManager();
        $dataDatosAcademico=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('reposo.id,reposo.id_datos_personales,
        reposo.id_tiporeposo,reposo.id_motivo_reposo,reposo.fecha_desde,reposo.fecha_hasta')
        ->from(Reposo::class,'reposo') 
        ->Where('reposo.id_datos_personales='.$id)
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $permisosDto =new ReposoOutPutDto();
          $permisosDto->id=$valor["id"];
          $permisosDto->idDatosPersonales=$valor["id_datos_personales"];
          $permisosDto->idDatosTipoReposo=$valor["id_tiporeposo"];
          $permisosDto->idDatosMotivoReposo=$valor["id_motivo_reposo"];
          $permisosDto->idDatosFechaDesde=$valor["fecha_desde"]->format("Y-m-d");
          $permisosDto->idDatosFechaHasta=$valor["fecha_hasta"]->format("Y-m-d");
          
          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipo_reposo.id,tipo_reposo.tiporeposo')
          ->from(TipoReposo::class,'tipo_reposo')
          ->Where('tipo_reposo.id='.$valor["id_tiporeposo"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $resp = $valorresp["tiporeposo"];
            $permisosDto->tiporeposo=array("id"=>$idresp,"nombre"=>$resp);                                      
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
              $resp = $valorresp["motivo"];
              $permisosDto->motivo=array("id"=>$idresp,"nombre"=>$resp);                                      
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
        $entity=$helper->setParametersToEntity(new Reposo(),$data);
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
           $queryresp->delete(Reposo::class,'reposo')
          ->Where('reposo.id='.$id)
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Eliminado: '.$id],200);
    }


        /**
     * Upload Data Reposos.
     */
    public function uploadReposo($file,$validator,$em,$formtpermt): JsonResponse  
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
                        $errores[]=array("message"=>"Tipo Reposo no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][2])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"Motivo Reposos no puede estar en blanco");
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


                       $queryresp = $em->createQueryBuilder();
                       $allAppointmentsQuery = $queryresp->select('tipo_reposo.id,tipo_reposo.tiporeposo')
                       ->from(TipoReposo::class,'tipo_reposo')
                       ->where("tipo_reposo.tiporeposo='".$sheetData[$i][1]."'")
                       ->getQuery();
                       $queryrespdata = $queryresp->getQuery();
                       $dataresp =  $queryrespdata->execute();
                      if (count($dataresp)>0) {   
                        $idresp_tipo_reposo = $dataresp[0]["id"];
                        $resp_tipo_reposo = $dataresp[0]["tiporeposo"];
                      }else{
                        //crear TiposRespuestas en caso de que no exista
                        $opcioRespuesta = new TipoReposo;
                        $opcioRespuesta ->setTiporeposo($sheetData[$i][1]);
                        $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                        $opcioRespuesta->setCreateBy($currentUser->getUserName());
                        $opcioRespuesta->setCreateAt(new \DateTime());
                        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $entity->setIdempresa($empresa->getId());
                        $entityManager->persist($opcioRespuesta);
                        $entityManager->flush();
                        $idresp_tipo_reposo= $opcioRespuesta->getId();
                      } 


                      $queryresp = $em->createQueryBuilder();
                      $allAppointmentsQuery = $queryresp->select('tipo_motivo_reposo.id,tipo_motivo_reposo.motivo')
                      ->from(TipoMotivoReposo::class,'tipo_motivo_reposo')
                      ->where("tipo_motivo_reposo.motivo='".$sheetData[$i][2]."'")
                      ->getQuery();
                      $queryrespdata = $queryresp->getQuery();
                      $dataresp =  $queryrespdata->execute();
                     if (count($dataresp)>0) {   
                       $idresp_tipo_motivo_reposo = $dataresp[0]["id"];
                       $resp_tipo_motivo_reposo = $dataresp[0]["motivo"];
                     }else{
                       //crear TiposRespuestas en caso de que no exista
                       $opcioRespuesta = new TipoMotivoReposo;
                       $opcioRespuesta ->setMotivo($sheetData[$i][2]);
                       $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                       $opcioRespuesta->setCreateBy($currentUser->getUserName());
                       $opcioRespuesta->setCreateAt(new \DateTime());
                       $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $entity->setIdempresa($empresa->getId());
                       $entityManager->persist($opcioRespuesta);
                       $entityManager->flush();
                       $idresp_tipo_motivo_reposo= $opcioRespuesta->getId();
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
                          $entity=new Reposo();
                          $entity->setIdDatosPersonales($iddatospersonales);
                          $entity->setIdTiporeposo($idresp_tipo_reposo);
                          $entity->setIdMotivoReposo($idresp_tipo_motivo_reposo);
                          $entity->setFechaDesde(!is_null($sheetData[$i][3])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $sheetData[$i][3] )))):null);
                          $entity->setFechaHasta(!is_null($sheetData[$i][4])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $sheetData[$i][4] )))):null);
                          $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                          $entity->setCreateBy($currentUser->getUserName());
                          $entity->setCreateAt(new \DateTime());
                          $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                          if($empresa)
                            $entity->setIdempresa($empresa);
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
    //  * @return Reposo[] Returns an array of Reposo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reposo
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

