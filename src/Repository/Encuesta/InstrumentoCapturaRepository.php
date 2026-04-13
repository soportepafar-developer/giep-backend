<?php

namespace App\Repository\Encuesta;

use App\Dto\Encuesta\InstrumentoCapturaaOutPutDto;
use App\Dto\Encuesta\InstrumentoCapturaUsersOutPutDto;
use App\Entity\Encuesta\InstrumentoCaptura;
use App\Entity\Encuesta\InstrumentoUsuario;
use App\Entity\Encuesta\Opciones;
use App\Entity\Encuesta\OpcionesCargo;
use App\Entity\Encuesta\Pregunta;
use App\Entity\Encuesta\TipoUnidad;
use App\Entity\Encuesta\Seccion;
use App\Entity\Encuesta\TipoCategoria;
use App\Entity\Encuesta\TipoInput;
use App\Entity\Status;
use App\Entity\Pais;
use App\Entity\Estado;
use App\Entity\Cargo;
use App\Entity\Rol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
Use App\Entity\Proyecto\Empresa;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

/**
 * @method InstrumentoCaptura|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstrumentoCaptura|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstrumentoCaptura[]    findAll()
 * @method InstrumentoCaptura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstrumentoCapturaRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, InstrumentoCaptura::class);
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
        $datainstrumentocap=array();
        foreach($paginator as $clave=>$valor){
            $instrumentocapturaDto =new InstrumentoCapturaaOutPutDto();
            $instrumentocapturaDto->id=$valor->getId();
            $instrumentocapturaDto->nombre=$valor->getNombre();
            $instrumentocapturaDto->descripcion=$valor->getDescripcion();
            $instrumentocapturaDto->idTipoUnidad=($valor->getIdTipoUnidad()!=null)?array("id"=>$valor->getIdTipoUnidad()->getId(),"Nombre"=>$valor->getIdTipoUnidad()->getNombre(),"Factor"=>$valor->getIdTipoUnidad()->getFactor()):[];
            $instrumentocapturaDto->unidad=$valor->getUnidad();
            $instrumentocapturaDto->path=$valor->getPath();
            $instrumentocapturaDto->orden=$valor->getOrden();;

            //$instrumentocapturaDto->fechaPublicacion=$valor->getFechaPublicacion()->format("Y-m-d");
            $instrumentocapturaDto->fechaPublicacion=!is_null($valor->getFechaPublicacion())?$valor->getFechaPublicacion()->format("Y-m-d"):null;
            $instrumentocapturaDto->createAt=!is_null($valor->getCreateAt())?$valor->getCreateAt()->format("Y-m-d"):null;
            //$instrumentocapturaDto->createAt=$valor->getCreateAt()->format("Y-m-d");
            $instrumentocapturaDto->fechaVigencia=$valor->getFechaVigencia()->format("Y-m-d");
            $instrumentocapturaDto->publicar=$valor->getPublicar();
            $instrumentocapturaDto->editable=1;

            $entity= $this->getEntityManager()->createQueryBuilder();
            $encuestaData= $entity->select("a,q")
                ->from("App\Entity\Encuesta\InstrumentoCaptura","a")
                ->innerJoin('a.instrumentoUsuarios', 'q')
                ->Where('q.respondida=1')
                ->andWhere('a.id='.$valor->getId())
                ->orderBy('a.id', 'ASC')
                ->getQuery()
                ->getResult();
            if($instrumentocapturaDto->publicar==1){
                $instrumentocapturaDto->editable=0;
            }

            if(count($encuestaData)>0){
                $instrumentocapturaDto->editable=0;
            }

            $datainstrumentocap[]=$instrumentocapturaDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$datainstrumentocap);
 
    }


    public function findList()
    {
        $entityManagerDefault = $this->getEntityManager();
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        $data= $this->createQueryBuilder('c')
            ->where('c.idempresa ='.$empresa->getId())
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $user=array();
        $datainstrumentocap=array();
        foreach($data as $clave=>$valor){
            $instrumentocapturaDto =new InstrumentoCapturaaOutPutDto;
            $instrumentocapturaDto->id=$valor->getId();
            $instrumentocapturaDto->nombre=$valor->getNombre();
            $instrumentocapturaDto->descripcion=$valor->getDescripcion();
            $instrumentocapturaDto->idTipoUnidad=$valor->getIdTipoUnidad();
            $instrumentocapturaDto->unidad=$valor->getUnidad();
            $instrumentocapturaDto->path=$valor->getPath();
            $instrumentocapturaDto->puntosGlobales= $valor->getPuntosGlobales();
            $datainstrumentocap[]=$instrumentocapturaDto;
        }
            return array("data"=>$datainstrumentocap);
     }
     
     /**
     * Lis InstrumentoCaptura Publicados.
     */

     public function findListAllPublicado()
    {
        $entityManagerDefault = $this->getEntityManager();
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        $data= $this->createQueryBuilder('c')
            ->Where('c.publicar=1')
            ->andWhere("c.fechaVigencia>='".date("Y-m-d h:i:s")."'")
            ->andWhere('c.idempresa ='.$empresa->getId())
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $user=array();
        $datainstrumentocap=array();
        foreach($data as $clave=>$valor){
            $instrumentocapturaDto =new InstrumentoCapturaaOutPutDto;
            $instrumentocapturaDto->id=$valor->getId();
            $instrumentocapturaDto->nombre=$valor->getNombre();
            $instrumentocapturaDto->descripcion=$valor->getDescripcion();
            $instrumentocapturaDto->idTipoUnidad=$valor->getIdTipoUnidad();
            $instrumentocapturaDto->unidad=$valor->getUnidad();
            $instrumentocapturaDto->path=$valor->getPath();
            $instrumentocapturaDto->puntosGlobales= $valor->getPuntosGlobales();
            $datainstrumentocap[]=$instrumentocapturaDto;
        }
            return array("data"=>$datainstrumentocap);
     }


      /**
     * Create InstrumentoCaptura.
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $entityManagerInstrumento = $this->getEntityManager();

        //        $entity=$helper->setParametersToEntity(new InstrumentoCaptura(),$data);
        $entity = new InstrumentoCaptura();
        $entity->setNombre(!is_null($data["name"])?$data["name"]:null);
        $entity->setDuration(!is_null($data["dutation"])?$data["dutation"]:null);
        $entity->setIdTipoUnidad(!is_null($data["unitType"])?$entityManager->getRepository(TipoUnidad::class)->find($data["unitType"]["id"]):null);
        $entity->setQuestionsByCategory(!is_null($data["questionsByCategory"])?$data["questionsByCategory"]:null);
        $entity->setPath(!is_null($data["path"])?$data["path"]:null);
        $entity->setFechaVigencia(!is_null($data["expirationDate"])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data["expirationDate"] )))):null);
        $entity->setDescripcion(!is_null($data["description"])?$data["description"]:null);
        $entity->setPuntosGlobales(!is_null($data["puntosGlobales"])?$data["puntosGlobales"]:null);
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
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);

            $entity->setCreateBy($currentUser->getUserName());
            //$entity->setIdStatus($entityManager->getRepository(Status::class)->find(1)); 
            $entityManager->persist($entity);
            $entityManager->flush();
            // if(isset($data["users"])){
            //     foreach($data["users"] as $valor){
            //         $instrumentoUsuario = new InstrumentoUsuario();
            //         $instrumentoUsuario->setFechaAsignacion(new \DateTime());
            //         $instrumentoUsuario->setIdInstrumento($entity);
            //         $user= $entityManager->getRepository(User::class)->find($valor["userId"]);
            //         $instrumentoUsuario->setIdUser($user);
            //         $instrumentoUsuario->setRespondida(0);
            //         $entityManagerInstrumento->persist($instrumentoUsuario);
            //         $entityManagerInstrumento->flush();
            //     }
            // }    
            if(isset($data["sections"])){
                foreach($data["sections"] as $valor){
                    $seccion = new Seccion();
                    $seccion->setInstrumento($entity);
                    $seccion->setNombre(!is_null($valor["name"])?$valor["name"]:null);
                    $seccion->setOrden(!is_null($valor["numberSection"])?$valor["numberSection"]:null);
                    $seccion->setUpdateAt(new \DateTime());
                    $seccion->setStatus($entityManager->getRepository(Status::class)->findOneById(1));          
                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                      $seccion->setIdempresa($empresa);

                    $seccion->setUpdateBy($currentUser->getUserName());
                    $entityManager->persist($seccion);
                    $entityManager->flush();
                    foreach($valor["questions"] as $question){
                        $pregunta = new Pregunta();
                        $pregunta->setPregunta(!is_null($question["label"])?$question["label"]:null);
                        $pregunta->setOrden(!is_null($question["order"])?$question["order"]:null);
                        $pregunta->setIdInput(!is_null($question["inputType"])?$entityManager->getRepository(TipoInput::class)->find($question["inputType"]["id"]):null);
                        $pregunta->setObligatorio(!is_null($question["required"])?$question["required"]:null);
                        $pregunta->setPuntos(!is_null($question["score"])?$question["score"]:null);
                        $pregunta->setIdCategoria(!is_null($question["categoryId"])?$entityManager->getRepository(TipoCategoria::class)->find($question["categoryId"]):null);
                        $pregunta->setIdInstrumento($entity);
                        $pregunta->setSeccion($seccion);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                         if($empresa)
                           $pregunta->setIdempresa($empresa);
                        $entityManager->persist($pregunta);
                        $entityManager->flush();    
                        if(isset($question["options"])){
                            foreach($question["options"] as $options){
                                $opciones = new Opciones();
                                $opciones->setCorrecta(1);
                                $opciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                $opciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                if($data["puntosGlobales"]==1){
                                    $opciones->setPuntos(!is_null($options["score"])?$options["score"]:null);
                                }
                                $opciones->setIdPregunta($pregunta);
                                $opciones->setUpdateAt(new \DateTime());
                                $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                if($empresa)
                                   $opciones->setIdempresa($empresa);
                                $opciones->setUpdateBy($currentUser->getUserName());                                
                                $entityManager->persist($opciones);
                                $entityManager->flush();    
                                if(isset($options["scoreBycharges"])){
                                    foreach($options["scoreBycharges"] as $optionsCargos){
                                        $opcionesCargos = new OpcionesCargo();
                                        $cargo =$entityManager->getRepository(Cargo::class)->find($optionsCargos["idCargo"]);
                                        $opcionesCargos->setIdCargo($cargo!=null?$cargo:null);
                                        $opcionesCargos->setOpcion($opciones);
                                        $opcionesCargos->setScore($optionsCargos["score"]);
                                        $opcionesCargos->setCreateBy($currentUser->getUsername());
                                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                        if($empresa)
                                           $opcionesCargos->setIdempresa($empresa);

                                        $entityManager->persist($opcionesCargos);
                                        $entityManager->flush();    
        
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }

    /**
     * Buscar Instrumento Captura.
     */

    public function findInstrumentoByCaptura($id){
        $entityManager = $this->getEntityManager();        
        $entity= $this->getEntityManager()->createQueryBuilder();
        $encuestaData= $entity->select("a,q,x")
            ->from("App\Entity\Encuesta\InstrumentoCaptura","a")
            ->innerJoin('a.instrumentoUsuarios', 'q')
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
        $datainstrumentocap=array();
        foreach($moduleData as $valor){
            $instrumentocapDto =new InstrumentoCapturaaOutPutDto();
            $instrumentocapDto->id=$valor->getId();
            $instrumentocapDto->nombre=$valor->getNombre();
            $instrumentocapDto->descripcion=$valor->getDescripcion();
            $instrumentocapDto->idTipoUnidad=$valor->getIdTipoUnidad();
            $instrumentocapDto->unidad=$valor->getUnidad();
            $datainstrumentocap[]=$instrumentocapDto;
        }
        return new JsonResponse($datainstrumentocap,200);  
    }

    public function findById($id){
        $entityManager = $this->getEntityManager();        
        $entity= $this->getEntityManager()->createQueryBuilder();
        $encuestaData= $entity->select("a,q,x,f")
            ->from("App\Entity\Encuesta\InstrumentoCaptura","a")
            ->leftJoin('a.instrumentoUsuarios', 'q')
            ->leftJoin('q.idUser', 'x')
            ->leftjoin('a.seccions', 'f')
            ->andWhere('a.id='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
        $dataInstrumento=null;
        foreach($encuestaData as $clave=>$valor){
            $instrumentoDto =new InstrumentoCapturaaOutPutDto();
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
            if($valor->getInstrumentoUsuarios()!=null){
                foreach($valor->getInstrumentoUsuarios() as $instrumentosuser){
                        if($instrumentosuser->getRespondida()==1){
                            $editable=0;
                        }
                        $usersData[]=array("id"=>$instrumentosuser->getIdUser()->getId(),"nombre"=>$instrumentosuser->getIdUser()->getPrimerNombre(). " ".$instrumentosuser->getIdUser()->getPrimerApellido(),"email"=>$instrumentosuser->getIdUser()->getEmail()
                        ,"respondida"=>$instrumentosuser->getRespondida(),"roles"=>$instrumentosuser->getIdUser()->getRoles());                       
                }
            }  
            $instrumentoDto->editable=$editable;
            $instrumentoDto->users=$usersData;
            //$instrumentoDto->pregunta=$entityManager->getRepository(Pregunta::class)->findByIdEncuesta($id);
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
                    $preguntas= $entityManager->getRepository(Pregunta::class)->findByIdEncuestaAndSeccion($id,$seccion->getId());
                    $secciones[]=array(
                        "id"=>$seccion->getId(),
                        "nombre"=>$seccion->getNombre(),  
                        "orden"=>$seccion->getOrden(),
                        "preguntas"=>$preguntas);
                } 
            }
            $instrumentoDto->secciones=$secciones;
            $dataInstrumento[]=$instrumentoDto;              
        }
       return new JsonResponse(['data'=>$dataInstrumento],200);
    }

    
    /**
     * Update Instrumento Captura.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {

        $entityManagerSeccion = $this->getEntityManager();

        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(InstrumentoCaptura::class)->find($id);
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
        $entityStatus = $entityManager->getRepository(Status::class)->findOneById(1);          
        $entity->setStatusId($entityStatus); 
        //$entity->setPublicar(0); 
       
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
            //$this->putInstrumentoAsignacionUsuarios($data,$entity);
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
            //         $seccion->setInstrumento($entity);
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
            //             $pregunta->setIdInstrumento($entity);
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

    function putInstrumentoAsignacionUsuarios($data,$entity){
        $entityManagerInstrumento = $this->getEntityManager();
        $entityManager = $this->getEntityManager();
        $encontro=false;        
        if(isset($data["users"])){               
            foreach($entity->getInstrumentoUsuarios() as $instrusuarios){               
                foreach($data["users"] as $valor){
                    if($valor["userId"]==$instrusuarios->getIdUser()->getId()){
                        $encontro=true;
                    }
                }               
                if($encontro==false){
                    if($instrusuarios->getRespondida()!=1){
                        $entity->removeInstrumentoUsuario($instrusuarios);
                        $entityManager->persist($entity);
                        $entityManager->flush();
                    }
                }
                $encontro=false;
            }    
            foreach($data["users"] as $valor){
                $user= $entityManager->getRepository(User::class)->find($valor["userId"]);
                if($user){
                    $userInstrumento= $entityManager->getRepository(InstrumentoUsuario::class)->findBy(array("idUser"=>$valor["userId"],
                    "IdInstrumento"=>$entity->getId()));
                    if($userInstrumento==null){
                        $instrumentoUsuario = new InstrumentoUsuario();
                        $instrumentoUsuario->setIdInstrumento($entity);
                        $instrumentoUsuario->setIdUser($user);
                        $instrumentoUsuario->setRespondida(0);
                        $instrumentoUsuario->setFechaAsignacion(new \DateTime());
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $instrumentoUsuario->setIdempresa($empresa);
                        $entityManagerInstrumento->persist($instrumentoUsuario);
                        $entityManagerInstrumento->flush();
                    }
                }
            }
        }
    }


    function putSecciones($data,$id){
        $entityManager = $this->getEntityManager();
        $entityInstrumento =$entityManager->getRepository(InstrumentoCaptura::class)->find($id);

        if(isset($data["sections"])){
            foreach($data["sections"] as $valor){
                if(isset($valor["id"])){
                    $entitySeccion =$entityManager->getRepository(Seccion::class)->find($valor["id"]);
                    if($entitySeccion!=null){
                        $entitySeccion->setNombre($valor["name"]);
                        $entitySeccion->setOrden($valor["numberSection"]);
                        $entityManager->flush();
                        foreach($valor["questions"] as $preguntas){
                            if(isset($preguntas["id"])){
                                $entity =$entityManager->getRepository(Pregunta::class)->find($preguntas["id"]);
                                if($entity!=null){
                                    $entity->setPregunta(!is_null($preguntas["label"])?$preguntas["label"]:null);
                                    $entity->setOrden(!is_null($preguntas["order"])?$preguntas["order"]:null);
                                    $entity->setIdInput(!is_null($preguntas["inputType"])?$entityManager->getRepository(TipoInput::class)->find($preguntas["inputType"]["id"]):null);
                                    if($preguntas["inputType"]["id"]!=$entity->getIdInput()->getId()){
                                        $tipoInput= $entityManager->getRepository(TipoInput::class)->find($preguntas["inputType"]["id"]);
                                        if($tipoInput!=null){                                            
                                            if($tipoInput->getSeleccionMultiple()==0){
                                                foreach($entity->getOpciones() as $opciones){
                                                    $entityManager->remove($opciones);
                                                    $entityManager->flush(); 
                                                }                                           
                                            }       
                                        } 
                                    }
                                    $entity->setObligatorio(!is_null($preguntas["required"])?$preguntas["required"]:null);
                                    $entity->setPuntos(!is_null($preguntas["score"])?$preguntas["score"]:null);
                                    $entity->setIdCategoria(!is_null($preguntas["categoryId"])?$entityManager->getRepository(TipoCategoria::class)->find($preguntas["categoryId"]):null);
                                    $entityManager->flush();
                                    $idOptions=null;
                                    if(isset($preguntas["options"])){
                                        foreach($preguntas["options"] as $options){
                                            if(isset($options["id"])){
                                                $idOptions=$options["id"];
                                                $entityOpciones =$entityManager->getRepository(Opciones::class)->find($options["id"]);
                                                if($entityOpciones!=null){
                                                    $entityOpciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                                    $entityOpciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                                    if(isset($options["scoreBycharges"]))
                                                        $entityOpciones->setPuntos(is_null($options["scoreBycharges"])?!is_null($options["score"])?$options["score"]:null:null);
                                                    $entityOpciones->setUpdateAt(new \DateTime());
                                                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                                                    $entityOpciones->setUpdateBy($currentUser->getUserName());                                
                                                    $entityManager->flush();
                                                }
                                            }else{
                                                    $entityOpciones = new Opciones();
                                                    $entityOpciones->setCorrecta(1);
                                                    $entityOpciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                                    $entityOpciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                                    $entityOpciones->setPuntos(is_null($options["scoreBycharges"])?!is_null($options["score"])?$options["score"]:null:null);
                                                    $entityOpciones->setIdPregunta($entity);
                                                    $entityOpciones->setUpdateAt(new \DateTime());
                                                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                                                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                                    if($empresa)
                                                       $entityOpciones->setIdempresa($empresa);
                                                    $entityOpciones->setUpdateBy($currentUser->getUserName());                                
                                                    $entityManager->persist($entityOpciones);
                                                    $entityManager->flush();   
                                                    $idOptions=$entityOpciones->getId();
                                            }
                                            if(isset($options["scoreBycharges"])){
                                                $sql = "SELECT * FROM opciones_cargo WHERE opcion_id = ". $idOptions;
                                                $conn = $this->getEntityManager()->getConnection();
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $entityOpcionesCargo = $stmt->fetchAll();
                                                
                                                if ($entityOpcionesCargo != null) {
                                                    $entityManager = $this->getEntityManager();
                                                    foreach ($entityOpcionesCargo as $opcion) {
                                                        $opcionEntity = $entityManager->getRepository(OpcionesCargo::class)->find($idOptions);
                                                        if ($opcionEntity) {
                                                            $entityManager->remove($opcionEntity);
                                                            $entityManager->flush();
                                                        }
                                                    }
                                                }
        
                                                foreach($options["scoreBycharges"] as $optionsCargos){
                                                    $opcionesCargos = new OpcionesCargo();
                                                    $cargo =$entityManager->getRepository(Cargo::class)->find($optionsCargos["idCargo"]);
                                                    $opcionesCargos->setIdCargo($cargo!=null?$cargo:null);
                                                    $opcionesCargos->setOpcion($entityOpciones);
                                                    $opcionesCargos->setScore($optionsCargos["score"]);
                                                    $opcionesCargos->setCreateBy($currentUser->getUsername());
                                                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                                    if($empresa)
                                                       $opcionesCargos->setIdempresa($empresa);
                                                    $entityManager->persist($opcionesCargos);
                                                    $entityManager->flush();                        
                                                }
                                            }

                                        }
                                    }
                                }
                            }else{
                                $pregunta = new Pregunta();
                                $pregunta->setPregunta(!is_null($preguntas["label"])?$preguntas["label"]:null);
                                $pregunta->setOrden(!is_null($preguntas["order"])?$preguntas["order"]:null);
                                $pregunta->setIdInput(!is_null($preguntas["inputType"])?$entityManager->getRepository(TipoInput::class)->find($preguntas["inputType"]["id"]):null);
                                $pregunta->setObligatorio(!is_null($preguntas["required"])?$preguntas["required"]:null);
                                $pregunta->setPuntos(!is_null($preguntas["score"])?$preguntas["score"]:null);
                                $pregunta->setIdCategoria(!is_null($preguntas["categoryId"])?$entityManager->getRepository(TipoCategoria::class)->find($preguntas["categoryId"]):null);
                                $pregunta->setIdInstrumento($entityInstrumento);
                                $pregunta->setSeccion($entitySeccion);
                                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                if($empresa)
                                   $pregunta->setIdempresa($empresa);

                                $entityManager->persist($pregunta);
                                $entityManager->flush();    
                                if(isset($preguntas["options"])){
                                    foreach($preguntas["options"] as $options){
                                            $entityOpciones = new Opciones();
                                            $entityOpciones->setCorrecta(1);
                                            $entityOpciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                            $entityOpciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                            $entityOpciones->setPuntos(is_null($options["scoreBycharges"])?!is_null($options["score"])?$options["score"]:null:null);
                                            $entityOpciones->setIdPregunta($pregunta);
                                            $entityOpciones->setUpdateAt(new \DateTime());
                                            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                                            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                            if($empresa)
                                            $entityOpciones->setIdempresa($empresa);

                                            $entityOpciones->setUpdateBy($currentUser->getUserName());                                
                                            $entityManager->persist($entityOpciones);
                                            $entityManager->flush(); 
                                            $idOptions=$entityOpciones->getId();
                                            if(isset($options["scoreBycharges"])){
                                                foreach($options["scoreBycharges"] as $optionsCargos){
                                                    $opcionesCargos = new OpcionesCargo();
                                                    $cargo =$entityManager->getRepository(Cargo::class)->find($optionsCargos["idCargo"]);
                                                    $opcionesCargos->setIdCargo($cargo!=null?$cargo:null);
                                                    $opcionesCargos->setOpcion($entityOpciones);
                                                    $opcionesCargos->setScore($optionsCargos["score"]);
                                                    $opcionesCargos->setCreateBy($currentUser->getUsername());
                                                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                                    if($empresa)
                                                       $opcionesCargos->setIdempresa($empresa);
                                                    $entityManager->persist($opcionesCargos);
                                                    $entityManager->flush();                        
                                                }
                                            }

                                            

                                    }
                                }   
                            }
  
                        }
                    }
 
                }else{
                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                    $entitySeccion = new Seccion();                   
                    $entitySeccion->setNombre($valor["name"]);
                    $entitySeccion->setOrden($valor["numberSection"]);
                    $entitySeccion->setInstrumento($entityInstrumento);
                    $entitySeccion->setUpdateBy($currentUser->getUserName());
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                       $entitySeccion->setIdempresa($empresa);

                    $entityManager->persist($entitySeccion);
                    $entityManager->flush();
                    foreach($valor["questions"] as $preguntas){
                        $pregunta = new Pregunta();
                        $pregunta->setPregunta(!is_null($preguntas["label"])?$preguntas["label"]:null);
                        $pregunta->setOrden(!is_null($preguntas["order"])?$preguntas["order"]:null);
                        $pregunta->setIdInput(!is_null($preguntas["inputType"])?$entityManager->getRepository(TipoInput::class)->find($preguntas["inputType"]["id"]):null);
                        $pregunta->setObligatorio(!is_null($preguntas["required"])?$preguntas["required"]:null);
                        $pregunta->setPuntos(!is_null($preguntas["score"])?$preguntas["score"]:null);
                        $pregunta->setIdCategoria(!is_null($preguntas["categoryId"])?$entityManager->getRepository(TipoCategoria::class)->find($preguntas["categoryId"]):null);
                        $pregunta->setIdInstrumento($entityInstrumento);
                        $pregunta->setSeccion($entitySeccion);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                       if($empresa)
                          $pregunta->setIdempresa($empresa);

                        $entityManager->persist($pregunta);
                        $entityManager->flush();
                        if(isset($preguntas["options"])){
                            foreach($preguntas["options"] as $options){
                                    $entityOpciones = new Opciones();
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
     * Update Instrumento Captura.
     */
    public function putOld($data,$id,$validator,$helper): JsonResponse  
    {

        $entityManagerInstrumento = $this->getEntityManager();

        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(InstrumentoCaptura::class)->find($id);
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
                
                foreach($entity->getInstrumentoUsuarios() as $instrusuarios){               
                    $entity->removeInstrumentoUsuario($instrusuarios);
                    $entityManager->persist($entity);
                    $entityManager->flush();
                }    
                
                foreach($data["users"] as $valor){
                    $instrumentoUsuario = new InstrumentoUsuario();
                    $instrumentoUsuario->setIdInstrumento($entity);
                    $user= $entityManager->getRepository(User::class)->find($valor["userId"]);
                    $instrumentoUsuario->setIdUser($user);
                    $instrumentoUsuario->setRespondida(0);
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                       $instrumentoUsuario->setIdempresa($empresa);

                    $entityManagerInstrumento->persist($instrumentoUsuario);
                    $entityManagerInstrumento->flush();
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
                    
                    $seccion = new Seccion();
                    $seccion->setInstrumento($entity);
                    $seccion->setNombre(!is_null($valor["name"])?$valor["name"]:null);
                    $seccion->setOrden(!is_null($valor["numberSection"])?$valor["numberSection"]:null);
                    $seccion->setUpdateAt(new \DateTime());
                    $seccion->setStatus($entityManager->getRepository(Status::class)->findOneById(1));          
                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                    $seccion->setUpdateBy($currentUser->getUserName());
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                      $seccion->setIdempresa($empresa);
                    $entityManagerSeccion->persist($seccion);
                    $entityManagerSeccion->flush();
                    foreach($valor["questions"] as $question){    
                        $pregunta = new Pregunta();
                        $pregunta->setPregunta(!is_null($question["label"])?$question["label"]:null);
                        $pregunta->setOrden(!is_null($question["order"])?$question["order"]:null);
                        $pregunta->setIdInput(!is_null($question["inputType"])?$entityManager->getRepository(TipoInput::class)->find($question["inputType"]["id"]):null);
                        $pregunta->setObligatorio(!is_null($question["required"])?$question["required"]:null);
                        $pregunta->setPuntos(!is_null($question["score"])?$question["score"]:null);
                        $pregunta->setIdCategoria(!is_null($question["categoryId"])?$entityManager->getRepository(TipoCategoria::class)->find($question["categoryId"]):null);
                        $pregunta->setIdInstrumento($entity);
                        $pregunta->setSeccion($seccion);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $pregunta->setIdempresa($empresa);

                        $entityManager->persist($pregunta);
                        $entityManager->flush();    
                        if(isset($question["options"])){
                             
                            foreach($question["options"] as $options){
                                $opciones = new Opciones();
                                $opciones->setCorrecta(1);
                                $opciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                $opciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                $opciones->setPuntos(!is_null($options["score"])?$options["score"]:null);
                                $opciones->setIdPregunta($pregunta);
                                $opciones->setUpdateAt(new \DateTime());
                                $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                if($empresa)
                                  $opciones->setIdempresa($empresa);

                                $opciones->setUpdateBy($currentUser->getUserName());                                
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
     * add User to Instrumento Captura.
     */
    public function addUserToInstrumento($data,$id,$validator,$helper): JsonResponse  
    {


        

        $entityManagerInstrumento = $this->getEntityManager();

        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(InstrumentoCaptura::class)->find($id);
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
                    $userInstrumento= $entityManager->getRepository(InstrumentoUsuario::class)->findBy(array("idUser"=>$valor["userId"],
                    "IdInstrumento"=>$id));
                    if($userInstrumento==null){
                        $instrumentoUsuario = new InstrumentoUsuario();
                        $instrumentoUsuario->setIdInstrumento($entity);
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

                        $entityManagerInstrumento->persist($instrumentoUsuario);
                        $entityManagerInstrumento->flush();
                    }
                }
            }    
            $conn = $this->getEntityManager()->getConnection();   
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }
    }


    /**
     * Delete Instrumento Captura.
     */
    public function delete($id,$validator,$helper): JsonResponse  
    {

        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(InstrumentoCaptura::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
       
        if ($entity->getPublicar()==1) {
            return new JsonResponse(['msg'=>'No puede eliminar un instrumento publicado'],500);  
        }

        $entityManager->remove($entity);
        $entityManager->flush();

        return new JsonResponse(['msg'=>'Registro Eliminado: '.$id],200);
        
    }


    /**
     * Publicr Instrumento Captura.
     */
    public function publicar($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(InstrumentoCaptura::class)->find($id);
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

    public function clonar($id) {
        $em = $this->getEntityManager();
        
        // 1. Cargar todo en una sola consulta usando JOIN FETCH
        $encuestaData = $em->createQueryBuilder()
            ->select('a, f, p, r, oc')
            ->from("App\Entity\Encuesta\InstrumentoCaptura", "a")
            ->leftJoin('a.seccions', 'f')
            ->leftJoin('f.preguntas', 'p')
            ->leftJoin('p.opciones', 'r')
            ->leftJoin('r.opcionesCargos', 'oc')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        if (empty($encuestaData)) {
            return null;
        }

        $originalEntity = $encuestaData[0];
        
        // 2. Clonar la entidad principal
        $newEntity = clone $originalEntity;
        $newEntity->setFechaPublicacion(null);
        $newEntity->setFechaVigencia(new \DateTime('now +1 day'));
        $newEntity->setOrden(1);
        $newEntity->setPublicar(0);
        
        // 3. Clonar secciones y sus relaciones en una sola transacción
        $em->beginTransaction();
        try {
            $em->persist($newEntity);
            
            // Clonar secciones
            foreach ($originalEntity->getSeccions() as $seccion) {
                $newSeccion = clone $seccion;
                $newEntity->addSeccion($newSeccion);
                $em->persist($newSeccion);
                
                // Clonar preguntas
                foreach ($seccion->getPreguntas() as $pregunta) {
                    $newPregunta = clone $pregunta;
                    $newPregunta->setIdInstrumento($newEntity);
                    $newPregunta->setSeccion($newSeccion);
                    $em->persist($newPregunta);
                    
                    // Clonar opciones
                    foreach ($pregunta->getOpciones() as $opcion) {
                        $newOpcion = clone $opcion;
                        $newOpcion->setIdPregunta($newPregunta);
                        $em->persist($newOpcion);
                        
                        // Clonar opcionesCargo
                        foreach ($opcion->getOpcionesCargos() as $opcionCargo) {
                            $newOpcionCargo = clone $opcionCargo;
                            $newOpcionCargo->setOpcion($newOpcion);
                            $em->persist($newOpcionCargo);
                        }
                    }
                }
            }
            
            // 4. Hacer un solo flush al final
            $em->flush();
            $em->commit();
            
            return $newEntity->getId();
            
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
    }





    public function iniciar($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();

        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $instrumentoUsuario =$entityManager->getRepository(InstrumentoUsuario::class)->findOneBy(array("IdInstrumento"=>$id,"idUser"=>$currentUser->getId()));
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
     * Publicr Instrumento Captura.
     */
    public function changeOrder($data,$id,$order,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(InstrumentoCaptura::class)->find($id);
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
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }

    }



}
