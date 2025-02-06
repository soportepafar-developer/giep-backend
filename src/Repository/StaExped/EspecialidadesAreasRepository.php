<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\EspecialidadesAreas;
use App\Entity\StaExped\DatosPersonales;
use App\Entity\StaExped\Departamento;
use App\Entity\StaExped\Area;
Use App\Entity\Proyecto\Empresa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Dto\StaExped\EspecialidadesAreasOutPutDto;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method EspecialidadesAreas|null find($id, $lockMode = null, $lockVersion = null)
 * @method EspecialidadesAreas|null findOneBy(array $criteria, array $orderBy = null)
 * @method EspecialidadesAreas[]    findAll()
 * @method EspecialidadesAreas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EspecialidadesAreasRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, EspecialidadesAreas::class);
    }

    public function getEspecialidadesAreasByCi($id,$em){
        //$entityManager = $this->getEntityManager();
        $dataDatosAcademico=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('especialidades_areas.id,especialidades_areas.id_personales,
        especialidades_areas.id_area')
        ->from(EspecialidadesAreas::class,'especialidades_areas') 
        ->Where('especialidades_areas.id_personales='.$id)
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $estudiosacademicosDto =new EspecialidadesAreasOutPutDto();
          $estudiosacademicosDto->id=$valor["id"];
          $estudiosacademicosDto->idDatosPersonales=$valor["id_personales"];
          $estudiosacademicosDto->idDatosAreas=$valor["id_area"];
          
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
        $entity=$helper->setParametersToEntity(new EspecialidadesAreas(),$data);
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
           $queryresp->delete(EspecialidadesAreas::class,'especialidades_areas')
          ->Where('especialidades_areas.id='.$id)
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Eliminado: '.$id],200);
    }


    

     /**
     * Upload Data Estudios Academicos.
     */
    public function loadEspecilalidadesAreas($file,$validator,$em,$formtpermt): JsonResponse  
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
                        $errores[]=array("message"=>"El Departamento no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][2])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"El Area no puede estar en blanco");
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
                    $allAppointmentsQuery = $query->select('departamento')
                    ->from(Departamento::class,'departamento')
                    ->where("departamento.descdepartamento='".$sheetData[$i][1]."'")
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
                        $opcioDepartamento ->setDescdepartamento($sheetData[$i][1]);
                        $entityManager->persist($opcioDepartamento);
                        $entityManager->flush();

                        $idrespdeaprt= $opcioDepartamento->getId();
                        //$haveError=true;
                        //$errores[]=array("message"=>"La cedula no Existe en la entidad datos_personales: ".$sheetData[$i][0]);
                    } 

                    $queryresparea = $em->createQueryBuilder();
                    $allAppointmentsQuery = $queryresparea->select('area')
                    ->from(Area::class,'area')
                    ->where("area.descarea='".$sheetData[$i][2]."'")
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
                      $opcioArea ->setDescarea($sheetData[$i][2]);
                      $entityManager->persist($opcioArea);
                      $entityManager->flush();
 
                      $idresparea= $opcioArea->getId();
                      //$haveError=true;
                      //$errores[]=array("message"=>"La cedula no Existe en la entidad datos_personales: ".$sheetData[$i][0]);
                    } 

                    //validar que el usuario no tenga el registro creado identico ****
                    $queryresparea = $em->createQueryBuilder();
                    $allAppointmentsQuery = $queryresparea->select('especialidades_areas')
                    ->from(EspecialidadesAreas::class,'especialidades_areas')
                    ->Where('especialidades_areas.id_area='.$idresparea)
                    ->andWhere('especialidades_areas.id_personales='.$iddatospersonales)
                    ->getQuery();
                    $queryrespdata = $queryresparea->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {                 
                        $haveError=true;
                        $errores[]=array("message"=>"El usuario ya tiene esa especialidades Area asignada verifique: ".$sheetData[$i][0]);
                    } 

                    //****************************************************************/

                    /* foreach($dataresp as $clave=>$valorarea){
                        $idresparea = $valorarea->getId();
                        $respArea = $valorarea->getDescarea();
                    } */
                    
                    if (!$haveError) {
                          $entity=new EspecialidadesAreas();
                          $entity->setIdPersonales($iddatospersonales);
                          $entity->setIdArea($idresparea);

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
    //  * @return EspecialidadesAreas[] Returns an array of EspecialidadesAreas objects
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
    public function findOneBySomeField($value): ?EspecialidadesAreas
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
