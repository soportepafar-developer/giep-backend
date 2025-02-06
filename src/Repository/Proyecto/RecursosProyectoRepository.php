<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\RecursosProyecto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Proyecto\TrazaRepository;

Use App\Entity\User;
Use App\Entity\Proyecto\Proyecto;
//Use App\Entity\Proyecto\SprintItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method RecursosProyecto|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecursosProyecto|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecursosProyecto[]    findAll()
 * @method RecursosProyecto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecursosProyectoRepository extends ServiceEntityRepository
{
    private $security;
    private $traza;
    public function __construct(ManagerRegistry $registry,Security $security,TrazaRepository $traza)
    {
        $this->security = $security;
        $this->traza=$traza;
        parent::__construct($registry, RecursosProyecto::class);
    }

    /**
     * Create Recursos Proyecto.
     */
    public function post($data,$validator,$helper): JsonResponse  {

        //var_dump($data);die;

        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new RecursosProyecto(),$data);


        $entityManager = $this->getEntityManager();

        // $valid = $data['idrecurso'];

        // $entity12 =$entityManager->getRepository(User::class)->find($data['idrecurso']);
        // if (!$entity12) {
        //     return new JsonResponse(['msg'=>'No existen Registros de Recursos con el idrecurso: '.$data['idrecurso']],404);  
        // }

        // $entity13 =$entityManager->getRepository(Proyecto::class)->find($data['idproyecto']);
        // if (!$entity13) {
        //     return new JsonResponse(['msg'=>'No existen el Proyecto con el idproyecto: '.$data['idproyecto']],404);  
        // }
        
        //var_dump("Probar Validandooooo ");die;

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            
            foreach($data['arrayuserresorce'] as $options){

                $valid =  $options['idproyecto'];

                 $entity12 =$entityManager->getRepository(User::class)->find($options["idrecurso"]);
                 if (!$entity12) {
                     return new JsonResponse(['msg'=>'No existen Registros de Recursos con el idrecurso: '.$options["idrecurso"]],404);  
                 }

                 $entity13 =$entityManager->getRepository(Proyecto::class)->find($options['idproyecto']);
                 if (!$entity13) {
                     return new JsonResponse(['msg'=>'No existen el Proyecto con el idproyecto: '.$options['idproyecto']],404);  
                 }
                $opciones = new RecursosProyecto();
                $opciones->setHorasdedicacion($options["horasdedicacion"]);
                $opciones->setIdrecurso($entity12);
                $opciones->setIdproyecto($entity13);
                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                    $entity->setIdempresa($opciones); 
                $entityManager->persist($opciones);
                $entityManager->flush();    
            }

            //$entityManager->persist($entity);
            //$entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado ','id'=>$opciones->getId()],200);
        }    
    }


/**
     * Create Recursos Proyecto.
     */
    public function postrecursosproyectoid($data,$validator,$helper): JsonResponse  {
        //var_dump($data);die;
        $encontroUserId=false;
        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new RecursosProyecto(),$data);
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
                    $userId = $data["userId"];
                    $projectId =$data['projectId'];
                    $queryresp = $entityManager->createQueryBuilder();
                    $allAppointmentsQuery = $queryresp->select('recursos_proyecto')
                    ->from(RecursosProyecto::class,'recursos_proyecto')
                    ->where("recursos_proyecto.idrecurso =$userId")
                    ->andWhere("recursos_proyecto.idproyecto=$projectId")
                    ->getQuery();
                    $queryrespdata = $queryresp->getQuery();
                    $dataresp =  $queryrespdata->execute();
                    if (count($dataresp)>0) {                 
                        //return new JsonResponse(['msg'=>'Existen Registros de Recursos con el idrecurso: '.$data["userId"]],404);  
                        $encontroUserId=true;
                    }

                $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                $userId = $data["userId"];

                $entity12 =$entityManager->getRepository(User::class)->find($data["userId"]);
                 if (!$entity12) {
                     return new JsonResponse(['msg'=>'No existen Registros de Recursos con el idrecurso: '.$data["userId"]],404);  
                 }

                 $entity13 =$entityManager->getRepository(Proyecto::class)->find($data['projectId']);
                 if (!$entity13) {
                     return new JsonResponse(['msg'=>'No existen el Proyecto con el idproyecto: '.$data['projectId']],404);  
                 }
                 if ($encontroUserId==true) {                
                    $entityManager = $this->getEntityManager();
                    $sql2 = "update recursos_proyecto set swact=1,horasdedicacion=".$data["dedicatedHours"]." where idproyecto_id =".$data["projectId"]." AND idrecurso_id=".$data["userId"]."";
                    $conn2 = $this->getEntityManager()->getConnection();
                    $stmt2 = $conn2->prepare($sql2);
                    $stmt2->execute();  
                    $data=array("tipoEntidad"=>"Actualizar Recurso Proyecto","idEntidad"=>$entity->getId(),"createdBy"=>$currentUser->getId(),"sqlInstruction"=>"update","accion"=>4);
                    $this->traza->post($data,$validator,$helper);
                    return new JsonResponse(['msg'=>'Registro Actualizado ','id'=>$userId],200);
                }else{
                    $opciones = new RecursosProyecto();
                    $opciones->setHorasdedicacion($data["dedicatedHours"]);
                    $opciones->setIdrecurso($entity12);
                    $opciones->setIdproyecto($entity13);
                    $opciones->setSwact(1);
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                        $entity->setIdempresa($empresa); 
                    $entityManager->persist($opciones);
                    $entityManager->flush();    
                    $data=array("tipoEntidad"=>"Nuevo Recurso Proyecto","idEntidad"=>$entity->getId(),"createdBy"=>$currentUser->getId(),"sqlInstruction"=>"Inser into","accion"=>3);
                    $this->traza->post($data,$validator,$helper);
                    return new JsonResponse(['msg'=>'Registro Creado ','id'=>$opciones->getId()],200);
               }
               
        }    
    }


   /**
     * Delete Recursos Proyecto.
     */
    public function putCalendarioRecursProy($data,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $userId = $data["userId"];
        $entity =$entityManager->getRepository(Proyecto::class)->find($data["projectId"]);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }

        $sql = "SELECT * FROM spring b 
        inner join sprint_item x on b.id = x.id_spring_id 
        inner join items i on x.id_item_id = i.id_backlog_padre_id where b.idproyecto_id=".$data["projectId"]." and i.id_user_id =".$data["userId"].";";

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result= $stmt->fetchAll();
        //cuanto registro hay
         $cunat = count($result);
         $springProy=array();
         $isCurrent = 0;
        foreach($result as $claveResult2=>$valorResult){
            $fech_fincompara1 = strtotime($valorResult["fechainicio"]);
            $fech_inicocompara = date('Y-m-d', $fech_fincompara1);
            $fech_fincompara1 = strtotime($fech_inicocompara);
            $fech_fincompara2 = strtotime($valorResult["fechafin"]);
            $fech_fincompara = date('Y-m-d', $fech_fincompara2);
            $fech_fincompara2 = strtotime($fech_fincompara);
            $fecha1 = date("Y-m-d");
            $fecha = strtotime($fecha1);
            if (($fecha >= $fech_fincompara1) && ($fecha <= $fech_fincompara2)){
                $isCurrent = 1;
                $sql2 = "update items set id_user_id=NULL where id =".$valorResult["id"]." ";
                $conn2 = $this->getEntityManager()->getConnection();
                $stmt2 = $conn2->prepare($sql2);
                $stmt2->execute();  
                $data=array("tipoEntidad"=>"Eliminar Recurso Items","idEntidad"=>$entity->getId(),"createdBy"=>$currentUser->getId(),"sqlInstruction"=>"Delete","accion"=>5);
                $this->traza->post($data,$validator,$helper);
            }else{
                if (($fech_fincompara1 > $fecha)){
                      $isCurrent = 'NULL';
                      $sql2 = "update items set id_user_id=NULL where id =".$valorResult["id"]." ";
                      $conn2 = $this->getEntityManager()->getConnection();
                      $stmt2 = $conn2->prepare($sql2);
                      $stmt2->execute();  
                      $data=array("tipoEntidad"=>"Eliminar Recurso Items","idEntidad"=>$entity->getId(),"createdBy"=>$currentUser->getId(),"sqlInstruction"=>"Delete","accion"=>5);
                      $this->traza->post($data,$validator,$helper);
                }else{
                    //aqui ya pasaron los spring
                  $isCurrent = 0;
                }
            }
        }
        
        $sql2 = "update recursos_proyecto set swact=0 where idproyecto_id =".$data["projectId"]." AND idrecurso_id=".$data["userId"]."";
        $conn2 = $this->getEntityManager()->getConnection();
        $stmt2 = $conn2->prepare($sql2);
        $stmt2->execute();  
        $data=array("tipoEntidad"=>"Eliminar Recurso Proyecto","idEntidad"=>$entity->getId(),"createdBy"=>$currentUser->getId(),"sqlInstruction"=>"Delete","accion"=>5);
        $this->traza->post($data,$validator,$helper);
       return new JsonResponse(['msg'=>'Registro Eliminado: '.$userId],200);
    }




    // /**
    //  * @return RecursosProyecto[] Returns an array of RecursosProyecto objects
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
    public function findOneBySomeField($value): ?RecursosProyecto
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
