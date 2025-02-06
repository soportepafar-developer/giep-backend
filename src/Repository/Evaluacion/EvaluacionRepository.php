<?php

namespace App\Repository\Evaluacion;

use App\Dto\Evaluacion\EvaluacionOutPutDto;
use App\Dto\Evaluacion\EvaluacionUsersOutPutDto;
use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Evaluacion\EvaluacionUsuario;
use App\Entity\Evaluacion\OpcionesEvaluacion;
use App\Entity\Evaluacion\PreguntaEvaluacion;
use App\Entity\Encuesta\TipoUnidad;
use App\Entity\Evaluacion\SeccionEvaluacion;
use App\Entity\Evaluacion\TipoCategoriaEvaluacion;
use App\Entity\Encuesta\TipoInput;
use App\Entity\Status;
use App\Entity\Pais;
use App\Entity\Estado;
use App\Entity\Cargo;
use App\Entity\Rol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
Use App\Entity\User;
use App\Entity\Proyecto\Empresa;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

/**
 * @method Evaluacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evaluacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evaluacion[]    findAll()
 * @method Evaluacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluacionRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Evaluacion::class);
    }

    

    public function findListByInstructor($data){

        $entity= $this->getEntityManager()->createQueryBuilder();

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
       
        $query= $this->createQueryBuilder('a');
        $query->orderBy('a.id', 'ASC');
        if($data['word']!=null){
            $query->where("a.nombre like '%".$data['word']."%' ");
        }

        $query->andwhere("a.evaluatorUserId =".$this->security->getUser()->getId());
        $query->andWhere("a.publicar =1");
        $query->andWhere("a.fechaVigencia  >= CURRENT_DATE()" );
        $query->orderBy('a.id', 'ASC');   
        $query->getQuery();

        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery()	
      	->setFirstResult($data['rowByPage'] *($data['page']-1))	
      	->setMaxResults($data['rowByPage']);	
        $dataUser=array();
        $hijos=[];
        $rolesUser=[];
        $dataevaluacion=array();
        foreach($paginator as $clave=>$valor){
            $evaluacionDto =new EvaluacionOutPutDto();
            $evaluacionDto->id=$valor->getId();
            $evaluacionDto->nombre=$valor->getNombre();
            $evaluacionDto->descripcion=$valor->getDescripcion();
            $evaluacionDto->idTipoUnidad=($valor->getIdTipoUnidad()!=null)?array("id"=>$valor->getIdTipoUnidad()->getId(),"Nombre"=>$valor->getIdTipoUnidad()->getNombre(),"Factor"=>$valor->getIdTipoUnidad()->getFactor()):[];
            $evaluacionDto->unidad=$valor->getUnidad();
            $evaluacionDto->path=$valor->getPath();
            $evaluacionDto->orden=$valor->getOrden();;

            //$evaluacionDto->fechaPublicacion=$valor->getFechaPublicacion()->format("Y-m-d");
            $evaluacionDto->fechaPublicacion=!is_null($valor->getFechaPublicacion())?$valor->getFechaPublicacion()->format("Y-m-d"):null;
            $evaluacionDto->createAt=!is_null($valor->getCreateAt())?$valor->getCreateAt()->format("Y-m-d"):null;
            //$evaluacionDto->createAt=$valor->getCreateAt()->format("Y-m-d");
            $evaluacionDto->fechaVigencia=$valor->getFechaVigencia()->format("Y-m-d");
            $evaluacionDto->publicar=$valor->getPublicar();
            $evaluacionDto->editable=1;

            $entity= $this->getEntityManager()->createQueryBuilder();
            
            $EvaluacionDataRespondida= $entity->select("a,q")
                ->from("App\Entity\Evaluacion\Evaluacion","a")
                ->innerJoin('a.evaluacionUsuarios', 'q')
                ->Where('q.respondida=1')
                ->andWhere('a.id='.$valor->getId())
                ->orderBy('a.id', 'ASC')
                ->getQuery()
                ->getResult();

            $EvaluacionDataNoRespondida= $entity->select("b,h")
                ->from("App\Entity\Evaluacion\Evaluacion","b")
                ->innerJoin('b.evaluacionUsuarios', 'h')
                ->Where('h.respondida=0')
                ->andWhere('b.id='.$valor->getId())
                ->orderBy('b.id', 'ASC')
                ->getQuery()
                ->getResult();

            $sqlUser = " SELECT count(*) as total FROM evaluacion a INNER JOIN evaluacion_usuario b 
            ON a.id = b.id_evaluacion_id WHERE b.respondida = 0 AND a.id = ".$valor->getId()  ;
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sqlUser);
            $stmt->execute();
            $dataUserCount=$stmt->fetchAll();
        


            if($evaluacionDto->publicar==1){
                $evaluacionDto->editable=0;
            }

            if(count($EvaluacionDataRespondida)>0){
                $evaluacionDto->editable=0;
            }

            $evaluacionDto->userIfEvaluating=$dataUserCount[0]["total"];


            $dataevaluacion[]=$evaluacionDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$dataevaluacion);
 
    }

    public function findAllPage($data){

        $entityManagerDefault = $this->getEntityManager();
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());


        $entity= $this->getEntityManager()->createQueryBuilder();

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
       
        $query= $this->createQueryBuilder('a');
        $query->orderBy('a.id', 'ASC');
        if($data['word']!=null){
            $query->where("a.nombre like '%".$data['word']."%' and a.idempresa = ".$empresa->getId()." "); 
        }else{
            $query->where("a.idempresa = ".$empresa->getId());
        }


        $query->orderBy('a.id', 'ASC');   
        $query->getQuery();

        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery()	
      	->setFirstResult($data['rowByPage'] *($data['page']-1))	
      	->setMaxResults($data['rowByPage']);	
        $dataUser=array();
        $hijos=[];
        $rolesUser=[];
        $dataevaluacion=array();
        foreach($paginator as $clave=>$valor){
            $evaluacionDto =new EvaluacionOutPutDto();
            $evaluacionDto->id=$valor->getId();
            $evaluacionDto->nombre=$valor->getNombre();
            $evaluacionDto->descripcion=$valor->getDescripcion();
            $evaluacionDto->idTipoUnidad=($valor->getIdTipoUnidad()!=null)?array("id"=>$valor->getIdTipoUnidad()->getId(),"Nombre"=>$valor->getIdTipoUnidad()->getNombre(),"Factor"=>$valor->getIdTipoUnidad()->getFactor()):[];
            $evaluacionDto->unidad=$valor->getUnidad();
            $evaluacionDto->path=$valor->getPath();
            $evaluacionDto->orden=$valor->getOrden();;

            //$evaluacionDto->fechaPublicacion=$valor->getFechaPublicacion()->format("Y-m-d");
            $evaluacionDto->fechaPublicacion=!is_null($valor->getFechaPublicacion())?$valor->getFechaPublicacion()->format("Y-m-d"):null;
            $evaluacionDto->createAt=!is_null($valor->getCreateAt())?$valor->getCreateAt()->format("Y-m-d"):null;
            //$evaluacionDto->createAt=$valor->getCreateAt()->format("Y-m-d");
            $evaluacionDto->fechaVigencia=$valor->getFechaVigencia()->format("Y-m-d");
            $evaluacionDto->publicar=$valor->getPublicar();
            $evaluacionDto->editable=1;

            $entity= $this->getEntityManager()->createQueryBuilder();
            $EvaluacionData= $entity->select("a,q")
                ->from("App\Entity\Evaluacion\Evaluacion","a")
                ->innerJoin('a.evaluacionUsuarios', 'q')
                ->Where('q.respondida=1')
                ->andWhere('a.id='.$valor->getId())
                ->orderBy('a.id', 'ASC')
                ->getQuery()
                ->getResult();
            if($evaluacionDto->publicar==1){
                $evaluacionDto->editable=0;
            }

            if(count($EvaluacionData)>0){
                $evaluacionDto->editable=0;
            }

            $dataevaluacion[]=$evaluacionDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$dataevaluacion);
 
    }


    public function findList()
    {

        $data= $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $user=array();
        $dataevaluacion=array();
        foreach($data as $clave=>$valor){
            $evaluacionDto =new EvaluacionOutPutDto;
            $evaluacionDto->id=$valor->getId();
            $evaluacionDto->nombre=$valor->getNombre();
            $evaluacionDto->descripcion=$valor->getDescripcion();
            $evaluacionDto->idTipoUnidad=$valor->getIdTipoUnidad();
            $evaluacionDto->unidad=$valor->getUnidad();
            $evaluacionDto->path=$valor->getPath();
            $evaluacionDto->puntosGlobales= $valor->getPuntosGlobales();
            $dataevaluacion[]=$evaluacionDto;
        }
            return array("data"=>$dataevaluacion);
     }

      /**
     * Create Evaluacion.
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $entityManagerEvaluacion = $this->getEntityManager();

        $entity = new Evaluacion();
        $entity->setNombre(!is_null($data["name"])?$data["name"]:null);
        $entity->setDuration(!is_null($data["dutation"])?$data["dutation"]:null);
        $entity->setIdTipoUnidad(!is_null($data["unitType"])?$entityManager->getRepository(TipoUnidad::class)->find($data["unitType"]["id"]):null);
        $entity->setQuestionsByCategory(!is_null($data["questionsByCategory"])?$data["questionsByCategory"]:null);
        $entity->setPath(!is_null($data["path"])?$data["path"]:null);
        $entity->setFechaVigencia(!is_null($data["expirationDate"])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data["expirationDate"] )))):null);
        $entity->setDescripcion(!is_null($data["description"])?$data["description"]:null);
        $entity->setPuntosGlobales(!is_null($data["puntosGlobales"])?$data["puntosGlobales"]:null);
        if($data["evaluatorUserId"]!=null){
            $evaluator =$entityManager->getRepository(User::class)->find($data["evaluatorUserId"]);
            $entity->setEvaluatorUserId($evaluator);
        }

        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);

        $entityStatus = $entityManager->getRepository(Status::class)->findOneById(1);          
        $entity->setStatusId($entityStatus); 
        $entity->setOrden(1);
        $entity->setPublicar(0);                
        if(isset($data["roles"])){
            foreach($data["roles"] as $valor){
                $rol= $entityManager->getRepository(Rol::class)->findOneBy(array("descripcion"=>$valor));
                if(!is_null($rol))
                    $entity->addRole($rol);    
            }
        }
     
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entityManager->persist($entity);
            $entityManager->flush();
                
            if(isset($data["sections"])){
                foreach($data["sections"] as $valor){
                    $seccion = new SeccionEvaluacion();
                    $seccion->setEvaluacion($entity);
                    $seccion->setNombre(!is_null($valor["name"])?$valor["name"]:null);
                    $seccion->setOrden(!is_null($valor["numberSection"])?$valor["numberSection"]:null);
                    $seccion->setUpdateAt(new \DateTime());
                    $seccion->setStatus($entityManager->getRepository(Status::class)->findOneById(1));          
                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                    $seccion->setUpdateBy($currentUser->getUserName());
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                       $seccion->setIdempresa($empresa);
                    $entityManager->persist($seccion);
                    $entityManager->flush();
                    foreach($valor["questions"] as $question){
                        $pregunta = new PreguntaEvaluacion();
                        $pregunta->setPregunta(!is_null($question["label"])?$question["label"]:null);
                        $pregunta->setOrden(!is_null($question["order"])?$question["order"]:null);
                        $pregunta->setIdInput(!is_null($question["inputType"])?$entityManager->getRepository(TipoInput::class)->find($question["inputType"]["id"]):null);
                        $pregunta->setObligatorio(!is_null($question["required"])?$question["required"]:null);
                        $pregunta->setPuntos(!is_null($question["score"])?$question["score"]:null);
                        $pregunta->setIdCategoria(!is_null($question["categoryId"])?$entityManager->getRepository(TipoCategoriaEvaluacion::class)->find($question["categoryId"]):null);
                        $pregunta->setIdEvaluacion($entity);
                        $pregunta->setSeccion($seccion);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                          $pregunta->setIdempresa($empresa);
                        $entityManager->persist($pregunta);
                        $entityManager->flush();    
                        if(isset($question["options"])){
                            foreach($question["options"] as $options){
                                $opciones = new OpcionesEvaluacion();
                                $opciones->setCorrecta(1);
                                $opciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                $opciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                if($data["puntosGlobales"]==1){
                                    $opciones->setPuntos(!is_null($options["score"])?$options["score"]:null);
                                }
                                $opciones->setIdPregunta($pregunta);
                                $opciones->setUpdateAt(new \DateTime());
                                $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                                $opciones->setUpdateBy($currentUser->getUserName());                                
                                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                if($empresa)
                                   $opciones->setIdempresa($empresa);
                                $entityManager->persist($opciones);
                                $entityManager->flush();    
                            }
                        }
                    }
                }
            }
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }

    /**
     * Buscar Evaluacion Captura.
     */

    public function findEvaluacionByCaptura($id){
        $entityManager = $this->getEntityManager();        
        $entity= $this->getEntityManager()->createQueryBuilder();
        $EvaluacionData= $entity->select("a,q,x")
            ->from("App\Entity\Evaluacion\Evaluacion","a")
            ->innerJoin('a.evaluacionUsuarios', 'q')
            ->innerJoin('q.idUser', 'x')
            ->andWhere('a.id='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
 
 
 
        $moduleData= $this->createQueryBuilder('a')
            ->Where('a.id='.$id)
            ->orderBy('a.nombre', 'ASC')
            ->getQuery()
            ->getResult();
        if (count($moduleData)==0) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $dataevaluacion=array();
        foreach($moduleData as $valor){
            $instrumentocapDto =new EvaluacionOutPutDto();
            $instrumentocapDto->id=$valor->getId();
            $instrumentocapDto->nombre=$valor->getNombre();
            $instrumentocapDto->descripcion=$valor->getDescripcion();
            $instrumentocapDto->idTipoUnidad=$valor->getIdTipoUnidad();
            $instrumentocapDto->unidad=$valor->getUnidad();
            $dataevaluacion[]=$instrumentocapDto;
        }
        return new JsonResponse($dataevaluacion,200);  
    }

    public function findById($id){
        $entityManager = $this->getEntityManager();        
        $entity= $this->getEntityManager()->createQueryBuilder();
        $EvaluacionData= $entity->select("a,q,x,f,r")
            ->from("App\Entity\Evaluacion\Evaluacion","a")
            ->leftJoin('a.evaluacionUsuarios', 'q')
            ->leftJoin('q.idUser', 'x')
            ->leftjoin('a.seccions', 'f')
            ->leftJoin('a.evaluatorUserId', 'r')
            ->andWhere('a.id='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();

            $dataEvaluacion=null;
        foreach($EvaluacionData as $clave=>$valor){
            $instrumentoDto =new EvaluacionOutPutDto();
            $instrumentoDto->id=$valor->getId();
            $instrumentoDto->nombre=$valor->getNombre();
            $instrumentoDto->descripcion=$valor->getDescripcion();
            $instrumentoDto->duracion=$valor->getDuration();
            $instrumentoDto->publicar=!is_null($valor->getPublicar())?$valor->getPublicar():0;
            $instrumentoDto->questionsByCategory= !is_null($valor->getQuestionsByCategory())?$valor->getQuestionsByCategory():0;
            $instrumentoDto->unidad=$valor->getUnidad();
            $instrumentoDto->path=$valor->getPath();
            $instrumentoDto->idTipoUnidad=($valor->getIdTipoUnidad()!=null)?array("id"=>$valor->getIdTipoUnidad()->getId(),"Descripcion"=>$valor->getIdTipoUnidad()->getNombre()):[];
            $instrumentoDto->statusId=($valor->getStatusId()!=null)?array("id"=>$valor->getStatusId()->getId(),"Descripcion"=>$valor->getStatusId()->getDescripcion()):[];
            $instrumentoDto->puntosGlobales= $valor->getPuntosGlobales();
            $instrumentoDto->evaluatorUserId=($valor->getEvaluatorUserId()!=null)?$valor->getEvaluatorUserId()->getId():null;
            $instrumentoDto->evaluatorFullName=($valor->getEvaluatorUserId()!=null)?$valor->getEvaluatorUserId()->getPrimerNombre()." ".$valor->getEvaluatorUserId()->getSegundoNombre()." ".$valor->getEvaluatorUserId()->getPrimerApellido():null;
            $instrumentoDto->evaluatorEmail=($valor->getEvaluatorUserId()!=null)?$valor->getEvaluatorUserId()->getEmail():null;
             

            if($valor->getFechaPublicacion()!=null){
                $instrumentoDto->fechaPublicacion=$valor->getFechaPublicacion()->format("Y-m-d");
            }    
            if($valor->getFechaVigencia()!=null){
                $instrumentoDto->fechaVigencia=$valor->getFechaVigencia()->format("Y-m-d");
            }    

            if($valor->getCreateAt()!=null){
                $instrumentoDto->createAt=$valor->getCreateAt()->format("Y-m-d");
            }    
            $instrumentoDto->updateBy=$valor->getUpdateBy();
            if($valor->getUpdateAt()!=null){           
                 $instrumentoDto->updateAt=$valor->getUpdateAt()->format("d/m/Y");
            }    

            $roles=[];
            foreach($valor->getRoles() as $valorRol){
                $roles[]= $valorRol->getDescripcion();            
            }
            $instrumentoDto->roles=trim(json_encode($roles),'"');
            $instrumentoDto->roles=$roles;
            $usersData=[];
            $editable=1;
            if($instrumentoDto->publicar==1){
                $editable=0;
            }
            if($valor->getEvaluacionUsuarios()!=null){
                foreach($valor->getEvaluacionUsuarios() as $instrumentosuser){
                        if($instrumentosuser->getRespondida()==1){
                            $editable=0;
                        }
                        $usersData[]=array("id"=>$instrumentosuser->getIdUser()->getId(),"nombre"=>$instrumentosuser->getIdUser()->getPrimerNombre(). " ".$instrumentosuser->getIdUser()->getPrimerApellido(),"email"=>$instrumentosuser->getIdUser()->getEmail()
                        ,"respondida"=>$instrumentosuser->getRespondida(),"roles"=>$instrumentosuser->getIdUser()->getRoles());                       
                }
            }  
            $instrumentoDto->editable=$editable;
            $instrumentoDto->users=$usersData;
            //$instrumentoDto->pregunta=$entityManager->getRepository(Pregunta::class)->findByIdEvaluacion($id);
            if($valor->getCreateAt()!=null){
                $instrumentoDto->createAt=$valor->getCreateAt()->format("d/m/Y");
            }    
            $instrumentoDto->updateBy=$valor->getUpdateBy();
            if($valor->getUpdateAt()!=null){           
                 $instrumentoDto->updateAt=$valor->getUpdateAt()->format("d/m/Y");
            }
            $instrumentoDto->createBy=$valor->getCreateBy();
            $secciones=array();
            if($valor->getSeccions()!=null){
                foreach($valor->getSeccions() as $seccion){
                    $preguntas= $entityManager->getRepository(PreguntaEvaluacion::class)->findByIdEvaluacionAndSeccion($id,$seccion->getId());
                    $secciones[]=array(
                        "id"=>$seccion->getId(),
                        "nombre"=>$seccion->getNombre(),  
                        "orden"=>$seccion->getOrden(),
                        "preguntas"=>$preguntas);
                } 
            }
            $instrumentoDto->secciones=$secciones;
            $dataEvaluacion[]=$instrumentoDto;              
        }
       return new JsonResponse(['data'=>$dataEvaluacion],200);
    }

    
    /**
     * Update Evaluacion Captura.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {

        $entityManagerSeccion = $this->getEntityManager();

        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Evaluacion::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $entity->setNombre(!is_null($data["name"])?$data["name"]:null);
        $entity->setDuration(!is_null($data["dutation"])?$data["dutation"]:null);
        $entity->setIdTipoUnidad(!is_null($data["unitType"])?$entityManager->getRepository(TipoUnidad::class)->find($data["unitType"]["id"]):null);
        $entity->setQuestionsByCategory(!is_null($data["questionsByCategory"])?$data["questionsByCategory"]:null);
        $entity->setPuntosGlobales(!is_null($data["puntosGlobales"])?$data["puntosGlobales"]:null);
        $entity->setPath(!is_null($data["path"])?$data["path"]:null);
        $entity->setFechaVigencia(!is_null($data["expirationDate"])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data["expirationDate"] )))):null);
        $entity->setDescripcion(!is_null($data["description"])?$data["description"]:null);
        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
         if($empresa)
            $entity->setIdempresa($empresa);
        $entityStatus = $entityManager->getRepository(Status::class)->findOneById(1);          
        $entity->setStatusId($entityStatus); 
        //$entity->setPublicar(0); 
        if($data["evaluatorUserId"]!=null){
            $evaluator =$entityManager->getRepository(User::class)->find($data["evaluatorUserId"]);
            $entity->setEvaluatorUserId($evaluator);
        }
  
        if(isset($data["roles"])){
            foreach($entity->getRoles() as $rol){               
                $entity->removeRole($rol);
              //  $entityManager->persist($entity);
                $entityManager->flush();
            }    
            foreach($data["roles"] as $valor){
                $rol= $entityManager->getRepository(Rol::class)->findOneBy(array("descripcion"=>$valor));
                if(!is_null($rol)){
                    $entity->addRole($rol); 
                }
   
            }
        }
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return new JsonResponse($messages,500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entityManager->flush();
            //$this->putEvaluacionAsignacionUsuarios($data,$entity);
            $this->putSecciones($data,$id);
            $conn = $this->getEntityManager()->getConnection();
            
            // foreach($entity->getSeccions() as $seccion){               
                   
            //       $sql = " delete from seccion where id = ".$seccion->getId();
            //       $stmt = $conn->prepare($sql);
            //       $stmt->execute();
                  
                    
            //     }
            // if(isset($data["sections"])){
            //     foreach($data["sections"] as $valor){
                    
            //         $seccion = new Seccion();
            //         $seccion->setEvaluacion($entity);
            //         $seccion->setNombre(!is_null($valor["name"])?$valor["name"]:null);
            //         $seccion->setOrden(!is_null($valor["numberSection"])?$valor["numberSection"]:null);
            //         $seccion->setUpdateAt(new \DateTime());
            //         $seccion->setStatus($entityManager->getRepository(Status::class)->findOneById(1));          
            //         $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            //         $seccion->setUpdateBy($currentUser->getUserName());
            //         $entityManagerSeccion->persist($seccion);
            //         $entityManagerSeccion->flush();
            //         foreach($valor["questions"] as $question){    
            //             $pregunta = new Pregunta();
            //             $pregunta->setPregunta(!is_null($question["label"])?$question["label"]:null);
            //             $pregunta->setOrden(!is_null($question["order"])?$question["order"]:null);
            //             $pregunta->setIdInput(!is_null($question["inputType"])?$entityManager->getRepository(TipoInput::class)->find($question["inputType"]["id"]):null);
            //             $pregunta->setObligatorio(!is_null($question["required"])?$question["required"]:null);
            //             $pregunta->setPuntos(!is_null($question["score"])?$question["score"]:null);
            //             $pregunta->setIdCategoria(!is_null($question["categoryId"])?$entityManager->getRepository(TipoCategoria::class)->find($question["categoryId"]):null);
            //             $pregunta->setIdEvaluacion($entity);
            //             $pregunta->setSeccion($seccion);
            //             $entityManager->persist($pregunta);
            //             $entityManager->flush();    
            //             if(isset($question["options"])){
                             
            //                 foreach($question["options"] as $options){
            //                     $opciones = new Opciones();
            //                     $opciones->setCorrecta(1);
            //                     $opciones->setNombre(!is_null($options["label"])?$options["label"]:null);
            //                     $opciones->setValor(!is_null($options["value"])?$options["value"]:null);
            //                     $opciones->setPuntos(!is_null($options["score"])?$options["score"]:null);
            //                     $opciones->setIdPregunta($pregunta);
            //                     $opciones->setUpdateAt(new \DateTime());
            //                     $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            //                     $opciones->setUpdateBy($currentUser->getUserName());                                
            //                     $entityManager->persist($opciones);
            //                     $entityManager->flush();    
            //                 }
            //             }
            //         }
            //     }
            // }
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }
    }

    function putEvaluacionAsignacionUsuarios($data,$entity){
        $entityManagerEvaluacion = $this->getEntityManager();
        $entityManager = $this->getEntityManager();
        $encontro=false;        
        if(isset($data["users"])){               
            foreach($entity->getEvaluacionUsuarios() as $instrusuarios){               
                foreach($data["users"] as $valor){
                    if($valor["userId"]==$instrusuarios->getIdUser()->getId()){
                        $encontro=true;
                    }
                }               
                if($encontro==false){
                    if($instrusuarios->getRespondida()!=1){
                        $entity->removeEvaluacionUsuario($instrusuarios);
                        $entityManager->persist($entity);
                        $entityManager->flush();
                    }
                }
                $encontro=false;
            }    
            foreach($data["users"] as $valor){
                $user= $entityManager->getRepository(User::class)->find($valor["userId"]);
                if($user){
                    $userEvaluacion= $entityManager->getRepository(EvaluacionUsuario::class)->findBy(array("idUser"=>$valor["userId"],
                    "IdEvaluacion"=>$entity->getId()));
                    if($userEvaluacion==null){
                        $instrumentoUsuario = new EvaluacionUsuario();
                        $instrumentoUsuario->setIdEvaluacion($entity);
                        $instrumentoUsuario->setIdUser($user);
                        $instrumentoUsuario->setRespondida(0);
                        $instrumentoUsuario->setFechaAsignacion(new \DateTime());
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $instrumentoUsuario->setIdempresa($empresa);
                        $entityManagerEvaluacion->persist($instrumentoUsuario);
                        $entityManagerEvaluacion->flush();
                    }
                }
            }
        }
    }


    function putSecciones($data,$id){
        $entityManager = $this->getEntityManager();
        $entityEvaluacion =$entityManager->getRepository(Evaluacion::class)->find($id);

        if(isset($data["sections"])){
            foreach($data["sections"] as $valor){
                if(isset($valor["id"])){
                    $entitySeccion =$entityManager->getRepository(SeccionEvaluacion::class)->find($valor["id"]);
                    if($entitySeccion!=null){
                        $entitySeccion->setNombre($valor["name"]);
                        $entitySeccion->setOrden($valor["numberSection"]);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                          $entitySeccion->setIdempresa($empresa);
                        $entityManager->flush();
                        foreach($valor["questions"] as $preguntas){
                            if(isset($preguntas["id"])){
                                $entity =$entityManager->getRepository(PreguntaEvaluacion::class)->find($preguntas["id"]);
                                if($entity!=null){
                                    $entity->setPregunta(!is_null($preguntas["label"])?$preguntas["label"]:null);
                                    $entity->setOrden(!is_null($preguntas["order"])?$preguntas["order"]:null);
                                    $entity->setIdInput(!is_null($preguntas["inputType"])?$entityManager->getRepository(TipoInput::class)->find($preguntas["inputType"]["id"]):null);
                                    if($preguntas["inputType"]["id"]!=$entity->getIdInput()->getId()){
                                        $TipoInput= $entityManager->getRepository(TipoInput::class)->find($preguntas["inputType"]["id"]);
                                        if($TipoInput!=null){                                            
                                            if($TipoInput->getSeleccionMultiple()==0){
                                                foreach($entity->getOpciones() as $opciones){
                                                    $entityManager->remove($opciones);
                                                    $entityManager->flush(); 
                                                }                                           
                                            }       
                                        } 
                                    }
                                    $entity->setObligatorio(!is_null($preguntas["required"])?$preguntas["required"]:null);
                                    $entity->setPuntos(!is_null($preguntas["score"])?$preguntas["score"]:null);
                                    $entity->setIdCategoria(!is_null($preguntas["categoryId"])?$entityManager->getRepository(TipoCategoriaEvaluacion::class)->find($preguntas["categoryId"]):null);
                                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                    if($empresa)
                                       $entity->setIdempresa($empresa);
                                    $entityManager->flush();
                                    if(isset($preguntas["options"])){
                                        foreach($preguntas["options"] as $options){
                                            if(isset($options["id"])){
                                                $entityOpciones =$entityManager->getRepository(OpcionesEvaluacion::class)->find($options["id"]);
                                                if($entityOpciones!=null){
                                                    $entityOpciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                                    $entityOpciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                                    $entityOpciones->setPuntos(!is_null($options["score"])?$options["score"]:null);
                                                    $entityOpciones->setUpdateAt(new \DateTime());
                                                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                                                    $entityOpciones->setUpdateBy($currentUser->getUserName());                                
                                                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                                    if($empresa)
                                                       $entityOpciones->setIdempresa($empresa);
                                                    $entityManager->flush();
                                                }
                                            }else{
                                                    $entityOpciones = new OpcionesEvaluacion();
                                                    $entityOpciones->setCorrecta(1);
                                                    $entityOpciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                                    $entityOpciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                                    $entityOpciones->setPuntos(!is_null($options["score"])?$options["score"]:null);
                                                    $entityOpciones->setIdPregunta($entity);
                                                    $entityOpciones->setUpdateAt(new \DateTime());
                                                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                                                    $entityOpciones->setUpdateBy($currentUser->getUserName());                                
                                                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                                    if($empresa)
                                                       $entityOpciones->setIdempresa($empresa);
                                                    $entityManager->persist($entityOpciones);
                                                    $entityManager->flush();   
                                            }
                                            if(isset($options["scoreBycharges"])){
                                                $sql = " select * from opciones_cargo where opcion_id  = " . $options["id"];
                                                $opciones = [];
                                                $conn = $this->getEntityManager()->getConnection();
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $entityOpcionesCargo= $stmt->fetchAll();
                                                if($entityOpcionesCargo!=null){                                            
                                                    foreach($entityOpcionesCargo as $opciones){
                                                        $entityManager->remove($opciones);
                                                        $entityManager->flush(); 
                                                    }                                                
                                                } 
                                            }
        
                                        }
                                    }
                                }
                            }else{
                                $pregunta = new PreguntaEvaluacion();
                                $pregunta->setPregunta(!is_null($preguntas["label"])?$preguntas["label"]:null);
                                $pregunta->setOrden(!is_null($preguntas["order"])?$preguntas["order"]:null);
                                $pregunta->setIdInput(!is_null($preguntas["inputType"])?$entityManager->getRepository(TipoInput::class)->find($preguntas["inputType"]["id"]):null);
                                $pregunta->setObligatorio(!is_null($preguntas["required"])?$preguntas["required"]:null);
                                $pregunta->setPuntos(!is_null($preguntas["score"])?$preguntas["score"]:null);
                                $pregunta->setIdCategoria(!is_null($preguntas["categoryId"])?$entityManager->getRepository(TipoCategoriaEvaluacion::class)->find($preguntas["categoryId"]):null);
                                $pregunta->setIdEvaluacion($entityEvaluacion);
                                $pregunta->setSeccion($entitySeccion);
                                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                if($empresa)
                                   $pregunta->setIdempresa($empresa);
                                $entityManager->persist($pregunta);
                                $entityManager->flush();    
                                if(isset($preguntas["options"])){
                                    foreach($preguntas["options"] as $options){
                                            $entityOpciones = new OpcionesEvaluacion();
                                            $entityOpciones->setCorrecta(1);
                                            $entityOpciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                            $entityOpciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                            $entityOpciones->setPuntos(!is_null($options["score"])?$options["score"]:null);
                                            $entityOpciones->setIdPregunta($pregunta);
                                            $entityOpciones->setUpdateAt(new \DateTime());
                                            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                            if($empresa)
                                                $entityOpciones->setIdempresa($empresa);
                                            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                                            $entityOpciones->setUpdateBy($currentUser->getUserName());                                
                                            $entityManager->persist($entityOpciones);
                                            $entityManager->flush();                                        
                                    }
                                }   
                            }
  
                        }
                    }
 
                }else{
                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                    $entitySeccion = new SeccionEvaluacion();                   
                    $entitySeccion->setNombre($valor["name"]);
                    $entitySeccion->setOrden($valor["numberSection"]);
                    $entitySeccion->setEvaluacion($entityEvaluacion);
                    $entitySeccion->setUpdateBy($currentUser->getUserName());
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                       $entitySeccion->setIdempresa($empresa);
                    $entityManager->persist($entitySeccion);
                    $entityManager->flush();
                    foreach($valor["questions"] as $preguntas){
                        $pregunta = new PreguntaEvaluacion();
                        $pregunta->setPregunta(!is_null($preguntas["label"])?$preguntas["label"]:null);
                        $pregunta->setOrden(!is_null($preguntas["order"])?$preguntas["order"]:null);
                        $pregunta->setIdInput(!is_null($preguntas["inputType"])?$entityManager->getRepository(TipoInput::class)->find($preguntas["inputType"]["id"]):null);
                        $pregunta->setObligatorio(!is_null($preguntas["required"])?$preguntas["required"]:null);
                        $pregunta->setPuntos(!is_null($preguntas["score"])?$preguntas["score"]:null);
                        $pregunta->setIdCategoria(!is_null($preguntas["categoryId"])?$entityManager->getRepository(TipoCategoriaEvaluacion::class)->find($preguntas["categoryId"]):null);
                        $pregunta->setIdEvaluacion($entityEvaluacion);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $pregunta->setIdempresa($empresa);
                        $pregunta->setSeccion($entitySeccion);
                        $entityManager->persist($pregunta);
                        $entityManager->flush();
                        if(isset($preguntas["options"])){
                            foreach($preguntas["options"] as $options){
                                    $entityOpciones = new OpcionesEvaluacion();
                                    $entityOpciones->setCorrecta(1);
                                    $entityOpciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                    $entityOpciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                    $entityOpciones->setPuntos(!is_null($options["score"])?$options["score"]:null);
                                    $entityOpciones->setIdPregunta($pregunta);
                                    $entityOpciones->setUpdateAt(new \DateTime());
                                    $entityOpciones->setUpdateBy($currentUser->getUserName());                                
                                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                    if($empresa)
                                      $entityOpciones->setIdempresa($empresa);
                                    $entityManager->persist($entityOpciones);
                                    $entityManager->flush();                                        
                            }
                        }                  
                    }
                }
            }
        }

    }



    /**
     * Update Evaluacion Captura.
     */
    public function putOld($data,$id,$validator,$helper): JsonResponse  
    {

        $entityManagerEvaluacion = $this->getEntityManager();

        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Evaluacion::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $entity->setNombre(!is_null($data["name"])?$data["name"]:null);
        $entity->setDuration(!is_null($data["dutation"])?$data["dutation"]:null);
        $entity->setIdTipoUnidad(!is_null($data["unitType"])?$entityManager->getRepository(TipoUnidad::class)->find($data["unitType"]["id"]):null);
        $entity->setQuestionsByCategory(!is_null($data["questionsByCategory"])?$data["questionsByCategory"]:null);
        $entity->setPath(!is_null($data["path"])?$data["path"]:null);
        $entity->setFechaVigencia(!is_null($data["expirationDate"])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data["expirationDate"] )))):null);
        $entity->setDescripcion(!is_null($data["description"])?$data["description"]:null);
        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);
        $entityStatus = $entityManager->getRepository(Status::class)->findOneById(1);          
        $entity->setStatusId($entityStatus); 
        $entity->setPublicar(0); 
       
        if(isset($data["roles"])){
            foreach($entity->getRoles() as $rol){               
                $entity->removeRole($rol);
              //  $entityManager->persist($entity);
                $entityManager->flush();
            }    
            foreach($data["roles"] as $valor){
                $rol= $entityManager->getRepository(Rol::class)->findOneBy(array("descripcion"=>$valor));
                if(!is_null($rol)){
                    $entity->addRole($rol); 
                }
   
            }
        }
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return new JsonResponse($messages,500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            //$entity->setIdStatus($entityManager->getRepository(Status::class)->find(1)); 
            $entityManager->flush();
            if(isset($data["users"])){
                
                foreach($entity->getEvaluacionUsuarios() as $instrusuarios){               
                    $entity->removeEvaluacionUsuario($instrusuarios);
                    $entityManager->persist($entity);
                    $entityManager->flush();
                }    
                
                foreach($data["users"] as $valor){
                    $instrumentoUsuario = new EvaluacionUsuario();
                    $instrumentoUsuario->setIdEvaluacion($entity);
                    $user= $entityManager->getRepository(User::class)->find($valor["userId"]);
                    $instrumentoUsuario->setIdUser($user);
                    $instrumentoUsuario->setRespondida(0);
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                       $instrumentoUsuario->setIdempresa($empresa);
                    $entityManagerEvaluacion->persist($instrumentoUsuario);
                    $entityManagerEvaluacion->flush();
                }
            }    
            $conn = $this->getEntityManager()->getConnection();
   
            foreach($entity->getSeccions() as $seccion){               
                   
                  $sql = " delete from seccion where id = ".$seccion->getId();
                  $stmt = $conn->prepare($sql);
                  $stmt->execute();
                  
                    
                }
            if(isset($data["sections"])){
                foreach($data["sections"] as $valor){
                    
                    $seccion = new SeccionEvaluacion();
                    $seccion->setEvaluacion($entity);
                    $seccion->setNombre(!is_null($valor["name"])?$valor["name"]:null);
                    $seccion->setOrden(!is_null($valor["numberSection"])?$valor["numberSection"]:null);
                    $seccion->setUpdateAt(new \DateTime());
                    $seccion->setStatus($entityManager->getRepository(Status::class)->findOneById(1));          
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                       $seccion->setIdempresa($empresa);
                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                    $seccion->setUpdateBy($currentUser->getUserName());
                    $entityManagerSeccion->persist($seccion);
                    $entityManagerSeccion->flush();
                    foreach($valor["questions"] as $question){    
                        $pregunta = new PreguntaEvaluacion();
                        $pregunta->setPregunta(!is_null($question["label"])?$question["label"]:null);
                        $pregunta->setOrden(!is_null($question["order"])?$question["order"]:null);
                        $pregunta->setIdInput(!is_null($question["inputType"])?$entityManager->getRepository(TipoInput::class)->find($question["inputType"]["id"]):null);
                        $pregunta->setObligatorio(!is_null($question["required"])?$question["required"]:null);
                        $pregunta->setPuntos(!is_null($question["score"])?$question["score"]:null);
                        $pregunta->setIdCategoria(!is_null($question["categoryId"])?$entityManager->getRepository(TipoCategoria::class)->find($question["categoryId"]):null);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $pregunta->setIdempresa($empresa);
                        $pregunta->setIdEvaluacion($entity);
                        $pregunta->setSeccion($seccion);
                        $entityManager->persist($pregunta);
                        $entityManager->flush();    
                        if(isset($question["options"])){
                             
                            foreach($question["options"] as $options){
                                $opciones = new OpcionesEvaluacion();
                                $opciones->setCorrecta(1);
                                $opciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                $opciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                $opciones->setPuntos(!is_null($options["score"])?$options["score"]:null);
                                $opciones->setIdPregunta($pregunta);
                                $opciones->setUpdateAt(new \DateTime());
                                $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                                $opciones->setUpdateBy($currentUser->getUserName());                                
                                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                if($empresa)
                                  $opciones->setIdempresa($empresa);
                                $entityManager->persist($opciones);
                                $entityManager->flush();    
                            }
                        }
                    }
                }
            }
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }
    }



    
    /**
     * add User to Evaluacion Captura.
     */
    public function addUserToEvaluacion($data,$id,$validator,$helper): JsonResponse  
    {


        

        $entityManagerEvaluacion = $this->getEntityManager();

        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Evaluacion::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return new JsonResponse($messages,500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entityManager->flush();
            if(isset($data["users"])){
                foreach($data["users"] as $valor){
                    $userEvaluacion= $entityManager->getRepository(EvaluacionUsuario::class)->findBy(array("idUser"=>$valor["userId"],
                    "IdEvaluacion"=>$id));
                    if($userEvaluacion==null){
                        $instrumentoUsuario = new EvaluacionUsuario();
                        $instrumentoUsuario->setIdEvaluacion($entity);
                        $user= $entityManager->getRepository(User::class)->find($valor["userId"]);
                        $pais= $entityManager->getRepository(Pais::class)->find($data["paisId"]);
                        $estado= $entityManager->getRepository(Estado::class)->find($data["estadoId"]);
                        $instrumentoUsuario->setIdUser($user);
                        $instrumentoUsuario->setEstadoId($estado);
                        $instrumentoUsuario->setPaisId($pais);
                        $instrumentoUsuario->setRespondida(0);
                        $instrumentoUsuario->setFechaAsignacion(new \DateTime());
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $instrumentoUsuario->setIdempresa($empresa);
                        $entityManagerEvaluacion->persist($instrumentoUsuario);
                        $entityManagerEvaluacion->flush();
                    }
                }
            }    
            $conn = $this->getEntityManager()->getConnection();   
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }
    }


    /**
     * Delete Evaluacion Captura.
     */
    public function delete($id,$validator,$helper): JsonResponse  
    {

        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Evaluacion::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
       
        if ($entity->getPublicar()==1) {
            return new JsonResponse(['msg'=>'No puede eliminar un evaluacion publicado'],500);  
        }

        $entityManager->remove($entity);
        $entityManager->flush();

        return new JsonResponse(['msg'=>'Registro Eliminado: '.$id],200);
        
    }


    /**
     * Publicr Evaluacion Captura.
     */
    public function publicar($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Evaluacion::class)->find($id);
        $entity->setPublicar($data["publicar"]);
        if($data["publicar"]==1){
            $entity->setFechaPublicacion(new \DateTime());
        }

        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity->setUpdateBy($currentUser->getUserName());
        $entity->setUpdateAt(new \DateTime());
        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        if($empresa)
           $entity->setIdempresa($empresa);
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return new JsonResponse($messages,500);
        }else{
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }

    }

    public function clonar($id){
        $em = $this->getEntityManager();     
        $entityManager = $this->getEntityManager();   
        $entity= $this->getEntityManager()->createQueryBuilder();
        $entityEvaluacionUsuarios= $this->getEntityManager()->createQueryBuilder();
       
        $EvaluacionData= $entity->select("a,f,p,r")
            ->from("App\Entity\Evaluacion\Evaluacion","a")
            ->leftjoin('a.seccions', 'f')
            ->leftjoin('f.preguntas', 'p')
            ->leftjoin('p.opciones', 'r')
            ->andWhere('a.id='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
        $new_entity = clone $EvaluacionData[0];
        $new_entity->setFechaPublicacion(null);
        $new_entity->setFechaVigencia(new \DateTime('now +1 day'));
        $new_entity->setOrden(1);
        $new_entity->setPublicar(0);
        $em->persist($new_entity);
        $em->flush();

        $evaluacionUsuarios= $entityEvaluacionUsuarios->select("a")
            ->from("App\Entity\Evaluacion\EvaluacionUsuario","a")
            ->Where('a.IdEvaluacion='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
            if(count($evaluacionUsuarios)>=0){
                foreach($evaluacionUsuarios as $instrumentosUsuario){
                    $new_instrumentousuario = clone $instrumentosUsuario;
                    $new_instrumentousuario->setIdEvaluacion($new_entity);
                    $new_instrumentousuario->setFechaAsignacion(new \DateTime());
                    $new_instrumentousuario->setFechaInicio(null);
                    $new_instrumentousuario->setFechaFin(null);
                    $new_instrumentousuario->setRespondida(0);
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                       $new_instrumentousuario->setIdempresa($empresa);
                    $em->persist($new_instrumentousuario); 
                    $em->flush();   

                }
            }        
        if($EvaluacionData[0]->getSeccions()!=null){
            foreach($EvaluacionData[0]->getSeccions() as $valor){
                $new_entity_seccion = clone $valor;
                $new_entity->addSeccion($new_entity_seccion);
                $em->persist($new_entity_seccion);
                $em->flush();    
                $entity= $this->getEntityManager()->createQueryBuilder();   
                $preguntas= $entity->select("a")
                ->from("App\Entity\Evaluacion\PreguntaEvaluacion","a")
                ->Where('a.idEvaluacion='.$id)
                ->andWhere('a.seccion='.$valor->getId())
                ->orderBy('a.id', 'ASC')
                ->getQuery()
                ->getResult();
    
                if(count($preguntas)>=0){
                    foreach($preguntas as $question){
                        $new_entity_question = clone $question;
                        $new_entity_question->setIdEvaluacion($new_entity);
                        $new_entity_question->setSeccion($new_entity_seccion);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $new_entity_question->setIdempresa($empresa);
                        $em->persist($new_entity_question); 
                        $em->flush();   
 
                        $entity= $this->getEntityManager()->createQueryBuilder();   
                        $opciones= $entity->select("a")
                        ->from("App\Entity\Evaluacion\OpcionesEvaluacion","a")
                        ->Where('a.idPregunta='.$question->getId())
                        ->orderBy('a.id', 'ASC')
                        ->getQuery()
                        ->getResult();
                        //Aca
                        if($opciones!=null){
                            foreach($opciones as $options){
                                $new_entity_options = clone $options;
                                $new_entity_options->setIdPregunta($new_entity_question);
                                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                if($empresa)
                                   $new_entity_options->setIdempresa($empresa);
                                $em->persist($new_entity_options);
                                $em->flush();    
                            }
                        }
                    }
                }
            }
            return $new_entity->getId();
        }
        if($EvaluacionData[0]->getPreguntas()!=null){
 
            $entity= $this->getEntityManager()->createQueryBuilder();   
            $preguntas= $entity->select("a")
            ->from("App\Entity\Evaluacion\PreguntaEvaluacion","a")
            ->Where('a.idEvaluacion='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();

            if(count($preguntas)>=0){
                foreach($preguntas as $question){
                    if($question->getSeccion()=="NULL" || $question->getSeccion()==null){
                        $new_entity_question = clone $question;
                        $new_entity_question->setIdEvaluacion($new_entity);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $new_entity_question->setIdempresa($empresa);
                        $em->persist($new_entity_question); 
                        $em->flush();   

                        $entity= $this->getEntityManager()->createQueryBuilder();   
                        $opciones= $entity->select("a")
                        ->from("App\Entity\Evaluacion\OpcionesEvaluacion","a")
                        ->Where('a.idPregunta='.$question->getId())
                        ->orderBy('a.id', 'ASC')
                        ->getQuery()
                        ->getResult();
        
                        if($opciones!=null){
                            foreach($opciones as $options){
                                $new_entity_options = clone $options;
                                $new_entity_options->setIdPregunta($new_entity_question);
                                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                if($empresa)
                                   $new_entity_options->setIdempresa($empresa);
                                $em->persist($new_entity_options);
                                $em->flush();    
                            }
                        }
                    }
                }
            }

        }
        
    }





    public function iniciar($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();

        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $instrumentoUsuario =$entityManager->getRepository(EvaluacionUsuario::class)->findOneBy(array("IdEvaluacion"=>$id,"idUser"=>$currentUser->getId()));
        if (!$instrumentoUsuario) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id.' para el usuario '.$currentUser->getId() ],404);  
        }

        $fechaInicial= new \Datetime(date("Y-m-d H:i:s") );
        $instrumentoUsuario->setFechaInicio($fechaInicial);
        $entityManager->persist($instrumentoUsuario);
        $entityManager->flush();
        return new JsonResponse(['msg'=>'Registro Actualizado: '.$instrumentoUsuario->getId()],200);
    }

  /**
     * Publicr Evaluacion Captura.
     */
    public function changeOrder($data,$id,$order,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Evaluacion::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity->setOrden($order);
        $entity->setUpdateBy($currentUser->getUserName());
        $entity->setUpdateAt(new \DateTime());
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return new JsonResponse($messages,500);
        }else{
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }

    }

   /**
     * List Evaluacion Publicados.
     */
     public function findListPublicados()
    {

        $data= $this->createQueryBuilder('c')
            ->Where('c.publicar=1')
            ->andWhere("c.fechaVigencia>='".date("Y-m-d h:i:s")."'")
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $user=array();
        $dataevaluacion=array();
        foreach($data as $clave=>$valor){
            $evaluacionDto =new EvaluacionOutPutDto;
            $evaluacionDto->id=$valor->getId();
            $evaluacionDto->nombre=$valor->getNombre();
            $evaluacionDto->descripcion=$valor->getDescripcion();
            $evaluacionDto->idTipoUnidad=$valor->getIdTipoUnidad();
            $evaluacionDto->unidad=$valor->getUnidad();
            $evaluacionDto->path=$valor->getPath();
            $evaluacionDto->puntosGlobales= $valor->getPuntosGlobales();
            $dataevaluacion[]=$evaluacionDto;
        }
            return array("data"=>$dataevaluacion);
     }


}