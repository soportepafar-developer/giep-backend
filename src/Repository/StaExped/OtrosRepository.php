<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\Otros;
use App\Entity\StaExped\CategoriasOtros;
use App\Entity\StaExped\TiposRespuestas;
use App\Entity\StaExped\TipoMotivoExpediente;
Use App\Entity\Proyecto\Empresa;

use App\Entity\StaExped\DatosPersonales;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Dto\StaExped\OtrosOutPutDto;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * @method Otros|null find($id, $lockMode = null, $lockVersion = null)
 * @method Otros|null findOneBy(array $criteria, array $orderBy = null)
 * @method Otros[]    findAll()
 * @method Otros[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OtrosRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Otros::class);
    }


    public function getOtrosByCi($id,$em){
        //$entityManager = $this->getEntityManager();
        $dataDatosAcademico=[];
        $query = $em->createQueryBuilder();

   
        $allAppointmentsQuery = $query->select('otros.id,otros.id_datos_personales,otros.expedientes_legal,otros.motivo
        ,otros.curso_desarrollo_area_laboral,otros.profesion_orientada_area_servicio,otros.id_categoria_otros')
        ->from(Otros::class,'otros') 
        ->Where('otros.id_datos_personales='.$id)
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $permisosDto =new Otros();
          $permisosDto->id=$valor["id"];
          $permisosDto->idDatosPersonales=$valor["id_datos_personales"];
          $permisosDto->idDatosexpedientes_legal=$valor["expedientes_legal"];
          $permisosDto->idDatosmotivo=$valor["motivo"];
          $permisosDto->idDatoscurso_desarrollo_area_laboral=$valor["curso_desarrollo_area_laboral"];
          $permisosDto->idDatosprofesion_orientada_area_servicio=$valor["profesion_orientada_area_servicio"];
          $permisosDto->idDatosid_categoria_otros=$valor["id_categoria_otros"];


          $queryresp = $em->createQueryBuilder();
          $allAppointmentsQuery = $queryresp->select('tipo_motivo_expediente.id,tipo_motivo_expediente.motivoexpediente')
          ->from(TipoMotivoExpediente::class,'tipo_motivo_expediente')
          ->Where('tipo_motivo_expediente.id='.$valor["motivo"])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          foreach($dataresp as $clave=>$valorresp){
            $idresp = $valorresp["id"];
            $resp = $valorresp["motivoexpediente"];
             $permisosDto->Datosid_motivoexpedientes=array("id"=>$idresp,"nombre"=>$resp);                                      
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
              $resp = $valorresp["tipcategoria"];
               $permisosDto->Datosid_categoria_otros=array("id"=>$idresp,"nombre"=>$resp);                                      
             }

          
           $queryresp = $em->createQueryBuilder();
           $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
           ->from(TiposRespuestas::class,'tipos_respuestas')
           ->Where('tipos_respuestas.id='.$valor["expedientes_legal"])
           ->getQuery();
           $queryrespdata = $queryresp->getQuery();
           $dataresp =  $queryrespdata->execute();
           foreach($dataresp as $clave=>$valorresp){
             $idresp = $valorresp["id"];
             $resp = $valorresp["respuestas"];
              $permisosDto->Datosexpedientes_legal=array("id"=>$idresp,"nombre"=>$resp);                                      
            }

            $queryresp = $em->createQueryBuilder();
            $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
            ->from(TiposRespuestas::class,'tipos_respuestas')
            ->Where('tipos_respuestas.id='.$valor["curso_desarrollo_area_laboral"])
            ->getQuery();
            $queryrespdata = $queryresp->getQuery();
            $dataresp =  $queryrespdata->execute();
            foreach($dataresp as $clave=>$valorresp){
              $idresp = $valorresp["id"];
              $resp = $valorresp["respuestas"];
               $permisosDto->Datoscurso_desarrollo_area_laboral=array("id"=>$idresp,"nombre"=>$resp);                                      
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
        $entity=$helper->setParametersToEntity(new Otros(),$data);
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
           $queryresp->update(Otros::class,'otros')
          ->set('otros.expedientes_legal', ':expedientes_legal')
          ->set('otros.motivo', ':motivo')
          ->set('otros.curso_desarrollo_area_laboral', ':curso_desarrollo_area_laboral')
          ->set('otros.profesion_orientada_area_servicio', ':profesion_orientada_area_servicio')
          ->set('otros.id_categoria_otros', ':id_categoria_otros')
          ->Where('otros.id='.$id)
          ->setParameter('expedientes_legal', $data['expedientes_legal'])
          ->setParameter('motivo', $data['motivo'])
          ->setParameter('curso_desarrollo_area_laboral', $data['curso_desarrollo_area_laboral'])
          ->setParameter('profesion_orientada_area_servicio', $data['profesion_orientada_area_servicio'])
          ->setParameter('id_categoria_otros', $data['id_categoria_otros'])
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
           $queryresp->delete(Otros::class,'otros')
          ->Where('otros.id='.$id)
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Eliminado: '.$id],200);
    }



/**
     * Upload Data Otros.
     */
    public function loadOtros($file,$validator,$em,$formtpermt): JsonResponse  
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
                        $errores[]=array("message"=>"Expedientes Legal no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][2])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"Curso Desarrollo Area Laboral no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][3])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"Categoría no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][4])=="")){
                      $haveError=true;
                      $errores[]=array("message"=>"Motivo Expediente no puede estar en blanco");
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
                        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $entity->setIdempresa($empresa->getId());
                        $entityManager->persist($opcioRespuesta);
                        $entityManager->flush();
   
                        $idrespcargo= $opcioRespuesta->getId();
                      } 

                      //$dataDocIngreso[] = array($idresp); 
                      $dataDocIngreso[] = $idresp; 

                       if ($ir > 1) {
                          break;
                       } 
                    }

                    $queryresp = $em->createQueryBuilder();
                    $allAppointmentsQuery = $queryresp->select('tipo_motivo_expediente.id,tipo_motivo_expediente.motivoexpediente')
                    ->from(TipoMotivoExpediente::class,'tipo_motivo_expediente')
                    ->where("tipo_motivo_expediente.motivoexpediente='".$sheetData[$i][4]."'")
                    ->getQuery();
                    $queryrespdata = $queryresp->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {   
                      $idresp_tipo_motivo_expediente = $dataresp[0]["id"];
                      $resp_tipo_motivo_expediente = $dataresp[0]["motivoexpediente"];
                    }else{
                      //crear TiposRespuestas en caso de que no exista
                      $opcioRespuesta = new TipoMotivoExpediente;
                      $opcioRespuesta ->setMotivoexpediente($sheetData[$i][4]);
                      $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                      $opcioRespuesta->setCreateBy($currentUser->getUserName());
                      $opcioRespuesta->setCreateAt(new \DateTime());
                      $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $entity->setIdempresa($empresa->getId());
                      $entityManager->persist($opcioRespuesta);
                      $entityManager->flush();
                      $idresp_tipo_motivo_expediente= $opcioRespuesta->getId();
                    } 
          
          
                    $queryresp = $em->createQueryBuilder();
                      $allAppointmentsQuery = $queryresp->select('categorias_otros.id,categorias_otros.tipcategoria')
                      ->from(CategoriasOtros::class,'categorias_otros')
                      ->where("categorias_otros.tipcategoria='".$sheetData[$i][3]."'")
                      ->getQuery();
                      $queryrespdata = $queryresp->getQuery();
                      $dataresp =  $queryrespdata->execute();
                      if (count($dataresp)>0) {    
                        $idresp_categorias_otros = $dataresp[0]["id"];
                        $resp_categorias_otros = $dataresp[0]["tipcategoria"];
                      }else{
                        //crear TiposRespuestas en caso de que no exista
                        $opcioRespuesta = new CategoriasOtros;
                        $opcioRespuesta ->setTipcategoria($sheetData[$i][3]);
                        $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
                        $opcioRespuesta->setCreateBy($currentUser->getUserName());
                        $opcioRespuesta->setCreateAt(new \DateTime());
                        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $entity->setIdempresa($empresa->getId());
                        $entityManager->persist($opcioRespuesta);
                        $entityManager->flush();
                        $idresp_categorias_otros= $opcioRespuesta->getId();
                      } 
                      
                    //validar que el usuario no tenga el registro creado identico ****
                    $querydocingres = $em->createQueryBuilder();
                    $allAppointmentsQuery = $querydocingres->select('otros')
                    ->from(Otros::class,'otros')
                    ->Where('otros.id_datos_personales='.$iddatospersonales)
                    ->getQuery();
                    $queryrespdata = $querydocingres->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {                 
                        $haveError=true;
                        $errores[]=array("message"=>"El usuario ya tiene Otros asignado verifique: ".$sheetData[$i][0]);
                    } 

                    //****************************************************************/
                    if (!$haveError) {
                          $entity=new Otros();
                          $entity->setIdDatosPersonales($iddatospersonales);
                          $entity->setExpedientesLegal($dataDocIngreso[0]);
                          $entity->setCursoDesarrolloAreaLaboral($dataDocIngreso[1]);

                          $entity->setIdCategoriaOtros($idresp_categorias_otros);
                          $entity->setMotivo($idresp_tipo_motivo_expediente);
                          
                          if ($sheetData[$i][5]) {
                            $entity->setProfesionOrientadaAreaServicio($sheetData[$i][5]);
                          }

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




    // /**
    //  * @return Otros[] Returns an array of Otros objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Otros
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
