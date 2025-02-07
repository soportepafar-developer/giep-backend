<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\DatosPersonales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Dto\StaExped\DatosPersonalesOutPutDto;
Use App\Entity\User;
use App\Entity\StaExped\TiposRespuestas;
Use App\Entity\Proyecto\Empresa;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method DatosPersonales|null find($id, $lockMode = null, $lockVersion = null)
 * @method DatosPersonales|null findOneBy(array $criteria, array $orderBy = null)
 * @method DatosPersonales[]    findAll()
 * @method DatosPersonales[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatosPersonalesRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, DatosPersonales::class);
    }


    public function getProyectByCi($ci,$em){
        //$entityManager = $this->getEntityManager();
        $dataDatosPersonales=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('datos_personales.id,datos_personales.cedula,datos_personales.fecha_ingreso
        ,datos_personales.autorizacion_ingreso,datos_personales.familiar_empresa')
        ->from(DatosPersonales::class,'datos_personales')
        ->Where('datos_personales.cedula='.$ci)
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $datospersonalesDto =new DatosPersonalesOutPutDto();
          $datospersonalesDto->id=$valor["id"];
          $datospersonalesDto->numeroDocumento=$valor["cedula"];
          //$datospersonalesDto->admissionDate=$valor["fecha_ingreso"];
          $datospersonalesDto->admissionDate=$valor["fecha_ingreso"]->format("Y-m-d");

          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
          ->from(TiposRespuestas::class,'tipos_respuestas')
          ->Where('tipos_respuestas.id='.$valor["autorizacion_ingreso"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $resp = $valorresp["respuestas"];
             $datospersonalesDto->autorizacion_ingreso=array("id"=>$idresp,"nombre"=>$resp);                                      
           }

           $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
          ->from(TiposRespuestas::class,'tipos_respuestas')
          ->Where('tipos_respuestas.id='.$valor["familiar_empresa"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $resp = $valorresp["respuestas"];
             $datospersonalesDto->familyBusiness=array("id"=>$idresp,"nombre"=>$resp);                                      
           }

          //$datospersonalesDto->entryAuthorization=$valor["autorizacion_ingreso"];
          //$datospersonalesDto->autorizacion_ingreso=array("id"=>1,"nombre"=>"Si");        

          //$datospersonalesDto->familyBusiness=$valor["familiar_empresa"];


          $dataDatosPersonales[]=$datospersonalesDto;
      }
       return array("data"=>$dataDatosPersonales);
    }

    


     /**
     * Create Datos Personales.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new DatosPersonales(),$data);
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
     * Update Datos Personales.
     */
    public function put($data,$id,$validator,$helper,$em): JsonResponse  
    {
        $queryresp = $em->createQueryBuilder();
           $queryresp->update(DatosPersonales::class,'datos_personales')
          ->set('datos_personales.familiar_empresa', ':familiar_empresa')
          ->set('datos_personales.autorizacion_ingreso', ':autorizacion_ingreso')
          ->set('datos_personales.fecha_ingreso', ':fecha_ingreso')
          ->Where('datos_personales.id='.$id)
          ->setParameter('familiar_empresa', $data['familiar_empresa'])
          ->setParameter('autorizacion_ingreso', $data['autorizacion_ingreso'])
          ->setParameter('fecha_ingreso', $data['fecha_ingreso'])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Actualizado: '.$id],200);
    }
    

        /**
     * Upload Data Personal.
     */
    public function loadDataPersonal($file,$validator,$em,$formtpermt): JsonResponse  
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
                        $errores[]=array("message"=>"La fecha de ingreso no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][2])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"La autorización de ingreso no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][3])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"Familiar empresa no puede estar en blanco");
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

                    $query = $em->createQueryBuilder();
                    $allAppointmentsQuery = $query->select('datos_personales.id,datos_personales.cedula')
                    ->from(DatosPersonales::class,'datos_personales')
                    ->Where('datos_personales.cedula='.$sheetData[$i][0])
                    ->getQuery();
                    $queryult = $query->getQuery();
                    $data =  $queryult->execute();
                    if (count($data)>0) {                 
                        $haveError=true;
                        $errores[]=array("message"=>"La cedula Existe en la entidad datos_personales: ".$sheetData[$i][0]);
                    }   
                    if (!$haveError) {
                          $entity=new DatosPersonales();
                          $entity->setCedula($cedula);
                          $entity->setFechaIngreso(!is_null($sheetData[$i][1])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $sheetData[$i][1] )))):null);
                          $entity->setAutorizacionIngreso($this->Respuestas($sheetData[$i][2],$em));
                          $entity->setFamiliarEmpresa($this->Respuestas($sheetData[$i][3],$em));
                          
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


public function Respuestas($idresp,$em){
  
  $queryresp = $em->createQueryBuilder();
  $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
  ->from(TiposRespuestas::class,'tipos_respuestas')
  ->where("tipos_respuestas.respuestas='".$idresp."'")
  ->getQuery();
  $queryrespdata = $queryresp->getQuery();
  $dataresp =  $queryrespdata->execute();
  foreach($dataresp as $clave=>$valorresp){
    $idresptipresp = $valorresp["id"];
    $Resput  = $valorresp["respuestas"];
   }
   return $idresptipresp;
  
}

    // /**
    //  * @return DatosPersonales[] Returns an array of DatosPersonales objects
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
    public function findOneBySomeField($value): ?DatosPersonales
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
