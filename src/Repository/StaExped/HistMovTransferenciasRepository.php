<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\HistMovTransferencias;
use App\Entity\StaExped\Departamento;
use App\Entity\StaExped\Region;
use App\Entity\StaExped\DatosPersonales;
Use App\Entity\Proyecto\Empresa;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Entity\StaExped\Area;
use App\Dto\StaExped\HistMovTransferenciasOutPutDto;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * @method HistMovTransferencias|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistMovTransferencias|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistMovTransferencias[]    findAll()
 * @method HistMovTransferencias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistMovTransferenciasRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, HistMovTransferencias::class);
    }

    public function getHistoricosMovimientosTransferenciasByCi($id,$em){
        //$entityManager = $this->getEntityManager();
        $dataDatosAcademico=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('hist_mov_transferencias.id,hist_mov_transferencias.id_datos_personales,
        hist_mov_transferencias.id_departamento,hist_mov_transferencias.id_area,hist_mov_transferencias.id_region,hist_mov_transferencias.fecha_transferencia')
        ->from(HistMovTransferencias::class,'hist_mov_transferencias') 
        ->Where('hist_mov_transferencias.id_datos_personales='.$id)
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $estudiosacademicosDto =new HistMovTransferenciasOutPutDto();
          $estudiosacademicosDto->id=$valor["id"];
          $estudiosacademicosDto->idDatosPersonales=$valor["id_datos_personales"];
          $estudiosacademicosDto->idDatosDepartamento=$valor["id_departamento"];
          $estudiosacademicosDto->idDatosAreas=$valor["id_area"];
          $estudiosacademicosDto->idDatosRegion=$valor["id_region"];
          $estudiosacademicosDto->Datosfecha_transferencia=$valor["fecha_transferencia"]->format("Y-m-d");
          
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
             $estudiosacademicosDto->departamento=array("id"=>$idresp,"nombre"=>$resp);                                      
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
              $estudiosacademicosDto->region=array("id"=>$idresp,"nombre"=>$resp);                                      
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
             $estudiosacademicosDto->area=array("id"=>$idresp,"nombre"=>$resp);                                      
           }

   
          $dataDatosAcademico[]=$estudiosacademicosDto;
      }
       return array("data"=>$dataDatosAcademico);
    }



    /**
     * Create Datos Especialidades Areas.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new HistMovTransferencias(),$data);
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
           $queryresp->delete(HistMovTransferencias::class,'hist_mov_transferencias')
          ->Where('hist_mov_transferencias.id='.$id)
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Eliminado: '.$id],200);
    }


  /**
     * Upload Data Transferencias.
     */
    public function uploadTransferencias($file,$validator,$em,$formtpermt): JsonResponse  
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
                        $errores[]=array("message"=>"La Región no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][2])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"El Departamento no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][3])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"El Área no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][4])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"La Fecha de Transferencia no puede estar en blanco");
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

                    $queryresparea = $em->createQueryBuilder();
                    $allAppointmentsQuery = $queryresparea->select('region')
                    ->from(Region::class,'region')
                    ->where("region.descregion='".$sheetData[$i][1]."'")
                    ->getQuery();
                    $queryrespdata = $queryresparea->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {                 
                        $idrespregion = $dataresp[0]->getId();
                        $respregion = $dataresp[0]->getDescregion();
                    }else{
                      //crear cargo en caso de que no exista
                      $opcioRegion = new Region;
                      $opcioRegion ->setDescregion($sheetData[$i][1]);
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
                    ->where("departamento.descdepartamento='".$sheetData[$i][2]."'")
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
                        $opcioDepartamento ->setDescdepartamento($sheetData[$i][2]);
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
                    ->where("area.descarea='".$sheetData[$i][3]."'")
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
                      $opcioArea ->setDescarea($sheetData[$i][3]);
                      $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                      $opcioArea->setCreateBy($currentUser->getUserName());
                      $opcioArea->setCreateAt(new \DateTime());
                      $entityManager->persist($opcioArea);
                      $entityManager->flush();
 
                      $idresparea= $opcioArea->getId();
                      //$haveError=true;
                      //$errores[]=array("message"=>"La cedula no Existe en la entidad datos_personales: ".$sheetData[$i][0]);
                    } 

                    /* //validar que el usuario no tenga el registro creado identico ****
                    $querydocingres = $em->createQueryBuilder();
                    $allAppointmentsQuery = $querydocingres->select('hist_mov_transferencias')
                    ->from(HistMovTransferencias::class,'hist_mov_transferencias')
                    ->Where('hist_mov_transferencias.id_datos_personales='.$iddatospersonales)
                    ->getQuery();
                    $queryrespdata = $querydocingres->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {                 
                        $haveError=true;
                        $errores[]=array("message"=>"El usuario ya tiene Docuemntos de Ingesos asignada verifique: ".$sheetData[$i][0]);
                    } 
 */
                    //****************************************************************/
                    if (!$haveError) {
                          $entity=new HistMovTransferencias();
                          $entity->setIdDatosPersonales($iddatospersonales);
                          $entity->setIdDepartamento($idrespdeaprt);
                          $entity->setIdArea($idresparea);
                          $entity->setIdRegion($idrespregion);
                          $entity->setFechaTransferencia(!is_null($sheetData[$i][4])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $sheetData[$i][4] )))):null);
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
    //  * @return HistMovTransferencias[] Returns an array of HistMovTransferencias objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistMovTransferencias
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
