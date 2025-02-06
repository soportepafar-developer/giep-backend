<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\SeguridadSaludLaboral;
use App\Entity\StaExped\TiposRespuestas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\StaExped\DatosPersonales;
Use App\Entity\Proyecto\Empresa;

Use App\Entity\User;
use App\Dto\StaExped\SeguridadSaludLaboralOutPutDto;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method SeguridadSaludLaboral|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeguridadSaludLaboral|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeguridadSaludLaboral[]    findAll()
 * @method SeguridadSaludLaboral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeguridadSaludLaboralRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, SeguridadSaludLaboral::class);
    }

    
    public function getSeguridadSaludLaboralByCi($id,$em){
        //$entityManager = $this->getEntityManager();
        $dataDatosAcademico=[];
        $query = $em->createQueryBuilder();

        $allAppointmentsQuery = $query->select('seguridad_salud_laboral.id,seguridad_salud_laboral.id_datos_personales,
        seguridad_salud_laboral.ruta_metro,seguridad_salud_laboral.analisis_seguro_trabajo,seguridad_salud_laboral.entrega_equipo_proteccion
        ,seguridad_salud_laboral.constancia_examenes_ocupacionales,seguridad_salud_laboral.constancia_normas_seguridad,
        seguridad_salud_laboral.copia_registro_delegado')
        ->from(SeguridadSaludLaboral::class,'seguridad_salud_laboral') 
        ->Where('seguridad_salud_laboral.id_datos_personales='.$id)
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $permisosDto =new SeguridadSaludLaboralOutPutDto();
          $permisosDto->id=$valor["id"];
          $permisosDto->idDatosPersonales=$valor["id_datos_personales"];
          $permisosDto->idDatosruta_metro=$valor["ruta_metro"];
          $permisosDto->idDatosanalisis_seguro_trabajo=$valor["analisis_seguro_trabajo"];
          $permisosDto->idDatosentrega_equipo_proteccion=$valor["entrega_equipo_proteccion"];
          $permisosDto->idDatosconstancia_examenes_ocupacionales=$valor["constancia_examenes_ocupacionales"];
          $permisosDto->idDatosconstancia_normas_seguridad=$valor["constancia_normas_seguridad"];
          $permisosDto->idDatoscopia_registro_delegado=$valor["copia_registro_delegado"];
          
           $queryresp = $em->createQueryBuilder();
           $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
           ->from(TiposRespuestas::class,'tipos_respuestas')
           ->Where('tipos_respuestas.id='.$valor["ruta_metro"])
           ->getQuery();
           $queryrespdata = $queryresp->getQuery();
           $dataresp =  $queryrespdata->execute();
           foreach($dataresp as $clave=>$valorresp){
             $idresp = $valorresp["id"];
             $resp = $valorresp["respuestas"];
              $permisosDto->Datosruta_metro=array("id"=>$idresp,"nombre"=>$resp);                                      
            }

            $queryresp = $em->createQueryBuilder();
           $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
           ->from(TiposRespuestas::class,'tipos_respuestas')
           ->Where('tipos_respuestas.id='.$valor["analisis_seguro_trabajo"])
           ->getQuery();
           $queryrespdata = $queryresp->getQuery();
           $dataresp =  $queryrespdata->execute();
           foreach($dataresp as $clave=>$valorresp){
             $idresp = $valorresp["id"];
             $resp = $valorresp["respuestas"];
              $permisosDto->Datosanalisis_seguro_trabajo=array("id"=>$idresp,"nombre"=>$resp);                                      
            }

            
            $queryresp = $em->createQueryBuilder();
           $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
           ->from(TiposRespuestas::class,'tipos_respuestas')
           ->Where('tipos_respuestas.id='.$valor["entrega_equipo_proteccion"])
           ->getQuery();
           $queryrespdata = $queryresp->getQuery();
           $dataresp =  $queryrespdata->execute();
           foreach($dataresp as $clave=>$valorresp){
             $idresp = $valorresp["id"];
             $resp = $valorresp["respuestas"];
              $permisosDto->Datosentrega_equipo_proteccion=array("id"=>$idresp,"nombre"=>$resp);                                      
            }

            $queryresp = $em->createQueryBuilder();
            $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
            ->from(TiposRespuestas::class,'tipos_respuestas')
            ->Where('tipos_respuestas.id='.$valor["constancia_examenes_ocupacionales"])
            ->getQuery();
            $queryrespdata = $queryresp->getQuery();
            $dataresp =  $queryrespdata->execute();
            foreach($dataresp as $clave=>$valorresp){
              $idresp = $valorresp["id"];
              $resp = $valorresp["respuestas"];
               $permisosDto->Datosconstancia_examenes_ocupacionales=array("id"=>$idresp,"nombre"=>$resp);                                      
             }

             $queryresp = $em->createQueryBuilder();
            $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
            ->from(TiposRespuestas::class,'tipos_respuestas')
            ->Where('tipos_respuestas.id='.$valor["constancia_normas_seguridad"])
            ->getQuery();
            $queryrespdata = $queryresp->getQuery();
            $dataresp =  $queryrespdata->execute();
            foreach($dataresp as $clave=>$valorresp){
              $idresp = $valorresp["id"];
              $resp = $valorresp["respuestas"];
               $permisosDto->Datosconstancia_normas_seguridad=array("id"=>$idresp,"nombre"=>$resp);                                      
             }

             $queryresp = $em->createQueryBuilder();
             $allAppointmentsQuery = $queryresp->select('tipos_respuestas.id,tipos_respuestas.respuestas')
             ->from(TiposRespuestas::class,'tipos_respuestas')
             ->Where('tipos_respuestas.id='.$valor["copia_registro_delegado"])
             ->getQuery();
             $queryrespdata = $queryresp->getQuery();
             $dataresp =  $queryrespdata->execute();
             foreach($dataresp as $clave=>$valorresp){
               $idresp = $valorresp["id"];
               $resp = $valorresp["respuestas"];
                $permisosDto->Datoscopia_registro_delegado=array("id"=>$idresp,"nombre"=>$resp);                                      
              }

          $dataDatosAcademico[]=$permisosDto;
      }
       return array("data"=>$dataDatosAcademico);
    }



    /**
     * Create Datos Seguridad Salud Laboral.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new SeguridadSaludLaboral(),$data);
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
     * Update Datos Seguridad Salud Laboral.
     */
    public function put($data,$id,$validator,$helper,$em): JsonResponse  
    {
        $queryresp = $em->createQueryBuilder();
           $queryresp->update(SeguridadSaludLaboral::class,'seguridad_salud_laboral')
          ->set('seguridad_salud_laboral.ruta_metro', ':ruta_metro')
          ->set('seguridad_salud_laboral.analisis_seguro_trabajo', ':analisis_seguro_trabajo')
          ->set('seguridad_salud_laboral.entrega_equipo_proteccion', ':entrega_equipo_proteccion')
          ->set('seguridad_salud_laboral.constancia_examenes_ocupacionales	', ':constancia_examenes_ocupacionales')
          ->set('seguridad_salud_laboral.constancia_normas_seguridad	', ':constancia_normas_seguridad')
          ->set('seguridad_salud_laboral.copia_registro_delegado	', ':copia_registro_delegado')
          ->Where('seguridad_salud_laboral.id='.$id)
          ->setParameter('ruta_metro', $data['ruta_metro'])
          ->setParameter('analisis_seguro_trabajo', $data['analisis_seguro_trabajo'])
          ->setParameter('entrega_equipo_proteccion', $data['entrega_equipo_proteccion'])
          ->setParameter('constancia_examenes_ocupacionales', $data['constancia_examenes_ocupacionales'])
          ->setParameter('constancia_normas_seguridad', $data['constancia_normas_seguridad'])
          ->setParameter('copia_registro_delegado', $data['copia_registro_delegado'])
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
           $queryresp->delete(SeguridadSaludLaboral::class,'seguridad_salud_laboral')
          ->Where('seguridad_salud_laboral.id='.$id)
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Eliminado: '.$id],200);
    }


    /**
     * Upload Data Seguridad Salud Laboral.
     */
    public function loadSeguridadSaludLaboral($file,$validator,$em,$formtpermt): JsonResponse  
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
                        $errores[]=array("message"=>"Deposito prestaciones sociales no puede estar en blanco");
                    } 
                    if(strtolower(trim($sheetData[$i][2])=="")){
                        $haveError=true;
                        $errores[]=array("message"=>"Recibo Anual Intereses no puede estar en blanco");
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

                       if ($ir > 5) {
                          break;
                       } 
                    }

                    //validar que el usuario no tenga el registro creado identico ****
                    $querydocingres = $em->createQueryBuilder();
                    $allAppointmentsQuery = $querydocingres->select('seguridad_salud_laboral')
                    ->from(SeguridadSaludLaboral::class,'seguridad_salud_laboral')
                    ->Where('seguridad_salud_laboral.id_datos_personales='.$iddatospersonales)
                    ->getQuery();
                    $queryrespdata = $querydocingres->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {                 
                        $haveError=true;
                        $errores[]=array("message"=>"El usuario ya tiene Controles Varios asignada verifique: ".$sheetData[$i][0]);
                    } 

                    //****************************************************************/
                    if (!$haveError) {
                          $entity=new SeguridadSaludLaboral();
                          $entity->setIdDatosPersonales($iddatospersonales);
                          $entity->setRutaMetro($dataDocIngreso[0]);
                          $entity->setAnalisisSeguroTrabajo($dataDocIngreso[1]);
                          $entity->setEntregaEquipoProteccion($dataDocIngreso[2]);
                          $entity->setConstanciaExamenesOcupacionales($dataDocIngreso[3]);
                          $entity->setConstanciaNormasSeguridad($dataDocIngreso[4]);
                          $entity->setCopiaRegistroDelegado($dataDocIngreso[5]);


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
    //  * @return SeguridadSaludLaboral[] Returns an array of SeguridadSaludLaboral objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SeguridadSaludLaboral
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
