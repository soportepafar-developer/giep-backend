<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\EstudiosAcademicos;
use App\Entity\StaExped\DatosPersonales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Dto\StaExped\EstudiosAcademicosOutPutDto;
Use App\Entity\User;
use App\Entity\StaExped\TiposRespuestas;
use App\Entity\StaExped\Profesion;
Use App\Entity\Proyecto\Empresa;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * @method EstudiosAcademicos|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstudiosAcademicos|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstudiosAcademicos[]    findAll()
 * @method EstudiosAcademicos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstudiosAcademicosRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, EstudiosAcademicos::class);
    }


    
    public function getAcademByCi($id,$em){
        //$entityManager = $this->getEntityManager();
        $dataDatosAcademico=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('estudios_academicos.id,estudios_academicos.iddatos_personales,
        estudios_academicos.idprofesion,estudios_academicos.fecha_graduado')
        ->from(EstudiosAcademicos::class,'estudios_academicos') 
        ->Where('estudios_academicos.iddatos_personales='.$id)
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $estudiosacademicosDto =new EstudiosAcademicosOutPutDto();
          $estudiosacademicosDto->id=$valor["id"];
          $estudiosacademicosDto->idDatosPersonales=$valor["iddatos_personales"];
          $estudiosacademicosDto->idDatosProfesion=$valor["idprofesion"];
          $estudiosacademicosDto->Datosfecha_graduado=$valor["fecha_graduado"]->format("Y-m-d");

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
             $estudiosacademicosDto->profesion=array("id"=>$idresp,"nombre"=>$resp);                                      
           }

   
          $dataDatosAcademico[]=$estudiosacademicosDto;
      }
       return array("data"=>$dataDatosAcademico);
    }
    


        /**
     * Create Datos Personales.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new EstudiosAcademicos(),$data);
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
     * Update Datos Estudios Academicos.
     */
    public function put($data,$id,$validator,$helper,$em): JsonResponse  
    {
        $queryresp = $em->createQueryBuilder();
           $queryresp->update(EstudiosAcademicos::class,'estudios_academicos')
          ->set('estudios_academicos.idprofesion', ':idprofesion')
          ->set('estudios_academicos.fecha_graduado', ':fecha_graduado')
          ->Where('estudios_academicos.id='.$id)
          ->setParameter('idprofesion', $data['idprofesion'])
          ->setParameter('fecha_graduado', $data['fecha_graduado'])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Actualizado: '.$id],200);
    }

    /**
     * Delete Estudios Academicos.
     */
    public function delete($id,$validator,$helper,$em): JsonResponse  
    {
        $queryresp = $em->createQueryBuilder();
           $queryresp->delete(EstudiosAcademicos::class,'estudios_academicos')
          ->Where('estudios_academicos.id='.$id)
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Eliminado: '.$id],200);
    }

    /**
     * Upload Data Estudios Academicos.
     */
    public function loadEstudiosAcademicos($file,$validator,$em,$formtpermt): JsonResponse  
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
                        $errores[]=array("message"=>"La profesion no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][2])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"La fecha graduado no puede estar en blanco");
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

                    $query = $em->createQueryBuilder();
                    $allAppointmentsQuery = $query->select('profesion')
                    ->from(Profesion::class,'profesion')
                    ->where("profesion.descprofesion='".$sheetData[$i][1]."'")
                    ->addOrderBy('profesion.descprofesion', 'ASC')
                    ->getQuery();
                    $queryult = $query->getQuery();
                    $data =  $queryult->execute();
                    $cont=0;
                    foreach($data as $clave=>$valor){
                        $idprofesion=$valor->getId();
                    }

                    
                    if (!$haveError) {
                          $entity=new EstudiosAcademicos();
                          $entity->setIdDatosPersonales($iddatospersonales);
                          $entity->setIdProfesion($idprofesion);
                          $entity->setFechaGraduado(!is_null($sheetData[$i][2])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $sheetData[$i][2] )))):null);

                          
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
    //  * @return EstudiosAcademicos[] Returns an array of EstudiosAcademicos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EstudiosAcademicos
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
