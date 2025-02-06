<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\HistMovPromocion;
use App\Entity\Cargo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\StaExped\DatosPersonales;
Use App\Entity\Proyecto\Empresa;

Use App\Entity\User;
use App\Dto\StaExped\HistMovPromocionOutPutDto;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * @method HistMovPromocion|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistMovPromocion|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistMovPromocion[]    findAll()
 * @method HistMovPromocion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistMovPromocionRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, HistMovPromocion::class);
    }


    public function getHistMovPromocionByCi($id,$em){
        $entityManager = $this->getEntityManager();
        $dataDatosAcademico=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('hist_mov_promocion.id,hist_mov_promocion.id_datos_personales,
        hist_mov_promocion.id_cargo,hist_mov_promocion.fecha_promocion')
        ->from(HistMovPromocion::class,'hist_mov_promocion') 
        ->Where('hist_mov_promocion.id_datos_personales='.$id)
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $permisosDto =new HistMovPromocionOutPutDto();
          $permisosDto->id=$valor["id"];
          $permisosDto->idDatosPersonales=$valor["id_datos_personales"];
          $permisosDto->idDatosCargo=$valor["id_cargo"];
          $permisosDto->idDatosFechaPromocion=$valor["fecha_promocion"]->format("Y-m-d");
          
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
            $permisosDto->cargo=array("id"=>$idresp,"nombre"=>$resp);                                      
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
        $entity=$helper->setParametersToEntity(new HistMovPromocion(),$data);
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
           $queryresp->delete(HistMovPromocion::class,'hist_mov_promocion')
          ->Where('hist_mov_promocion.id='.$id)
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Eliminado: '.$id],200);
    }

        /**
     * Upload Data Movimiento Promoción.
     */
    public function uploadMovimientoPromocion($file,$validator,$em,$formtpermt): JsonResponse  
    {
        $entityManager = $em;
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
                        $errores[]=array("message"=>"El Cargo no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][2])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"La fecha promoción no puede estar en blanco");
                    } 
                    
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

                    $queryresparea = $entityManagerDefault->createQueryBuilder();
                    $allAppointmentsQuery = $queryresparea->select('cargo')
                    ->from(Cargo::class,'cargo')
                    ->where("cargo.descripcion='".$sheetData[$i][1]."'")
                    ->getQuery();
                    $queryrespdata = $queryresparea->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {                 
                        $idrespcargo = $dataresp[0]->getId();
                        $respcargo = $dataresp[0]->getDescripcion();
                    }else{
                      //crear cargo en caso de que no exista
                      $opcioCargo = new Cargo;
                      $opcioCargo ->setDescripcion($sheetData[$i][1]);
                      $opcioCargo->setIdStatus($entityManagerDefault->getRepository(Status::class)->find(1)); 
                      $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                      $opcioCargo->setCreateBy($currentUser->getUserName());
                      $opcioCargo->setCreateAt(new \DateTime());
                      $entityManager->persist($opcioCargo);
                      $entityManager->flush();
 
                      $idrespcargo= $opcioCargo->getId();
                    } 

                    //validar que el usuario no tenga el registro creado identico ****
                    $querydocingres = $em->createQueryBuilder();
                    $allAppointmentsQuery = $querydocingres->select('hist_mov_promocion')
                    ->from(HistMovPromocion::class,'hist_mov_promocion')
                    ->Where('hist_mov_promocion.id_datos_personales='.$iddatospersonales)
                    ->andWhere("hist_mov_promocion.id_cargo=$idrespcargo")
                    ->getQuery();
                    $queryrespdata = $querydocingres->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {                 
                        $haveError=true;
                        $errores[]=array("message"=>"El usuario ya tiene este cargo asignado como promoción verifique: ".$sheetData[$i][0]);
                    } 

                    
                    if (!$haveError) {
                          $entity=new HistMovPromocion();
                          $entity->setIdDatosPersonales($iddatospersonales);
                          $entity->setIdCargo($idrespcargo);
                          $entity->setFechaPromocion(!is_null($sheetData[$i][2])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $sheetData[$i][2] )))):null);
                          $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                          $entity->setCreateBy($currentUser->getUserName());
                          $entity->setCreateAt(new \DateTime());

                          $entityManager->persist($entity);
                          $entityManager->flush();
                          $procesados++;
                     }else{
                       // $Noprocesados[]=array("Cedula"=>$sheetData[$i][0],"errores"=>$errores);
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
    //  * @return HistMovPromocion[] Returns an array of HistMovPromocion objects
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
    public function findOneBySomeField($value): ?HistMovPromocion
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

