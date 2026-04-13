<?php

namespace App\Repository\Instrumento360;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Instrumento360\Instrumento360;
use App\Entity\Instrumento360\TipoInstrumento360;
use App\Entity\Instrumento360\SeccionEvaluacion360;
use App\Entity\Instrumento360\PreguntaEvaluacion360;
use App\Entity\Instrumento360\OpcionesEvaluacion360;
use App\Entity\Instrumento360\Competencia360;
use App\Entity\Encuesta\TipoUnidad;
use App\Entity\Instrumento360\TipoInputEvaluacion360;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Instrumento360Evaluaciones;
use App\Entity\Instrumento360UsuariosAsignados;

use App\Entity\User;
use App\Entity\Proyecto\Empresa;
use Symfony\Component\Security\Core\Security;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Dto\Instrumento360\Instrumento360OutPutDto;

/**
 * @method Instrumento360|null find($id, $lockMode = null, $lockVersion = null)
 * @method Instrumento360|null findOneBy(array $criteria, array $orderBy = null)
 * @method Instrumento360[]    findAll()
 * @method Instrumento360[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Instrumento360Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Instrumento360::class);
    }


     /**
     * Update Instrumento 360.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {

        $entityManagerSeccion = $this->getEntityManager();

        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Instrumento360::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $entity->setNombre(!is_null($data["name"])?$data["name"]:null);
        $entity->setDuracion(!is_null($data["dutation"])?$data["dutation"]:null);
        $entity->setTipounidad(!is_null($data["unitType"])?$entityManager->getRepository(TipoUnidad::class)->find($data["unitType"]["id"]):null);
        $entity->setQuestionsByCategory(!is_null($data["questionsByCategory"])?$data["questionsByCategory"]:null);
        $entity->setPuntosGlobales(!is_null($data["puntosGlobales"])?$data["puntosGlobales"]:null);
        $entity->setTipoInstrumento(!is_null($data["instrumentType"])?$entityManager->getRepository(TipoInstrumento360::class)->find($data["instrumentType"]):null);
        $entity->setFechaVigencia(!is_null($data["expirationDate"])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data["expirationDate"] )))):null);
        $entity->setDescripcion(!is_null($data["description"])?$data["description"]:null);
       
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
            $this->putSecciones($data,$id);
            $conn = $this->getEntityManager()->getConnection();
            
           
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }
    }

    public function post($data,$validator,$helper): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        $entityManagerInstrumento = $this->getEntityManager();

        $entity = new Instrumento360();
        $entity->setNombre(!is_null($data["name"])?$data["name"]:null);
        $entity->setDuracion(!is_null($data["dutation"])?$data["dutation"]:null);
        $entity->setTipounidad(!is_null($data["unitType"])?$entityManager->getRepository(TipoUnidad::class)->find($data["unitType"]["id"]):null);
        $entity->setTipoInstrumento(!is_null($data["instrumentType"])?$entityManager->getRepository(TipoInstrumento360::class)->find($data["instrumentType"]):null);

        $entity->setQuestionsByCategory(!is_null($data["questionsByCategory"])?$data["questionsByCategory"]:null);
        //$entity->setPath(!is_null($data["path"])?$data["path"]:null);
        $entity->setFechaVigencia(!is_null($data["expirationDate"])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data["expirationDate"] )))):null);
        $entity->setDescripcion(!is_null($data["description"])?$data["description"]:null);
        $entity->setPuntosGlobales(!is_null($data["puntosGlobales"])?$data["puntosGlobales"]:null);
       // $entityStatus = $entityManager->getRepository(Status::class)->findOneById(1);          
        //$entity->setStatusId($entityManager->getRepository(Status::class)->findOneById(1)); 
        //$entity->setOrden(1);
        $entity->setPublicar(0);                
        if(isset($data["roles"])){
    
        } 
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setEmpresa($empresa);

            $entity->setCreateBy($currentUser->getUserName());

            $entityManager->persist($entity);
            $entityManager->flush();

            if(isset($data["sections"])){
                foreach($data["sections"] as $valor){
                    $seccion = new SeccionEvaluacion360();
                    $seccion->setInstrumento($entity);
                    $seccion->setNombre(!is_null($valor["name"])?$valor["name"]:null);
                    $seccion->setOrden(!is_null($valor["numberSection"])?$valor["numberSection"]:null);
                    $seccion->setUpdateAt(new \DateTime());
                    //$seccion->setStatus($entityManager->getRepository(Status::class)->findOneById(1));          
                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                      $seccion->setIdempresa($empresa);

                    $seccion->setUpdateBy($currentUser->getUserName());
                    $entityManager->persist($seccion);
                    $entityManager->flush();
                    foreach($valor["questions"] as $question){
                        $pregunta = new PreguntaEvaluacion360();
                        $pregunta->setPregunta(!is_null($question["label"])?$question["label"]:null);
                        $pregunta->setOrden(!is_null($question["order"])?$question["order"]:null);
                        $pregunta->setIdInput(!is_null($question["inputType"])?$entityManager->getRepository(TipoInputEvaluacion360::class)->find($question["inputType"]["id"]):null);
                        $pregunta->setObligatorio(!is_null($question["required"])?$question["required"]:null);
                        $pregunta->setPuntos(!is_null($question["score"])?$question["score"]:null);
                        $pregunta->setIdCategoria(!is_null($question["categoryId"])?$entityManager->getRepository(Competencia360::class)->find($question["categoryId"]):null);
                        $pregunta->setIdInstrumento($entity);
                        $pregunta->setSeccion($seccion);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                         if($empresa)
                           $pregunta->setIdempresa($empresa);
                        $entityManager->persist($pregunta);
                        $entityManager->flush();    
                        if(isset($question["options"])){
                            foreach($question["options"] as $options){
                                $opciones = new OpcionesEvaluacion360();
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
                                // if(isset($options["scoreBycharges"])){
                                //     foreach($options["scoreBycharges"] as $optionsCargos){
                                //         $opcionesCargos = new OpcionesCargo();
                                //         $cargo =$entityManager->getRepository(Cargo::class)->find($optionsCargos["idCargo"]);
                                //         $opcionesCargos->setIdCargo($cargo!=null?$cargo:null);
                                //         $opcionesCargos->setOpcion($opciones);
                                //         $opcionesCargos->setScore($optionsCargos["score"]);
                                //         $opcionesCargos->setCreateBy($currentUser->getUsername());
                                //         $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                //         if($empresa)
                                //            $opcionesCargos->setIdempresa($empresa);

                                //         $entityManager->persist($opcionesCargos);
                                //         $entityManager->flush();    
        
                                //     }
                                // }
                            }
                        }
                    }
                }
            }
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }
    

function putSecciones($data,$id){
        $entityManager = $this->getEntityManager();
        $entityInstrumento =$entityManager->getRepository(Instrumento360::class)->find($id);

        if(isset($data["sections"])){
            foreach($data["sections"] as $valor){
                if(isset($valor["id"])){
                    $entitySeccion =$entityManager->getRepository(SeccionEvaluacion360::class)->find($valor["id"]);
                    if($entitySeccion!=null){
                        $entitySeccion->setNombre($valor["name"]);
                        $entitySeccion->setOrden($valor["numberSection"]);
                        $entityManager->flush();
                        foreach($valor["questions"] as $preguntas){
                            if(isset($preguntas["id"])){
                                $entity =$entityManager->getRepository(PreguntaEvaluacion360::class)->find($preguntas["id"]);
                                if($entity!=null){
                                    $entity->setPregunta(!is_null($preguntas["label"])?$preguntas["label"]:null);
                                    $entity->setOrden(!is_null($preguntas["order"])?$preguntas["order"]:null);
                                    $entity->setIdInput(!is_null($preguntas["inputType"])?$entityManager->getRepository(TipoInputEvaluacion360::class)->find($preguntas["inputType"]["id"]):null);
                                    if($preguntas["inputType"]["id"]!=$entity->getIdInput()->getId()){
                                        $tipoInput= $entityManager->getRepository(TipoInputEvaluacion360::class)->find($preguntas["inputType"]["id"]);
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
                                    $entity->setIdCategoria(!is_null($preguntas["categoryId"])?$entityManager->getRepository(Competencia360::class)->find($preguntas["categoryId"]):null);
                                    $entityManager->flush();
                                    $idOptions=null;
                                    if(isset($preguntas["options"])){
                                        foreach($preguntas["options"] as $options){
                                            if(isset($options["id"])){
                                                $idOptions=$options["id"];
                                                $entityOpciones =$entityManager->getRepository(OpcionesEvaluacion360::class)->find($options["id"]);
                                                if($entityOpciones!=null){
                                                    $entityOpciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                                    $entityOpciones->setValor(!is_null($options["value"])?$options["value"]:null);

                                                    //$entityOpciones->setPuntos(is_null($options["score"])?!is_null($options["score"])?$options["score"]:null:null);
                                                    $entityOpciones->setPuntos(!is_null($options["score"])?$options["score"]:null);

                                                    $entityOpciones->setUpdateAt(new \DateTime());
                                                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                                                    $entityOpciones->setUpdateBy($currentUser->getUserName());                                
                                                    $entityManager->flush();
                                                }
                                            }else{
                                                    $entityOpciones = new OpcionesEvaluacion360();
                                                    $entityOpciones->setCorrecta(1);
                                                    $entityOpciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                                    $entityOpciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                                    $entityOpciones->setPuntos(!is_null($options["score"])?$options["score"]:null);
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
                                            // if(isset($options["scoreBycharges"])){
                                            //     $sql = "SELECT * FROM opciones_cargo WHERE opcion_id = ". $idOptions;
                                            //     $conn = $this->getEntityManager()->getConnection();
                                            //     $stmt = $conn->prepare($sql);
                                            //     $stmt->execute();
                                            //     $entityOpcionesCargo = $stmt->fetchAll();
                                                
                                            //     if ($entityOpcionesCargo != null) {
                                            //         $entityManager = $this->getEntityManager();
                                            //         foreach ($entityOpcionesCargo as $opcion) {
                                            //             $opcionEntity = $entityManager->getRepository(OpcionesCargo::class)->find($idOptions);
                                            //             if ($opcionEntity) {
                                            //                 $entityManager->remove($opcionEntity);
                                            //                 $entityManager->flush();
                                            //             }
                                            //         }
                                            //     }
        
                                            //     foreach($options["scoreBycharges"] as $optionsCargos){
                                            //         $opcionesCargos = new OpcionesCargo();
                                            //         $cargo =$entityManager->getRepository(Cargo::class)->find($optionsCargos["idCargo"]);
                                            //         $opcionesCargos->setIdCargo($cargo!=null?$cargo:null);
                                            //         $opcionesCargos->setOpcion($entityOpciones);
                                            //         $opcionesCargos->setScore($optionsCargos["score"]);
                                            //         $opcionesCargos->setCreateBy($currentUser->getUsername());
                                            //         $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                            //         if($empresa)
                                            //            $opcionesCargos->setIdempresa($empresa);
                                            //         $entityManager->persist($opcionesCargos);
                                            //         $entityManager->flush();                        
                                            //     }
                                            // }

                                        }
                                    }
                                }
                            }else{
                                $pregunta = new PreguntaEvaluacion360();
                                $pregunta->setPregunta(!is_null($preguntas["label"])?$preguntas["label"]:null);
                                $pregunta->setOrden(!is_null($preguntas["order"])?$preguntas["order"]:null);
                                $pregunta->setIdInput(!is_null($preguntas["inputType"])?$entityManager->getRepository(TipoInputEvaluacion360::class)->find($preguntas["inputType"]["id"]):null);

                                $pregunta->setObligatorio(!is_null($preguntas["required"])?$preguntas["required"]:null);
                                $pregunta->setPuntos(!is_null($preguntas["score"])?$preguntas["score"]:null);
                                $pregunta->setIdCategoria(!is_null($preguntas["categoryId"])?$entityManager->getRepository(Competencia360::class)->find($preguntas["categoryId"]):null);
                                $pregunta->setIdInstrumento($entityInstrumento);
                                $pregunta->setSeccion($entitySeccion);
                                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                if($empresa)
                                   $pregunta->setIdempresa($empresa);

                                $entityManager->persist($pregunta);
                                $entityManager->flush();    
                                if(isset($preguntas["options"])){
                                    foreach($preguntas["options"] as $options){
                                            $entityOpciones = new OpcionesEvaluacion360();
                                            $entityOpciones->setCorrecta(1);
                                            $entityOpciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                            $entityOpciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                            //$entityOpciones->setPuntos(is_null($options["score"])?!is_null($options["score"])?$options["score"]:null:null);
                                            $entityOpciones->setPuntos(!is_null($options["score"])?$options["score"]:null);
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
                                            // if(isset($options["scoreBycharges"])){
                                            //     foreach($options["scoreBycharges"] as $optionsCargos){
                                            //         $opcionesCargos = new OpcionesCargo();
                                            //         $cargo =$entityManager->getRepository(Cargo::class)->find($optionsCargos["idCargo"]);
                                            //         $opcionesCargos->setIdCargo($cargo!=null?$cargo:null);
                                            //         $opcionesCargos->setOpcion($entityOpciones);
                                            //         $opcionesCargos->setScore($optionsCargos["score"]);
                                            //         $opcionesCargos->setCreateBy($currentUser->getUsername());
                                            //         $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                            //         if($empresa)
                                            //            $opcionesCargos->setIdempresa($empresa);
                                            //         $entityManager->persist($opcionesCargos);
                                            //         $entityManager->flush();                        
                                            //     }
                                            // }

                                            

                                    }
                                }   
                            }
  
                        }
                    }
 
                }else{
                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                    $entitySeccion = new SeccionEvaluacion360();                   
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
                        //$pregunta = new Pregunta();
                        $pregunta = new PreguntaEvaluacion360();
                        $pregunta->setPregunta(!is_null($preguntas["label"])?$preguntas["label"]:null);
                        $pregunta->setOrden(!is_null($preguntas["order"])?$preguntas["order"]:null);

                        $pregunta->setIdInput(!is_null($preguntas["inputType"])?$entityManager->getRepository(TipoInputEvaluacion360::class)->find($preguntas["inputType"]["id"]):null);

                        $pregunta->setObligatorio(!is_null($preguntas["required"])?$preguntas["required"]:null);
                        $pregunta->setPuntos(!is_null($preguntas["score"])?$preguntas["score"]:null);
                        //$pregunta->setIdCategoria(!is_null($preguntas["categoryId"])?$entityManager->getRepository(TipoCategoria::class)->find($preguntas["categoryId"]):null);
                        $pregunta->setIdCategoria(!is_null($preguntas["categoryId"])?$entityManager->getRepository(Competencia360::class)->find($preguntas["categoryId"]):null);
                        $pregunta->setIdInstrumento($entityInstrumento);
                        $pregunta->setSeccion($entitySeccion);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                       if($empresa)
                          $pregunta->setIdempresa($empresa);

                        $entityManager->persist($pregunta);
                        $entityManager->flush();
                        if(isset($preguntas["options"])){
                            foreach($preguntas["options"] as $options){
                                    //$entityOpciones = new Opciones();
                                    $entityOpciones = new OpcionesEvaluacion360();
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

    

    public function findById($id){
        $entityManager = $this->getEntityManager();        
        $entity= $this->getEntityManager()->createQueryBuilder();
        $encuestaData= $entity->select("a,q")
            ->from("App\Entity\Instrumento360\Instrumento360","a")
            //->leftJoin('a.Instrumento360UsuariosAsignados', 'q')
            ->leftJoin('a.instrumento360UsuariosAsignados', 'q')
            ->leftJoin('a.tipounidad', 't')
            ->leftJoin('a.tipoInstrumento', 'ti')
            ->leftJoin('a.tipoInstrumento', 'em')
            ->leftJoin('q.user', 'x')
            ->leftJoin('q.userEvaluador', 'e')
            ->leftjoin('a.seccions', 'f')
            ->Where('a.id='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
        $dataInstrumento=null;
        foreach($encuestaData as $clave=>$valor){
            $instrumentoDto =new Instrumento360OutPutDto();
            $instrumentoDto->id=$valor->getId();
            $instrumentoDto->nombre=$valor->getNombre();
            $instrumentoDto->descripcion=$valor->getDescripcion();
            $instrumentoDto->duracion=$valor->getDuracion();
            $instrumentoDto->publicar=!is_null($valor->getPublicar())?$valor->getPublicar():0;
           
           $instrumentoDto->questionsByCategory= !is_null($valor->getQuestionsByCategory())?$valor->getQuestionsByCategory():0;
           $instrumentoDto->puntosGlobales= !is_null($valor->getPuntosGlobales())?$valor->getPuntosGlobales():0;
            
            //$instrumentoDto->unidad=$valor->getUnidad();

            //$instrumentoDto->path=$valor->getPath();

            $instrumentoDto->tipounidad=($valor->getTipoUnidad()!=null)?array("id"=>$valor->getTipoUnidad()->getId(),"Descripcion"=>$valor->getTipoUnidad()->getNombre()):[];


            $instrumentoDto->tipoInstrumento=($valor->getTipoInstrumento()!=null)?array("id"=>$valor->getTipoInstrumento()->getId(),"Descripcion"=>$valor->getTipoInstrumento()->getNombre()):[];
            $instrumentoDto->empresa=($valor->getEmpresa()!=null)?array("id"=>$valor->getEmpresa()->getId(),"Descripcion"=>$valor->getEmpresa()->getNombre()):[];

            //$instrumentoDto->statusId=($valor->getStatusId()!=null)?array("id"=>$valor->getStatusId()->getId(),"Descripcion"=>$valor->getStatusId()->getDescripcion()):[];
            
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
            /* foreach($valor->getRoles() as $valorRol){
                $roles[]= $valorRol->getDescripcion();            
            }
            $instrumentoDto->roles=trim(json_encode($roles),'"');
            $instrumentoDto->roles=$roles; */

            $usersData=[];
            $editable=1;
            if($instrumentoDto->publicar==1){
                $editable=0;
            }
            
           /*  if($valor->getInstrumento360UsuariosAsignados()!=null){
                foreach($valor->getInstrumento360UsuariosAsignados() as $instrumentosuser){
                        if($instrumentosuser->getRespondida()==1){
                            $editable=0;
                        }
                        $usersData[]=array("id"=>$instrumentosuser->getIdUser()->getId(),"nombre"=>$instrumentosuser->getIdUser()->getPrimerNombre(). " ".$instrumentosuser->getIdUser()->getPrimerApellido(),"email"=>$instrumentosuser->getIdUser()->getEmail()
                        ,"respondida"=>$instrumentosuser->getRespondida(),"roles"=>$instrumentosuser->getIdUser()->getRoles());                       
                }
            } */

            //************************************************************************* */

             $estructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'e.id = u.idestructura')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getOneOrNullResult();

                        //return array("data"=>$estructuraId);

                   // Consulta principal
                    $qb = $this->getEntityManager()->createQueryBuilder();
                    $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username,u.roles,
                                c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                    ->from('App\Entity\User', 'u')
                    ->innerJoin('u.idCargo', 'c')
                    ->innerJoin('u.idestructura', 'e')
                    ->where('u.id = :userRef')
                    ->andWhere('e.id = :estructuraId')
                    ->setParameter('userRef', $this->security->getUser()->getId())
                    ->setParameter('estructuraId', $estructuraId)
                    ->orderBy('c.nivel', 'ASC');

                    $contSinResp=0;
                    $usuariosFiltrados = $qb->getQuery()->getResult();
                     $datosUsuarios = [];
                   foreach ($usuariosFiltrados as $usuarioAsignado) {
                      /*   $sqlUser = " SELECT respondida FROM instrumento360 a INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id WHERE b.user_id = ".$usuarioAsignado['id_user']; */

                        $sqlUser = "SELECT respondida 
                        FROM instrumento360 a 
                        INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id 
                        WHERE b.user_id = ".$usuarioAsignado['id_user']." 
                        AND b.instrumento360_id = ".$id;


                        $conn = $this->getEntityManager()->getConnection();
                        $stmt = $conn->prepare($sqlUser);
                        $stmt->execute();
                       $dataUserRespondida=$stmt->fetchAll();
                       $resp=0;
                       if (!empty($dataUserRespondida)) {
                           if ( $dataUserRespondida[0]["respondida"]==0) {
                               $contSinResp++;
                           }
                            $resp = $dataUserRespondida[0]["respondida"];
                       }else{
                           $contSinResp++;
                       }

                       
                       /* if (!empty($dataUserRespondida)) {
                            $resp = $dataUserRespondida[0]["respondida"];
                       } */
                       $rolesArray = json_decode($usuarioAsignado['roles'], true);

                       /* $usersData[]=array("id"=>$instrumentosuser->getUserEvaluador()->getId(),
                       "nombre"=>$instrumentosuser->getUserEvaluador()->getPrimerNombre(). " ".$instrumentosuser->getUserEvaluador()->getPrimerApellido()
                       ,"email"=>$instrumentosuser->getUserEvaluador()->getEmail()
                        ,"respondida"=>$instrumentosuser->getRespondida(),"roles"=>$instrumentosuser->getUserEvaluador()->getRoles());                        */

                       $datosUsuarios[] = [
                            'usuarioId'    => $usuarioAsignado['id_user'],
                            "nombre"=> $usuarioAsignado['primerNombre']. " ". $usuarioAsignado['primerApellido'],
                            "email" => $usuarioAsignado['username'],
                            'cargoId'      => $usuarioAsignado['idcargo'],
                            'cargoNombre'      => $usuarioAsignado['cargo'],
                            'respondida'   => $resp,
                            'roles'   => $rolesArray
                        ];

                    } 


            //************************************************************************** */

            $instrumentoDto->editable=$editable;
            $instrumentoDto->users=$datosUsuarios;
            //$instrumentoDto->users=$usersData;
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
                    $preguntas= $entityManager->getRepository(PreguntaEvaluacion360::class)->findByIdEncuestaAndSeccion($id,$seccion->getId());
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
            $query->where("a.nombre like '%".$data['word']."%' ");
        }

        $query->andwhere("a.evaluatorUserId =".$this->security->getUser()->getId());
        $query->andWhere("a.publicar =1");
        $query->andWhere("a.fechaVigencia  >= CURRENT_DATE()" );

        //$query->orderBy('a.id', 'ASC');
        if($data['word']!=null){
            $query->where("a.nombre like '%".$data['word']."%' and a.empresa = ".$empresa->getId()." "); 
        }else{
            $query->where("a.empresa = ".$empresa->getId());
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
            $instrumentocapturaDto =new Instrumento360OutPutDto();
            $instrumentocapturaDto->id=$valor->getId();
            $instrumentocapturaDto->nombre=$valor->getNombre();
            $instrumentocapturaDto->descripcion=$valor->getDescripcion();
            $instrumentocapturaDto->idTipoUnidad=($valor->getTipoUnidad()!=null)?array("id"=>$valor->getTipoUnidad()->getId(),"Nombre"=>$valor->getTipoUnidad()->getNombre(),"Factor"=>$valor->getTipoUnidad()->getFactor()):[];
            $instrumentocapturaDto->unidad=$valor->getTipounidad();

            //$instrumentocapturaDto->fechaPublicacion=$valor->getFechaPublicacion()->format("Y-m-d");
            $instrumentocapturaDto->fechaPublicacion=!is_null($valor->getFechaPublicacion())?$valor->getFechaPublicacion()->format("Y-m-d"):null;
            $instrumentocapturaDto->createAt=!is_null($valor->getCreateAt())?$valor->getCreateAt()->format("Y-m-d"):null;
            //$instrumentocapturaDto->createAt=$valor->getCreateAt()->format("Y-m-d");
            $instrumentocapturaDto->fechaVigencia=$valor->getFechaVigencia()->format("Y-m-d");
            $instrumentocapturaDto->publicar=$valor->getPublicar();
            $instrumentocapturaDto->tipoInstrumento=($valor->getTipoInstrumento()!=null)?array("id"=>$valor->getTipoInstrumento()->getId(),"Nombre"=>$valor->getTipoInstrumento()->getNombre()):[];
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
            }else{
                $instrumentocapturaDto->editable=1;
            }

            /* if(count($encuestaData)>0){
                $instrumentocapturaDto->editable=0;
            }else{
                $instrumentocapturaDto->editable=1;    
            } */

            $datainstrumentocap[]=$instrumentocapturaDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$datainstrumentocap);
 
    }


public function findListByInstructor($data){

       

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
       
        $query= $this->createQueryBuilder('a');
        $query->Where("a.fechaVigencia  >= CURRENT_DATE()" );
        //$query->orderBy('a.id', 'ASC');
        if($data['word']!=null){
            $query->andwhere("a.nombre like '%".$data['word']."%' ");
        }

        //$query->andwhere("a.evaluatorUserId =".$this->security->getUser()->getId());
        $query->andWhere("a.publicar =1");

        //Devolver solo los Autoevaluacion 
        //$query->andWhere("a.tipoInstrumento =2");
      
        //$query->andWhere("a.fechaVigencia  >= CURRENT_DATE()" );

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
           
            $idInstrumento360=$valor->getId();

            //Autoevaluacion ***********************************
           if($valor->getTipoInstrumento()->getId() ==2){
            $entity= $this->getEntityManager()->createQueryBuilder();

             /* $EvaluacionDataRespondida = $entity->select("i,asignado")
            ->from("App\Entity\Instrumento360\Instrumento360", "i")
            ->innerJoin('i.instrumento360UsuariosAsignados', 'asignado')
            ->where('asignado.respondida = 0')
            ->andWhere('i.id = ' . $valor->getId())
            ->andWhere('asignado.user = ' . $this->security->getUser()->getId())
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult(); */

             $EvaluacionDataRespondida = $entity->select("i, asignado")
            ->from("App\Entity\Instrumento360\Instrumento360", "i")
            ->innerJoin('i.instrumento360UsuariosAsignados', 'asignado')
            ->where('asignado.respondida = 0')
            ->andWhere('i.id = :instrumentoId')
            ->andWhere('asignado.user = :authUser')
            ->setParameter('instrumentoId', $valor->getId())
            ->setParameter('authUser', $this->security->getUser()->getId())
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult();


           // if (!empty($EvaluacionDataRespondida)) { 

                $estructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'e.id = u.idestructura')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getOneOrNullResult();

                        //return array("data"=>$estructuraId);

                   // Consulta principal
                    $qb = $this->getEntityManager()->createQueryBuilder();
                    $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username,u.roles,
                                c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                    ->from('App\Entity\User', 'u')
                    ->innerJoin('u.idCargo', 'c')
                    ->innerJoin('u.idestructura', 'e')
                    ->where('u.id = :userRef')
                    ->andWhere('e.id = :estructuraId')
                    ->setParameter('userRef', $this->security->getUser()->getId())
                    ->setParameter('estructuraId', $estructuraId)
                    ->orderBy('c.nivel', 'ASC');
                    
                    $contSinResp=0;
                    $usuariosFiltrados = $qb->getQuery()->getResult();
                     $datosUsuarios = [];
                   foreach ($usuariosFiltrados as $usuarioAsignado) {
                        /* $sqlUser = " SELECT respondida FROM instrumento360 a INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id WHERE b.user_id = ".$usuarioAsignado['id_user']."  and  b.instrumento360_id. = ".$valor->getId(); */

                        $sqlUser = "SELECT respondida 
                        FROM instrumento360 a 
                        INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id 
                        WHERE b.user_id = ".$usuarioAsignado['id_user']." 
                        AND b.instrumento360_id = ".$valor->getId();

                        
                        $conn = $this->getEntityManager()->getConnection();
                        $stmt = $conn->prepare($sqlUser);
                        $stmt->execute();
                       $dataUserRespondida=$stmt->fetchAll();
                       $resp=0;
                       if (!empty($dataUserRespondida)) {
                            if ( $dataUserRespondida[0]["respondida"]==0) {
                            $contSinResp++;
                            }
                            $resp = $dataUserRespondida[0]["respondida"];
                       }else{
                        $contSinResp++;
                       }
                       $rolesArray = json_decode($usuarioAsignado['roles'], true);
                       $datosUsuarios[] = [
                            'usuarioId'    => $usuarioAsignado['id_user'],
                            'cargoId'      => $usuarioAsignado['idcargo'],
                            'cargoNombre'      => $usuarioAsignado['cargo'],
                            'respondida'   => $resp,
                            'roles'   => $rolesArray
                        ];
                    } 


            $evaluacionDto =new Instrumento360OutPutDto();
            $evaluacionDto->userIfEvaluating=$contSinResp;

            $evaluacionDto->instrumento360UsuariosAsignados=$datosUsuarios;
            $evaluacionDto->id=$valor->getId();
            $evaluacionDto->nombre=$valor->getNombre();
            $evaluacionDto->descripcion=$valor->getDescripcion();

            $evaluacionDto->idTipoUnidad=($valor->getTipoUnidad()!=null)?array("id"=>$valor->getTipoUnidad()->getId(),"Nombre"=>$valor->getTipoUnidad()->getNombre(),"Factor"=>$valor->getTipoUnidad()->getFactor()):[];
            $evaluacionDto->unidad=$valor->getTipounidad();

            $evaluacionDto->tipoInstrumento=($valor->getTipoInstrumento()!=null)?array("id"=>$valor->getTipoInstrumento()->getId(),"Nombre"=>$valor->getTipoInstrumento()->getNombre()):[];

            //$evaluacionDto->path=$valor->getPath();
            //$evaluacionDto->orden=$valor->getOrden();;

            $evaluacionDto->fechaPublicacion=!is_null($valor->getFechaPublicacion())?$valor->getFechaPublicacion()->format("Y-m-d"):null;
            $evaluacionDto->createAt=!is_null($valor->getCreateAt())?$valor->getCreateAt()->format("Y-m-d"):null;
            $evaluacionDto->fechaVigencia=$valor->getFechaVigencia()->format("Y-m-d");
            $evaluacionDto->publicar=$valor->getPublicar();
            $evaluacionDto->questionsByCategory=$valor->getQuestionsByCategory();
            $evaluacionDto->puntosGlobales=$valor->getPuntosGlobales();
            $evaluacionDto->editable=1;

            $entity= $this->getEntityManager()->createQueryBuilder();
            
                    

         

            /* $sqlUser = " SELECT count(*) as total FROM instrumento360 a INNER JOIN instrumento360_usuarios_asignados b 
            ON a.id = b.instrumento360_id WHERE b.respondida = 0 AND a.id = ".$valor->getId()  ;
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sqlUser);
            $stmt->execute();
            $dataUserCount=$stmt->fetchAll(); */

            $sqlUser = " SELECT count(*) as total FROM instrumento360 a INNER JOIN instrumento360_usuarios_asignados b 
                    ON a.id = b.instrumento360_id WHERE b.respondida = 0 AND a.id = ".$valor->getId() ." AND b.user_id <> ".$this->security->getUser()->getId();
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

           //$evaluacionDto->userIfEvaluating=$dataUserCount[0]["total"];
            $dataevaluacion[]=$evaluacionDto;
         //}
           
          // tipo PAR ***********************************
          }else if($valor->getTipoInstrumento()->getId() ==3){ 

                    $cargoUserLogin = $this->security->getUser()->getIdCargo()->getId();
                    // Obtener el nivel_id del cargo y la estructura del usuario 2961
                    $nivelId = $this->getEntityManager()->createQueryBuilder()
                        ->select('IDENTITY(c.nivel)')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();

                        $estructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'e.id = u.idestructura')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getOneOrNullResult();

                    $cargoId = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();

                    if($cargoId ==2 or $cargoId ==3 or $cargoId ==4 or $cargoId ==5 ){  

                        $todasestructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->where('e.padre_id = :Refpadre')
                        ->andWhere('e.id != :estructuraId')
                        ->setParameter('Refpadre', $estructuraId->getPadreId())
                        ->setParameter('estructuraId', $estructuraId->getId())
                        ->getQuery()
                        ->getResult(); // devuelve un array de entidades
                        
                        $estructuraFId  = []; // inicializamos el array

                        foreach ($todasestructuraId as $estructura) {
                            // $estructura es un objeto EstructuraOrganizativa
                            $estructuraFId[] = $estructura->getId(); // acumulamos cada ID
                        }

                       // $valorestructura = $estructuraId->getPadreId();

                       //$val = $this->Estructura_Organizativa($valor->getUnidad()->getId());


                    // Consulta principal
                   $qb = $this->getEntityManager()->createQueryBuilder();
                    $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username, u.roles,
                                c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                    ->from('App\Entity\User', 'u')
                    ->innerJoin('u.idCargo', 'c')
                    ->innerJoin('u.idestructura', 'e')
                    ->where('c.nivel = :nivel')
                    ->andWhere('e.id IN (:estructuraIds)')
                    ->andWhere('c.id = :cargoId')
                    ->andWhere('u.id != :userRef')
                    ->setParameter('nivel', $nivelId)
                    ->setParameter('estructuraIds', $estructuraFId) // puede ser array
                    ->setParameter('cargoId', $cargoId)
                    ->setParameter('userRef', $this->security->getUser()->getId())
                    ->orderBy('c.nivel', 'ASC');

                    $usuariosFiltrados = $qb->getQuery()->getResult();
                         

                 }else{

                    // Consulta principal
                    $qb = $this->getEntityManager()->createQueryBuilder();
                        $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username,u.roles,
                                    c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                    e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                        ->from('App\Entity\User', 'u')
                        ->innerJoin('u.idCargo', 'c')
                        ->innerJoin('u.idestructura', 'e')
                        ->where('c.nivel = :nivel')
                        ->andWhere('e.id = :estructuraId')
                        ->andWhere('c.id = :cargoId')
                        ->andwhere('u.id != :userRef')
                        ->setParameter('nivel', $nivelId)
                        ->setParameter('estructuraId', $estructuraId)
                        ->setParameter('cargoId', $cargoId)
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->orderBy('c.nivel', 'ASC');

                        $usuariosFiltrados = $qb->getQuery()->getResult();
                  }


                    $contSinResp=0;
                 if (!empty($usuariosFiltrados)) {
                   $datosUsuarios = [];
                   foreach ($usuariosFiltrados as $usuarioAsignado) {
                        /* $sqlUser = " SELECT respondida FROM instrumento360 a INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id WHERE b.user_id = ".$usuarioAsignado['id_user']; */

                        $sqlUser = "SELECT respondida 
                        FROM instrumento360 a 
                        INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id 
                        WHERE b.user_id = ".$usuarioAsignado['id_user']." 
                        AND b.instrumento360_id = ".$valor->getId();

                        $conn = $this->getEntityManager()->getConnection();
                        $stmt = $conn->prepare($sqlUser);
                        $stmt->execute();
                       $dataUserRespondida=$stmt->fetchAll();
                       $resp=0;
                       if (!empty($dataUserRespondida)) {
                        if ( $dataUserRespondida[0]["respondida"]==0) {
                            $contSinResp++;
                         }
                            $resp = $dataUserRespondida[0]["respondida"];
                       }else{
                          $contSinResp++;
                       }
                       $rolesArray = json_decode($usuarioAsignado['roles'], true);
                       $datosUsuarios[] = [
                            'usuarioId'    => $usuarioAsignado['id_user'],
                            'cargoId'      => $usuarioAsignado['idcargo'],
                            'cargoNombre'      => $usuarioAsignado['cargo'],
                            'respondida'   => $resp,
                            'roles'   => $rolesArray
                        ];
                    }
            
                   $evaluacionDto =new Instrumento360OutPutDto();
                   $evaluacionDto->userIfEvaluating=$contSinResp;

                   $evaluacionDto->instrumento360UsuariosAsignados=$datosUsuarios;


                    $evaluacionDto->id=$valor->getId();
                    $evaluacionDto->nombre=$valor->getNombre();
                    $evaluacionDto->descripcion=$valor->getDescripcion();

                    $evaluacionDto->idTipoUnidad=($valor->getTipoUnidad()!=null)?array("id"=>$valor->getTipoUnidad()->getId(),"Nombre"=>$valor->getTipoUnidad()->getNombre(),"Factor"=>$valor->getTipoUnidad()->getFactor()):[];
                    $evaluacionDto->unidad=$valor->getTipounidad();

                    $evaluacionDto->tipoInstrumento=($valor->getTipoInstrumento()!=null)?array("id"=>$valor->getTipoInstrumento()->getId(),"Nombre"=>$valor->getTipoInstrumento()->getNombre()):[];

                    //$evaluacionDto->path=$valor->getPath();
                    //$evaluacionDto->orden=$valor->getOrden();;

                    $evaluacionDto->fechaPublicacion=!is_null($valor->getFechaPublicacion())?$valor->getFechaPublicacion()->format("Y-m-d"):null;
                    $evaluacionDto->createAt=!is_null($valor->getCreateAt())?$valor->getCreateAt()->format("Y-m-d"):null;
                    $evaluacionDto->fechaVigencia=$valor->getFechaVigencia()->format("Y-m-d");
                    $evaluacionDto->publicar=$valor->getPublicar();

                    $evaluacionDto->questionsByCategory=$valor->getQuestionsByCategory();
                    $evaluacionDto->puntosGlobales=$valor->getPuntosGlobales();

                    $evaluacionDto->editable=1;

                    $entity= $this->getEntityManager()->createQueryBuilder();
                    
                     $EvaluacionDataRespondida= $entity->select("a,q")
                        ->from("App\Entity\Instrumento360\Instrumento360","a")
                        ->innerJoin('a.instrumento360UsuariosAsignados', 'q')
                        ->Where('q.respondida=1')
                        ->andWhere('a.id='.$valor->getId())
                        ->orderBy('a.id', 'ASC')
                        ->getQuery()
                        ->getResult();
                    /*
                    $EvaluacionDataNoRespondida= $entity->select("b,h")
                        ->from("App\Entity\Instrumento360\Instrumento360","b")
                        ->innerJoin('b.instrumento360UsuariosAsignados', 'h')
                        ->Where('h.respondida=0')
                        ->andWhere('b.id='.$valor->getId())
                        ->orderBy('b.id', 'ASC')
                        ->getQuery()
                        ->getResult(); */

                    $sqlUser = " SELECT count(*) as total FROM instrumento360 a INNER JOIN instrumento360_usuarios_asignados b 
                    ON a.id = b.instrumento360_id WHERE b.respondida = 0 AND a.id = ".$valor->getId() ." AND b.user_id <> ".$this->security->getUser()->getId();  
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

                    //$evaluacionDto->userIfEvaluating=$dataUserCount[0]["total"];
                    $dataevaluacion[]=$evaluacionDto;
                }
                        
           // tipo Supervisorio Descendente***********************************
          }else if($valor->getTipoInstrumento()->getId() ==1){ 
                $cargoUserLogin = $this->security->getUser()->getIdCargo()->getId();
                    // Obtener el nivel_id del cargo y la estructura del usuario 2961
                    $nivelId = $this->getEntityManager()->createQueryBuilder()
                        ->select('IDENTITY(c.nivel)')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();

                        $estructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'e.id = u.idestructura')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getOneOrNullResult();

                    /* $cargoIds = $this->getEntityManager()->createQueryBuilder()
                        //->select('c.id')
                        ->select('c.id_jerarquia_ascendente')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult(); */

                    $cargoIdsRaw = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id_jerarquia_ascendente')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getScalarResult();

                    // Convertir a array plano
                    $cargoIds = array_column($cargoIdsRaw, 'id_jerarquia_ascendente');
                    // Extraer el string JSON del primer elemento
                    $jsonString = $cargoIds[0];
                    // Decodificar a array
                    $cargoIds = json_decode($jsonString, true);

                    $cargoId = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();

                    if($cargoId ==1 or $cargoId ==2 or $cargoId ==3 or $cargoId ==4 or $cargoId ==5 ){  

                         if($cargoId ==1){  
                           $cargoId = $cargoIds; 
                         }else{
                           $cargoId = $cargoId +1;
                         }

                        $todasestructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->where('e.padre_id = :Refpadre')
                        ->andWhere('e.id != :estructuraId')
                        ->setParameter('Refpadre', $estructuraId->getId())
                        ->setParameter('estructuraId', $estructuraId->getId())
                        ->getQuery()
                        ->getResult(); // devuelve un array de entidades
                        
                        $estructuraFId  = []; // inicializamos el array

                        foreach ($todasestructuraId as $estructura) {
                            // $estructura es un objeto EstructuraOrganizativa
                            $estructuraFId[] = $estructura->getId(); // acumulamos cada ID
                        }

                        $qb = $this->getEntityManager()->createQueryBuilder();
                        $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username, u.username,u.roles,
                                    c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                    e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                        ->from('App\Entity\User', 'u')
                        ->innerJoin('u.idCargo', 'c')
                        ->innerJoin('u.idestructura', 'e')
                        //->where('c.nivel = :nivel')
                        ->Where('e.id IN (:estructuraId)')
                        ->andWhere($qb->expr()->in('c.id', ':cargoIds')) // 👈 AQUÍ el cambio importante
                        ->andWhere('u.id != :userRef')
                        ->andWhere('u.idStatus = :statusActivo') // 👈 Usando parámetro
                        //->setParameter('nivel', $nivelId)
                        ->setParameter('estructuraId', $estructuraFId)
                        ->setParameter('cargoIds', $cargoId)
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->setParameter('statusActivo', 1) // 👈 Parámetro con nombre descriptivo
                        ->orderBy('c.nivel', 'ASC');

                    
                        $usuariosFiltrados = $qb->getQuery()->getResult();



                    }else{

                        //$cargoIds = [4,5]; // 4 Gerente de Linea / 5 Coordinador IDs de los cargos que deseas incluir
                        //$cargoIds = [4,5,6,7,8,9,10,11,12]; // IDs de los cargos que deseas incluir
                        $qb = $this->getEntityManager()->createQueryBuilder();
                        $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username, u.username,u.roles,
                                    c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                    e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                        ->from('App\Entity\User', 'u')
                        ->innerJoin('u.idCargo', 'c')
                        ->innerJoin('u.idestructura', 'e')
                        //->where('c.nivel = :nivel')
                        ->Where('e.id = :estructuraId')
                        ->andWhere($qb->expr()->in('c.id', ':cargoIds')) // 👈 AQUÍ el cambio importante
                        ->andWhere('u.id != :userRef')
                        //->setParameter('nivel', $nivelId)
                        ->setParameter('estructuraId', $estructuraId)
                        ->setParameter('cargoIds', $cargoIds)
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->orderBy('c.nivel', 'ASC');

                    
                        $usuariosFiltrados = $qb->getQuery()->getResult();
                   }

                    $contSinResp=0;
                if (!empty($usuariosFiltrados)) {
                   $datosUsuarios = [];
                   foreach ($usuariosFiltrados as $usuarioAsignado) {
                        /* $sqlUser = " SELECT respondida FROM instrumento360 a INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id WHERE b.user_id = ".$usuarioAsignado['id_user']; */

                        $sqlUser = "SELECT respondida 
                        FROM instrumento360 a 
                        INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id 
                        WHERE b.user_id = ".$usuarioAsignado['id_user']." 
                        AND b.instrumento360_id = ".$valor->getId();

                        $conn = $this->getEntityManager()->getConnection();
                        $stmt = $conn->prepare($sqlUser);
                        $stmt->execute();
                       $dataUserRespondida=$stmt->fetchAll();
                       $resp=0;
                       if (!empty($dataUserRespondida)) {
                            if ( $dataUserRespondida[0]["respondida"]==0) {
                            $contSinResp++;
                            }
                            $resp = $dataUserRespondida[0]["respondida"];
                       }else{
                        $contSinResp++;
                       }
                       $rolesArray = json_decode($usuarioAsignado['roles'], true);
                       $datosUsuarios[] = [
                            'usuarioId'    => $usuarioAsignado['id_user'],
                            'cargoId'      => $usuarioAsignado['idcargo'],
                            'cargoNombre'      => $usuarioAsignado['cargo'],
                            'respondida'   => $resp,
                            'roles'   => $rolesArray
                        ];
                    }

                    
            
                   $evaluacionDto =new Instrumento360OutPutDto();

                   $evaluacionDto->userIfEvaluating=$contSinResp;

                   $evaluacionDto->instrumento360UsuariosAsignados=$datosUsuarios;


                    $evaluacionDto->id=$valor->getId();
                    $evaluacionDto->nombre=$valor->getNombre();
                    $evaluacionDto->descripcion=$valor->getDescripcion();

                    $evaluacionDto->idTipoUnidad=($valor->getTipoUnidad()!=null)?array("id"=>$valor->getTipoUnidad()->getId(),"Nombre"=>$valor->getTipoUnidad()->getNombre(),"Factor"=>$valor->getTipoUnidad()->getFactor()):[];
                    $evaluacionDto->unidad=$valor->getTipounidad();

                    $evaluacionDto->tipoInstrumento=($valor->getTipoInstrumento()!=null)?array("id"=>$valor->getTipoInstrumento()->getId(),"Nombre"=>$valor->getTipoInstrumento()->getNombre()):[];

                    //$evaluacionDto->path=$valor->getPath();
                    //$evaluacionDto->orden=$valor->getOrden();;

                    $evaluacionDto->fechaPublicacion=!is_null($valor->getFechaPublicacion())?$valor->getFechaPublicacion()->format("Y-m-d"):null;
                    $evaluacionDto->createAt=!is_null($valor->getCreateAt())?$valor->getCreateAt()->format("Y-m-d"):null;
                    $evaluacionDto->fechaVigencia=$valor->getFechaVigencia()->format("Y-m-d");
                    $evaluacionDto->publicar=$valor->getPublicar();

                    $evaluacionDto->questionsByCategory=$valor->getQuestionsByCategory();
                    $evaluacionDto->puntosGlobales=$valor->getPuntosGlobales();

                    $evaluacionDto->editable=1;

                    $entity= $this->getEntityManager()->createQueryBuilder();
                    
                     $EvaluacionDataRespondida= $entity->select("a,q")
                        ->from("App\Entity\Instrumento360\Instrumento360","a")
                        ->innerJoin('a.instrumento360UsuariosAsignados', 'q')
                        ->Where('q.respondida=1')
                        ->andWhere('a.id='.$valor->getId())
                        ->orderBy('a.id', 'ASC')
                        ->getQuery()
                        ->getResult();
                    /*
                    $EvaluacionDataNoRespondida= $entity->select("b,h")
                        ->from("App\Entity\Instrumento360\Instrumento360","b")
                        ->innerJoin('b.instrumento360UsuariosAsignados', 'h')
                        ->Where('h.respondida=0')
                        ->andWhere('b.id='.$valor->getId())
                        ->orderBy('b.id', 'ASC')
                        ->getQuery()
                        ->getResult(); */

                    $sqlUser = " SELECT count(*) as total FROM instrumento360 a INNER JOIN instrumento360_usuarios_asignados b 
                    ON a.id = b.instrumento360_id WHERE b.respondida = 0 AND a.id = ".$valor->getId() ." AND b.user_id <> ".$this->security->getUser()->getId();
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

                    //$evaluacionDto->userIfEvaluating=$dataUserCount[0]["total"];
                    $dataevaluacion[]=$evaluacionDto;
                }
          // tipo Supervisorio Ascendente***********************************
          }else if($valor->getTipoInstrumento()->getId() ==4){ 


                 $cargoUserLogin = $this->security->getUser()->getIdCargo()->getId();
                    // Obtener el nivel_id del cargo y la estructura del usuario 2961
                    $nivelId = $this->getEntityManager()->createQueryBuilder()
                        ->select('IDENTITY(c.nivel)')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();

                        $estructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'e.id = u.idestructura')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getOneOrNullResult();

                    /* $cargoIds = $this->getEntityManager()->createQueryBuilder()
                        //->select('c.id')
                        ->select('c.id_jerarquia_ascendente')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult(); */

                    $cargoIdsRaw = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id_jerarquia_descendente')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getScalarResult();

                    // Convertir a array plano
                    $cargoIds = array_column($cargoIdsRaw, 'id_jerarquia_descendente');
                    // Extraer el string JSON del primer elemento
                    $jsonString = $cargoIds[0];
                    // Decodificar a array
                    $cargoIds = json_decode($jsonString, true);

                    $cargoId = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();
                     if($cargoId ==1 or $cargoId ==2 or $cargoId ==3 or $cargoId ==4 or $cargoId ==5 ){  
                         //buscar el padre de la unidad padre del usuario logeado 
                         //es el padre = 1 entonce //$cargoId = 1; 
                         //de lo contrario
                         if($estructuraId->getPadreId() ==1){
                            $cargoId = 1;  
                         }else{
                            $cargoId = $cargoId -1;
                         }
 
                       $todasestructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->where('e.id = :Refpadre')
                        //->andWhere('e.id != :estructuraId')
                        ->setParameter('Refpadre', $estructuraId->getPadreId())
                        //->setParameter('estructuraId', $estructuraId->getId())
                        ->getQuery()
                        ->getResult(); // devuelve un array de entidades

                        
                        $estructuraFId  = []; // inicializamos el array

                        foreach ($todasestructuraId as $estructura) {
                            // $estructura es un objeto EstructuraOrganizativa
                            $estructuraFId[] = $estructura->getId(); // acumulamos cada ID
                        }

                        $qb = $this->getEntityManager()->createQueryBuilder();
                        $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username, u.username,u.roles,
                                    c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                    e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                        ->from('App\Entity\User', 'u')
                        ->innerJoin('u.idCargo', 'c')
                        ->innerJoin('u.idestructura', 'e')
                        //->where('c.nivel = :nivel')
                        ->Where('e.id IN (:estructuraId)')
                        ->andWhere($qb->expr()->in('c.id', ':cargoIds')) // 👈 AQUÍ el cambio importante
                        ->andWhere('u.id != :userRef')
                        ->andWhere('u.idStatus = :statusActivo') // 👈 Usando parámetro
                        //->setParameter('nivel', $nivelId)
                        ->setParameter('estructuraId', $estructuraFId)
                        ->setParameter('cargoIds', $cargoId)
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->setParameter('statusActivo', 1) // 👈 Parámetro con nombre descriptivo
                        ->orderBy('c.nivel', 'ASC');

                    
                        $usuariosFiltrados = $qb->getQuery()->getResult();



                    }else{

                        //$cargoIds = [4,5]; // 4 Gerente de Linea / 5 Coordinador IDs de los cargos que deseas incluir
                        //$cargoIds = [4,5,6,7,8,9,10,11,12]; // IDs de los cargos que deseas incluir
                        $qb = $this->getEntityManager()->createQueryBuilder();
                        $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username, u.username,u.roles,
                                    c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                    e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                        ->from('App\Entity\User', 'u')
                        ->innerJoin('u.idCargo', 'c')
                        ->innerJoin('u.idestructura', 'e')
                        //->where('c.nivel = :nivel')
                        ->Where('e.id = :estructuraId')
                        ->andWhere($qb->expr()->in('c.id', ':cargoIds')) // 👈 AQUÍ el cambio importante
                        ->andWhere('u.id != :userRef')
                        ->andWhere('u.idStatus = :statusActivo') // 👈 Usando parámetro
                        //->setParameter('nivel', $nivelId)
                        ->setParameter('estructuraId', $estructuraId)
                        ->setParameter('cargoIds', $cargoIds)
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->setParameter('statusActivo', 1) // 👈 Parámetro con nombre descriptivo
                        ->orderBy('c.nivel', 'ASC');

                    
                        $usuariosFiltrados = $qb->getQuery()->getResult();
                   }

                    $contSinResp=0;
                if (!empty($usuariosFiltrados)) {
                   $datosUsuarios = [];
                   foreach ($usuariosFiltrados as $usuarioAsignado) {
                        /* $sqlUser = " SELECT respondida FROM instrumento360 a INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id WHERE b.user_id = ".$usuarioAsignado['id_user']; */

                        $sqlUser = "SELECT respondida 
                        FROM instrumento360 a 
                        INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id 
                        WHERE b.user_id = ".$usuarioAsignado['id_user']." 
                        AND b.instrumento360_id = ".$valor->getId();

                        $conn = $this->getEntityManager()->getConnection();
                        $stmt = $conn->prepare($sqlUser);
                        $stmt->execute();
                       $dataUserRespondida=$stmt->fetchAll();
                       $resp=0;
                       if (!empty($dataUserRespondida)) {
                            if ( $dataUserRespondida[0]["respondida"]==0) {
                            $contSinResp++;
                            }
                            $resp = $dataUserRespondida[0]["respondida"];
                       }else{
                        $contSinResp++;
                       }
                       $rolesArray = json_decode($usuarioAsignado['roles'], true);
                       $datosUsuarios[] = [
                            'usuarioId'    => $usuarioAsignado['id_user'],
                            'cargoId'      => $usuarioAsignado['idcargo'],
                            'cargoNombre'      => $usuarioAsignado['cargo'],
                            'respondida'   => $resp,
                            'roles'   => $rolesArray
                        ];
                    }

                    
            
                   $evaluacionDto =new Instrumento360OutPutDto();

                   $evaluacionDto->userIfEvaluating=$contSinResp;

                   $evaluacionDto->instrumento360UsuariosAsignados=$datosUsuarios;


                    $evaluacionDto->id=$valor->getId();
                    $evaluacionDto->nombre=$valor->getNombre();
                    $evaluacionDto->descripcion=$valor->getDescripcion();

                    $evaluacionDto->idTipoUnidad=($valor->getTipoUnidad()!=null)?array("id"=>$valor->getTipoUnidad()->getId(),"Nombre"=>$valor->getTipoUnidad()->getNombre(),"Factor"=>$valor->getTipoUnidad()->getFactor()):[];
                    $evaluacionDto->unidad=$valor->getTipounidad();

                    $evaluacionDto->tipoInstrumento=($valor->getTipoInstrumento()!=null)?array("id"=>$valor->getTipoInstrumento()->getId(),"Nombre"=>$valor->getTipoInstrumento()->getNombre()):[];

                    //$evaluacionDto->path=$valor->getPath();
                    //$evaluacionDto->orden=$valor->getOrden();;

                    $evaluacionDto->fechaPublicacion=!is_null($valor->getFechaPublicacion())?$valor->getFechaPublicacion()->format("Y-m-d"):null;
                    $evaluacionDto->createAt=!is_null($valor->getCreateAt())?$valor->getCreateAt()->format("Y-m-d"):null;
                    $evaluacionDto->fechaVigencia=$valor->getFechaVigencia()->format("Y-m-d");
                    $evaluacionDto->publicar=$valor->getPublicar();

                    $evaluacionDto->questionsByCategory=$valor->getQuestionsByCategory();
                    $evaluacionDto->puntosGlobales=$valor->getPuntosGlobales();

                    $evaluacionDto->editable=1;

                    $entity= $this->getEntityManager()->createQueryBuilder();
                    
                     $EvaluacionDataRespondida= $entity->select("a,q")
                        ->from("App\Entity\Instrumento360\Instrumento360","a")
                        ->innerJoin('a.instrumento360UsuariosAsignados', 'q')
                        ->Where('q.respondida=1')
                        ->andWhere('a.id='.$valor->getId())
                        ->orderBy('a.id', 'ASC')
                        ->getQuery()
                        ->getResult();
                    /*
                    $EvaluacionDataNoRespondida= $entity->select("b,h")
                        ->from("App\Entity\Instrumento360\Instrumento360","b")
                        ->innerJoin('b.instrumento360UsuariosAsignados', 'h')
                        ->Where('h.respondida=0')
                        ->andWhere('b.id='.$valor->getId())
                        ->orderBy('b.id', 'ASC')
                        ->getQuery()
                        ->getResult(); */

                    $sqlUser = " SELECT count(*) as total FROM instrumento360 a INNER JOIN instrumento360_usuarios_asignados b 
                    ON a.id = b.instrumento360_id WHERE b.respondida = 0 AND a.id = ".$valor->getId() ." AND b.user_id <> ".$this->security->getUser()->getId();
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

                    //$evaluacionDto->userIfEvaluating=$dataUserCount[0]["total"];
                    $dataevaluacion[]=$evaluacionDto;
                }


          }

        }



       return array("count"=>count($paginatorTotalCount),"data"=>$dataevaluacion);
 
    }    

    public function findById1($id){
        $entityManager = $this->getEntityManager();        
        $entity= $this->getEntityManager()->createQueryBuilder();
        /* $EvaluacionData= $entity->select("a,q,x,f,r")
            ->from("App\Entity\Evaluacion\Evaluacion","a")
            ->leftJoin('a.evaluacionUsuarios', 'q')
            ->leftJoin('q.idUser', 'x')
            ->leftjoin('a.seccions', 'f')
            ->leftJoin('a.evaluatorUserId', 'r')
            ->andWhere('a.id='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult(); */

            $EvaluacionData= $entity->select('a, q, x, f')
            ->from('App\Entity\Instrumento360\Instrumento360', 'a')
            ->leftJoin('a.instrumento360UsuariosAsignados', 'q')
            ->leftJoin('q.user', 'x')
            ->leftJoin('a.seccions', 'f')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();


            $dataEvaluacion=null;
        foreach($EvaluacionData as $clave=>$valor){

            //$epp1 = $valor->getInstrumento360UsuariosAsignados();
            //$epp = $valor->getInstrumento360UsuariosAsignados()->getUserEvaluador();

            //******  Usuarios ************/ 
            $qb="";
            //Supervisorio Dec
           if($valor->getTipoInstrumento()->getId() ==1){ 

                 $nivelId = $this->getEntityManager()->createQueryBuilder()
                        ->select('IDENTITY(c.nivel)')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();

                        $estructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'e.id = u.idestructura')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getOneOrNullResult();

                    $cargoIdsRaw = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id_jerarquia_ascendente')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getScalarResult();

                    // Convertir a array plano
                    $cargoIds = array_column($cargoIdsRaw, 'id_jerarquia_ascendente');
                    // Extraer el string JSON del primer elemento
                    $jsonString = $cargoIds[0];
                    // Decodificar a array
                    $cargoIds = json_decode($jsonString, true);
                    
                                        $cargoId = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();

                    if($cargoId ==1 or $cargoId ==2 or $cargoId ==3 or $cargoId ==4 or $cargoId ==5 ){  

                         if($cargoId ==1){  
                           $cargoId = $cargoIds; 
                         }else{
                           $cargoId = $cargoId +1;
                         }


                        $todasestructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->where('e.padre_id = :Refpadre')
                        ->andWhere('e.id != :estructuraId')
                        ->setParameter('Refpadre', $estructuraId->getId())
                        ->setParameter('estructuraId', $estructuraId->getId())
                        ->getQuery()
                        ->getResult(); // devuelve un array de entidades
                        
                        $estructuraFId  = []; // inicializamos el array

                        foreach ($todasestructuraId as $estructura) {
                            // $estructura es un objeto EstructuraOrganizativa
                            $estructuraFId[] = $estructura->getId(); // acumulamos cada ID
                        }

                        $qb = $this->getEntityManager()->createQueryBuilder();
                        $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username, u.username,u.roles,
                                    c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                    e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                        ->from('App\Entity\User', 'u')
                        ->innerJoin('u.idCargo', 'c')
                        ->innerJoin('u.idestructura', 'e')
                        //->where('c.nivel = :nivel')
                        ->Where('e.id IN (:estructuraId)')
                        ->andWhere($qb->expr()->in('c.id', ':cargoIds')) // 👈 AQUÍ el cambio importante
                        ->andWhere('u.id != :userRef')
                        ->andWhere('u.idStatus = :statusActivo') // 👈 Usando parámetro
                        //->setParameter('nivel', $nivelId)
                        ->setParameter('estructuraId', $estructuraFId)
                        ->setParameter('cargoIds', $cargoId)
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->setParameter('statusActivo', 1) // 👈 Parámetro con nombre descriptivo
                        ->orderBy('c.nivel', 'ASC');

                         $resultado = $qb->getQuery()->getResult();
                        //$usuariosFiltrados = $qb->getQuery()->getResult();



                    }else{ 



                        //$cargoIds = [4,5]; // 4 Gerente de Linea / 5 Coordinador IDs de los cargos que deseas incluir
                        //$cargoIds = [4,5,6,7,8,9,10,11,12]; // IDs de los cargos que deseas incluir
                        $qb = $this->getEntityManager()->createQueryBuilder();
                        $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username,u.roles,
                                    c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                    e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                        ->from('App\Entity\User', 'u')
                        ->innerJoin('u.idCargo', 'c')
                        ->innerJoin('u.idestructura', 'e')
                        //->where('c.nivel = :nivel')
                        ->andWhere('e.id = :estructuraId')
                        ->andWhere($qb->expr()->in('c.id', ':cargoIds')) // 👈 AQUÍ el cambio importante
                        ->andWhere('u.id != :userRef')
                        ->andWhere('u.idStatus = :statusActivo') // 👈 Usando parámetro
                        //->setParameter('nivel', $nivelId)
                        ->setParameter('estructuraId', $estructuraId)
                        ->setParameter('cargoIds', $cargoIds)
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->setParameter('statusActivo', 1) // 👈 Parámetro con nombre descriptivo
                        ->orderBy('c.nivel', 'ASC');

                        $resultado = $qb->getQuery()->getResult();
                        //dd($resultado);
                      }
                         $ver = 1222;
                         //Supervisorio Dec

                   //Supervisorio Asc
                  }elseif($valor->getTipoInstrumento()->getId() ==4){ 

                      $nivelId = $this->getEntityManager()->createQueryBuilder()
                        ->select('IDENTITY(c.nivel)')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();

                        $estructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'e.id = u.idestructura')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getOneOrNullResult();

                    $cargoIdsRaw = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id_jerarquia_descendente')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getScalarResult();

                    // Convertir a array plano
                    $cargoIds = array_column($cargoIdsRaw, 'id_jerarquia_descendente');
                    // Extraer el string JSON del primer elemento
                    $jsonString = $cargoIds[0];
                    // Decodificar a array
                    $cargoIds = json_decode($jsonString, true);
                    
                    $cargoId = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();

                    if($cargoId ==2 or $cargoId ==3 or $cargoId ==4 or $cargoId ==5 ){  

                         
                        if($estructuraId->getPadreId() ==1){
                           $cargoId = 1;  
                        }else{
                           $cargoId = $cargoId -1;
                        }


                         $todasestructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->where('e.id = :Refpadre')
                        //->andWhere('e.id != :estructuraId')
                        ->setParameter('Refpadre', $estructuraId->getPadreId())
                        //->setParameter('estructuraId', $estructuraId->getId())
                        ->getQuery()
                        ->getResult(); // devuelve un array de entidades

                                                
                        $estructuraFId  = []; // inicializamos el array

                        foreach ($todasestructuraId as $estructura) {
                            // $estructura es un objeto EstructuraOrganizativa
                            $estructuraFId[] = $estructura->getId(); // acumulamos cada ID
                        }

                        $qb = $this->getEntityManager()->createQueryBuilder();
                        $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username, u.username,u.roles,
                                    c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                    e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                        ->from('App\Entity\User', 'u')
                        ->innerJoin('u.idCargo', 'c')
                        ->innerJoin('u.idestructura', 'e')
                        //->where('c.nivel = :nivel')
                        ->Where('e.id IN (:estructuraId)')
                        ->andWhere($qb->expr()->in('c.id', ':cargoIds')) // 👈 AQUÍ el cambio importante
                        ->andWhere('u.id != :userRef')
                        ->andWhere('u.idStatus = :statusActivo') // 👈 Usando parámetro
                        //->setParameter('nivel', $nivelId)
                        ->setParameter('estructuraId', $estructuraFId)
                        ->setParameter('cargoIds', $cargoId)
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->setParameter('statusActivo', 1) // 👈 Parámetro con nombre descriptivo
                        ->orderBy('c.nivel', 'ASC');

                         $resultado = $qb->getQuery()->getResult();
                        //$usuariosFiltrados = $qb->getQuery()->getResult();



                    }else{ 



                        //$cargoIds = [4,5]; // 4 Gerente de Linea / 5 Coordinador IDs de los cargos que deseas incluir
                        //$cargoIds = [4,5,6,7,8,9,10,11,12]; // IDs de los cargos que deseas incluir
                        $qb = $this->getEntityManager()->createQueryBuilder();
                        $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username,u.roles,
                                    c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                    e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                        ->from('App\Entity\User', 'u')
                        ->innerJoin('u.idCargo', 'c')
                        ->innerJoin('u.idestructura', 'e')
                        //->where('c.nivel = :nivel')
                        ->andWhere('e.id = :estructuraId')
                        ->andWhere($qb->expr()->in('c.id', ':cargoIds')) // 👈 AQUÍ el cambio importante
                        ->andWhere('u.id != :userRef')
                        ->andWhere('u.idStatus = :statusActivo') // 👈 Usando parámetro
                        //->setParameter('nivel', $nivelId)
                        ->setParameter('estructuraId', $estructuraId)
                        ->setParameter('cargoIds', $cargoIds)
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->setParameter('statusActivo', 1) // 👈 Parámetro con nombre descriptivo
                        ->orderBy('c.nivel', 'ASC');

                        $resultado = $qb->getQuery()->getResult();
                        //dd($resultado);
                      }


                  //Autoevaluacion
                  }elseif($valor->getTipoInstrumento()->getId() ==2){       
                        $estructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'e.id = u.idestructura')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getOneOrNullResult();

                        //return array("data"=>$estructuraId);

                   // Consulta principal
                    $qb = $this->getEntityManager()->createQueryBuilder();
                    $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username,u.roles,
                                c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                    ->from('App\Entity\User', 'u')
                    ->innerJoin('u.idCargo', 'c')
                    ->innerJoin('u.idestructura', 'e')
                    ->where('u.id = :userRef')
                    ->andWhere('e.id = :estructuraId')
                    ->setParameter('userRef', $this->security->getUser()->getId())
                    ->setParameter('estructuraId', $estructuraId)
                    ->orderBy('c.nivel', 'ASC');

                    $contSinResp=0;
                    $usuariosFiltrados = $qb->getQuery()->getResult();
                     $datosUsuarios = [];
                   foreach ($usuariosFiltrados as $usuarioAsignado) {
                      /*   $sqlUser = " SELECT respondida FROM instrumento360 a INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id WHERE b.user_id = ".$usuarioAsignado['id_user']; */

                        $sqlUser = "SELECT respondida 
                        FROM instrumento360 a 
                        INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id 
                        WHERE b.user_id = ".$usuarioAsignado['id_user']." 
                        AND b.instrumento360_id = ".$id;


                        $conn = $this->getEntityManager()->getConnection();
                        $stmt = $conn->prepare($sqlUser);
                        $stmt->execute();
                       $dataUserRespondida=$stmt->fetchAll();
                       $resp=0;
                       if (!empty($dataUserRespondida)) {
                           if ( $dataUserRespondida[0]["respondida"]==0) {
                               $contSinResp++;
                           }
                            $resp = $dataUserRespondida[0]["respondida"];
                       }else{
                           $contSinResp++;
                       }

                       
                       /* if (!empty($dataUserRespondida)) {
                            $resp = $dataUserRespondida[0]["respondida"];
                       } */
                       $rolesArray = json_decode($usuarioAsignado['roles'], true);
                       $und= array("id"=>$usuarioAsignado['id_estructura'],"label"=>$usuarioAsignado['estructura_organizativa']);                        
                       /* $usersData[]=array("id"=>$instrumentosuser->getUserEvaluador()->getId(),
                       "nombre"=>$instrumentosuser->getUserEvaluador()->getPrimerNombre(). " ".$instrumentosuser->getUserEvaluador()->getPrimerApellido()
                       ,"email"=>$instrumentosuser->getUserEvaluador()->getEmail()
                        ,"respondida"=>$instrumentosuser->getRespondida(),"roles"=>$instrumentosuser->getUserEvaluador()->getRoles());                        */

                       $datosUsuarios[] = [
                            'usuarioId'    => $usuarioAsignado['id_user'],
                            "nombre"=> $usuarioAsignado['primerNombre']. " ". $usuarioAsignado['primerApellido'],
                            "email" => $usuarioAsignado['username'],
                            'cargoId'      => $usuarioAsignado['idcargo'],
                            'cargoNombre'      => $usuarioAsignado['cargo'],
                            'respondida'   => $resp,
                            'roles'   => $rolesArray,
                            'unidad' => $und                        
                        ];

                    } 

                  //Pares
                  }elseif($valor->getTipoInstrumento()->getId() ==3){    
                    /* $nivelId = $this->getEntityManager()->createQueryBuilder()
                        ->select('IDENTITY(c.nivel)')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();

                        $estructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'e.id = u.idestructura')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getOneOrNullResult();

                    $cargoId = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();


                    // Consulta principal
                    $qb = $this->getEntityManager()->createQueryBuilder();
                    $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username,u.roles,
                                c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                    ->from('App\Entity\User', 'u')
                    ->innerJoin('u.idCargo', 'c')
                    ->innerJoin('u.idestructura', 'e')

                    ->leftJoin('a.seccions', 'f')

                    ->where('c.nivel = :nivel')
                    ->andWhere('e.id = :estructuraId')
                    ->andWhere('c.id = :cargoId')
                    ->andwhere('u.id != :userRef')
                    ->setParameter('nivel', $nivelId)
                    ->setParameter('estructuraId', $estructuraId)
                    ->setParameter('cargoId', $cargoId)
                    ->setParameter('userRef', $this->security->getUser()->getId())
                    ->orderBy('c.nivel', 'ASC'); */

                    $cargoId = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();

                    $nivelId = $this->getEntityManager()->createQueryBuilder()
                        ->select('IDENTITY(c.nivel)')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();

                        $estructuraId = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'e.id = u.idestructura')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getOneOrNullResult();

                    /* $cargoIdsRaw = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id_jerarquia_ascendente')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getScalarResult(); */

                    // Convertir a array plano
                    /* $cargoIds = array_column($cargoIdsRaw, 'id_jerarquia_ascendente');
                    // Extraer el string JSON del primer elemento
                    $jsonString = $cargoIds[0];
                    // Decodificar a array
                    $cargoIds = json_decode($jsonString, true); */
                    
                     $cargoId = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();

                       if($cargoId ==2 or $cargoId ==3 or $cargoId ==4 or $cargoId ==5 ){ 
                             
                              $todasestructuraId = $this->getEntityManager()->createQueryBuilder()
                            ->select('e')
                            ->from('App\Entity\EstructuraOrganizativa', 'e')
                            ->where('e.padre_id = :Refpadre')
                            ->andWhere('e.id != :estructuraId')
                            ->setParameter('Refpadre', $estructuraId->getPadreId())
                            ->setParameter('estructuraId', $estructuraId->getId())
                            ->getQuery()
                            ->getResult();

                        $estructuraFId = [];
                        foreach ($todasestructuraId as $estructura) {
                            $estructuraFId[] = $estructura->getId();
                        }

                        $qb = $this->getEntityManager()->createQueryBuilder();
                        $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username, u.roles,
                                    c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                    e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                        ->from('App\Entity\User', 'u')
                        ->innerJoin('u.idCargo', 'c')
                        ->innerJoin('u.idestructura', 'e')
                        ->where('c.nivel = :nivel')
                        ->andWhere($qb->expr()->in('e.id', ':estructuraIds'))
                        ->andWhere('c.id = :cargoId')
                        ->andWhere('u.id != :userRef')
                        ->andWhere('u.idStatus = :statusActivo') // 👈 Usando parámetro
                        ->setParameter('nivel', $nivelId)
                        ->setParameter('estructuraIds', $estructuraFId, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY) // 👈 importante
                        ->setParameter('cargoId', $cargoId)
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->setParameter('statusActivo', 1) // 👈 Parámetro con nombre descriptivo
                        ->orderBy('c.nivel', 'ASC');

                        $usuariosFiltrados = $qb->getQuery()->getResult();

                       }else{   

                        
                        //$cargoIds = [4,5]; // 4 Gerente de Linea / 5 Coordinador IDs de los cargos que deseas incluir
                        //$cargoIds = [4,5,6,7,8,9,10,11,12]; // IDs de los cargos que deseas incluir
                        $qb = $this->getEntityManager()->createQueryBuilder();
                        $qb->select('u.id AS id_user, u.numeroDocumento, u.primerNombre, u.primerApellido, u.username,u.roles,
                                    c.id AS idcargo, c.descripcion AS cargo, IDENTITY(c.nivel) AS id_nivelcargo,
                                    e.id AS id_estructura, e.estructura_organizativa, e.jerarquia')
                        ->from('App\Entity\User', 'u')
                        ->innerJoin('u.idCargo', 'c')
                        ->innerJoin('u.idestructura', 'e')
                        //->where('c.nivel = :nivel')
                        ->andWhere('e.id = :estructuraId')
                        ->andWhere($qb->expr()->in('c.id', ':cargoIds')) // 👈 AQUÍ el cambio importante
                        ->andWhere('u.id != :userRef')
                        ->andWhere('u.idStatus = :statusActivo') // 👈 Usando parámetro
                        //->setParameter('nivel', $nivelId)
                        ->setParameter('estructuraId', $estructuraId)
                        ->setParameter('cargoIds', $cargoId)
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->setParameter('statusActivo', 1) // 👈 Parámetro con nombre descriptivo
                        ->orderBy('c.nivel', 'ASC');

                         $usuariosFiltrados = $qb->getQuery()->getResult();
                       }
                    $datosUsuarios = [];
                 if (!empty($usuariosFiltrados)) {
                   
                   foreach ($usuariosFiltrados as $usuarioAsignado) {
                        /* $sqlUser = " SELECT respondida FROM instrumento360 a INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id WHERE b.user_id = ".$usuarioAsignado['id_user']; */

                         $sqlUser = "SELECT respondida 
                        FROM instrumento360 a 
                        INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id 
                        WHERE b.user_id = ".$usuarioAsignado['id_user']." 
                        AND b.instrumento360_id = ".$id;

                        $conn = $this->getEntityManager()->getConnection();
                        $stmt = $conn->prepare($sqlUser);
                        $stmt->execute();
                       $dataUserRespondida=$stmt->fetchAll();
                       $resp=0;
                       if (!empty($dataUserRespondida)) {
                            $resp = $dataUserRespondida[0]["respondida"];
                       }
                       $rolesArray = json_decode($usuarioAsignado['roles'], true);
                       $und= array("id"=>$usuarioAsignado['id_estructura'],"label"=>$usuarioAsignado['estructura_organizativa']);                        
                       $datosUsuarios[] = [
                            'usuarioId'    => $usuarioAsignado['id_user'],
                            "nombre"=> $usuarioAsignado['primerNombre']. " ". $usuarioAsignado['primerApellido'],
                            "email" => $usuarioAsignado['username'],
                            'cargoId'      => $usuarioAsignado['idcargo'],
                            'cargoNombre'      => $usuarioAsignado['cargo'],
                            'respondida'   => $resp,
                            'roles'   => $rolesArray,
                            'unidad' => $und                        
                        ];
                       /* $datosUsuarios[] = [
                            'usuarioId'    => $usuarioAsignado['id_user'],
                            'cargoId'      => $usuarioAsignado['idcargo'],
                            'cargoNombre'      => $usuarioAsignado['cargo'],
                            'respondida'   => $resp,
                            'roles'   => $rolesArray
                        ]; */
                    }   

                  }
                }

                   $usuariosFiltrados = $qb->getQuery()->getResult();
                  $datosUsuarios = [];
                  $contSinResp=0;
                if (!empty($usuariosFiltrados)) {
                   
                   foreach ($usuariosFiltrados as $usuarioAsignado) {
                        /* $sqlUser = " SELECT respondida FROM instrumento360 a INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id WHERE b.user_id = ".$usuarioAsignado['id_user']; */

                         $sqlUser = "SELECT respondida 
                        FROM instrumento360 a 
                        INNER JOIN instrumento360_usuarios_asignados b 
                        ON a.id = b.instrumento360_id 
                        WHERE b.user_id = ".$usuarioAsignado['id_user']." 
                        AND b.instrumento360_id = ".$id;

                        $conn = $this->getEntityManager()->getConnection();
                        $stmt = $conn->prepare($sqlUser);
                        $stmt->execute();
                       $dataUserRespondida=$stmt->fetchAll();
                       $resp=0;
                       if (!empty($dataUserRespondida)) {
                            if ( $dataUserRespondida[0]["respondida"]==0) {
                               $contSinResp++;
                           }
                            $resp = $dataUserRespondida[0]["respondida"];
                       }else{
                           $contSinResp++;
                       }
                       $rolesArray = json_decode($usuarioAsignado['roles'], true);
                       $und= array("id"=>$usuarioAsignado['id_estructura'],"label"=>$usuarioAsignado['estructura_organizativa']);                        
                       $datosUsuarios[] = [
                            'usuarioId'    => $usuarioAsignado['id_user'],
                            "nombre"=> $usuarioAsignado['primerNombre']. " ". $usuarioAsignado['primerApellido'],
                            "email" => $usuarioAsignado['username'],
                            'cargoId'      => $usuarioAsignado['idcargo'],
                            'cargoNombre'      => $usuarioAsignado['cargo'],
                            'respondida'   => $resp,
                            'roles'   => $rolesArray,
                            'unidad' => $und                        
                        ];
                       /* $datosUsuarios[] = [
                            'usuarioId'    => $usuarioAsignado['id_user'],
                            'cargoId'      => $usuarioAsignado['idcargo'],
                            'cargoNombre'      => $usuarioAsignado['cargo'],
                            'respondida'   => $resp,
                            'roles'   => $rolesArray
                        ]; */
                    }
                } 

            //************************** */
            $instrumentoDto =new Instrumento360OutPutDto();
            $instrumentoDto->userIfEvaluating=$contSinResp;
            $instrumentoDto->users=$datosUsuarios;

            $instrumentoDto->id=$valor->getId();
            $instrumentoDto->nombre=$valor->getNombre();
            $instrumentoDto->descripcion=$valor->getDescripcion();
            //$instrumentoDto->duracion=$valor->getDuration();
            $instrumentoDto->publicar=!is_null($valor->getPublicar())?$valor->getPublicar():0;
            $instrumentoDto->questionsByCategory= !is_null($valor->getQuestionsByCategory())?$valor->getQuestionsByCategory():0;
            //$instrumentoDto->unidad=$valor->getUnidad();
            //$instrumentoDto->path=$valor->getPath();
            
            $instrumentoDto->idTipoUnidad=($valor->getTipounidad()!=null)?array("id"=>$valor->getTipounidad()->getId(),"Descripcion"=>$valor->getTipounidad()->getNombre()):[];
            
            //$instrumentoDto->idTipoUnidad=($valor->getIdTipoUnidad()!=null)?array("id"=>$valor->getIdTipoUnidad()->getId(),"Descripcion"=>$valor->getIdTipoUnidad()->getNombre()):[];

            $instrumentoDto->unidad=$valor->getTipounidad();
            $instrumentoDto->idTipoUnidad=($valor->getTipoUnidad()!=null)?array("id"=>$valor->getTipoUnidad()->getId(),"Nombre"=>$valor->getTipoUnidad()->getNombre(),"Factor"=>$valor->getTipoUnidad()->getFactor()):[];
            
            $instrumentoDto->tipoInstrumento=($valor->getTipoInstrumento()!=null)?array("id"=>$valor->getTipoInstrumento()->getId(),"Nombre"=>$valor->getTipoInstrumento()->getNombre()):[];


            //$instrumentoDto->statusId=($valor->getStatusId()!=null)?array("id"=>$valor->getStatusId()->getId(),"Descripcion"=>$valor->getStatusId()->getDescripcion()):[];
            $instrumentoDto->puntosGlobales= $valor->getPuntosGlobales();

            /* $instrumentoDto->evaluatorUserId=($valor->getEvaluatorUserId()!=null)?$valor->getEvaluatorUserId()->getId():null;
            $instrumentoDto->evaluatorFullName=($valor->getEvaluatorUserId()!=null)?$valor->getEvaluatorUserId()->getPrimerNombre()." ".$valor->getEvaluatorUserId()->getSegundoNombre()." ".$valor->getEvaluatorUserId()->getPrimerApellido():null;
            $instrumentoDto->evaluatorEmail=($valor->getEvaluatorUserId()!=null)?$valor->getEvaluatorUserId()->getEmail():null; */

            /* $fff =  $valor->getInstrumento360UsuariosAsignados();
            $ff = $fff[0]->getUserEvaluador()->getRoles(); */
             
            foreach ($valor->getInstrumento360UsuariosAsignados() as $asignado) {
                $instrumentoDto->evaluatorUserId=($asignado->getUserEvaluador()!=null)?$asignado->getUserEvaluador()->getId():null;
                $instrumentoDto->evaluatorFullName=($asignado->getUserEvaluador()!=null)?$asignado->getUserEvaluador()->getPrimerNombre()." ".$asignado->getUserEvaluador()->getSegundoNombre()." ".$asignado->getUserEvaluador()->getPrimerApellido():null;
                $instrumentoDto->evaluatorEmail=($asignado->getUserEvaluador()!=null)?$asignado->getUserEvaluador()->getEmail():null;
                
                //$epp = $asignado->getUserEvaluador()->getId();
                //$evaluatorEmail=($asignado->getUserEvaluador()!=null)?$asignado->getUserEvaluador()->getEmail():null;
                // Aquí puedes trabajar con $epp como necesites
            }

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

            $Roles =  $valor->getInstrumento360UsuariosAsignados();
            //$ff = $fff[0]->getUserEvaluador()->getRoles();
            
            $roles=[];
            if (isset($Roles[0]) && $Roles[0]->getUserEvaluador() !== null) {
                foreach($Roles[0]->getUserEvaluador()->getRoles() as $valorRol){
                    $roles[]= $valorRol;
                    //$roles[]= $valorRol->getDescripcion();            
                }
            }
            $instrumentoDto->roles=trim(json_encode($roles),'"');
            $instrumentoDto->roles=$roles;
            $usersData=[];
            $editable=1;
            if($instrumentoDto->publicar==1){
                $editable=0;
            }


            //$valor->getInstrumento360UsuariosAsignados()

            if($valor->getInstrumento360UsuariosAsignados()!=null){
                foreach($valor->getInstrumento360UsuariosAsignados() as $instrumentosuser){
                        if($instrumentosuser->getRespondida()==1){
                            $editable=0;
                        }

                        /* $instrumentoDto->evaluatorUserId=($asignado->getUserEvaluador()!=null)?$asignado->getUserEvaluador()->getId():null;
                        $instrumentoDto->evaluatorFullName=($asignado->getUserEvaluador()!=null)?$asignado->getUserEvaluador()->getPrimerNombre()." ".$asignado->getUserEvaluador()->getSegundoNombre()." ".$asignado->getUserEvaluador()->getPrimerApellido():null;
                        $instrumentoDto->evaluatorEmail=($asignado->getUserEvaluador()!=null)?$asignado->getUserEvaluador()->getEmail():null; */

                        $usersData[]=array("id"=>$instrumentosuser->getUserEvaluador()->getId(),"nombre"=>$instrumentosuser->getUserEvaluador()->getPrimerNombre(). " ".$instrumentosuser->getUserEvaluador()->getPrimerApellido(),"email"=>$instrumentosuser->getUserEvaluador()->getEmail()
                        ,"respondida"=>$instrumentosuser->getRespondida(),"roles"=>$instrumentosuser->getUserEvaluador()->getRoles());                       
                }
            }  

            /* if($valor->getEvaluacionUsuarios()!=null){
                foreach($valor->getEvaluacionUsuarios() as $instrumentosuser){
                        if($instrumentosuser->getRespondida()==1){
                            $editable=0;
                        }
                        $usersData[]=array("id"=>$instrumentosuser->getIdUser()->getId(),"nombre"=>$instrumentosuser->getIdUser()->getPrimerNombre(). " ".$instrumentosuser->getIdUser()->getPrimerApellido(),"email"=>$instrumentosuser->getIdUser()->getEmail()
                        ,"respondida"=>$instrumentosuser->getRespondida(),"roles"=>$instrumentosuser->getIdUser()->getRoles());                       
                }
            }   */
            $instrumentoDto->editable=$editable;
           // $instrumentoDto->users=$usersData;
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
            $seccionesCardinales=array();

            $estructuraId=0;
              $estructuraIdBusc = $this->getEntityManager()->createQueryBuilder()
                        ->select('e')
                        ->from('App\Entity\EstructuraOrganizativa', 'e')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'e.id = u.idestructura')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getOneOrNullResult();
                         if ($estructuraIdBusc !== null) {
                             $estructuraId = $estructuraIdBusc->getId(); // o cualquier otro getter que necesites
                         }


                    $cargoIdBusc = $this->getEntityManager()->createQueryBuilder()
                        ->select('c.id')
                        ->from('App\Entity\Cargo', 'c')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'c.id = u.idCargo')
                        ->where('u.id = :userRef')
                        ->setParameter('userRef', $this->security->getUser()->getId())
                        ->getQuery()
                        ->getSingleScalarResult();
                    
        

       /*  $EvaluacionData1 = $entityManager->createQueryBuilder()
            ->select('DISTINCT c.id, ccu.unidadId, i.id AS idinstrumento360, i.nombre AS nombreinstrumento, i.tipoInstrumentoId, p.seccionId, p.pregunta, p.orden, c.nombre AS nombrecompetencia360, c.tipo')
            ->from('App\Entity\Instrumento360\Instrumento360', 'i')
            ->join('i.preguntas', 'p')
            ->join('p.competencia', 'c')
            ->join('c.competenciaCargoUnidads', 'ccu')
            ->where('i.id = :id')
            ->andWhere('ccu.unidadId = :unidad')
            ->setParameter('id', 10)
            ->setParameter('unidad', 3)
            ->getQuery()
            ->getResult(); */


            /* $EvaluacionData1 = "select DISTINCT c.id, unidad_id, a.id idinstrumento360, a.nombre as nombreinstrumento, a.tipo_instrumento_id, 
            b.id as idpregunta, b.seccion_id, sc.nombre, sc.orden as ordenseccion, b.pregunta, b.orden, d.cargo_id,
            c.nombre as competencia, c.tipo from instrumento360 a JOIN seccion_evaluacion360 sc 
            ON sc.instrumento360_id = a.id inner join pregunta_evaluacion360 b on a.id = b.id_instrumento_id 
            left join competencia360 c on c.id = b.id_categoria_id left join competencia_cargo_unidad d on c.id =
            d.competencia_id where a.id = ".$id." AND unidad_id = ".$estructuraId." "; */
           //Tecnicas ****************************************************************************


            $EvaluacionData1 = "SELECT DISTINCT 
                c.id,
                ccu.unidad_id,
                i.id as idinstrumento360,
                i.nombre as nombreinstrumento,
                i.tipo_instrumento_id,
                p.id as idpregunta,
                p.seccion_id,
                sc.nombre,
                sc.orden as ordenseccion,
                p.pregunta,
                p.orden,  
                c.nombre as nombrecompetencia360,
                c.tipo

                FROM instrumento360 i
                JOIN pregunta_evaluacion360 p ON i.id = p.id_instrumento_id
                JOIN competencia360 c ON c.id = p.id_categoria_id
                JOIN competencia_cargo_unidad ccu ON ccu.competencia_id = c.id 
                JOIN seccion_evaluacion360 sc ON sc.instrumento360_id = i.id
                WHERE i.id = ".$id." AND ccu.unidad_id = ".$estructuraId."
                GROUP BY c.id, ccu.unidad_id, i.id, i.nombre, i.tipo_instrumento_id, 
                p.id,p.seccion_id,sc.nombre, sc.orden, p.pregunta, p.orden, c.nombre, c.tipo ";

                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($EvaluacionData1);
                $stmt->execute();
                $dataUserRespondida=$stmt->fetchAll();

            $dataEvaluacion=null;
         $idpreguntaft = []; // 👈 Esto es lo que faltaba

        foreach($dataUserRespondida as $clave => $valor23){
            $idpreguntaft[] = $valor23['idpregunta'];
        }

        /* foreach($dataUserRespondida as $clave=>$valor1){
            //$idpregunta = $valor1['idpregunta'];
              $seccionId = $valor1['seccion_id'];
             if($valor1["seccion_id"]!=null){
                //foreach($valor1->getSeccions() as $seccion){
                    //$preguntas= $entityManager->getRepository(PreguntaEvaluacion360::class)->findByIdEvaluacionAndSeccion($id,$seccion->getId());
                    $idpregunta = $idpreguntaft;
                    $preguntas= $entityManager->getRepository(PreguntaEvaluacion360::class)->findByIdEncuestaAndSeccionT($id,$valor1['seccion_id'],$idpregunta);

                    $secciones[]=array(
                        "id"=>$seccionId,
                        "nombre"=>$valor1['nombre'],  
                        "orden"=>$valor1['ordenseccion'],
                        "preguntas"=>$preguntas);
            }
           break;
        } */
        //****************************************************************************************************
        //****************************************************************************************************
        // Cardinales ****************************************************************************

           $EvaluacionDataCardinal = "select
                i.id as idinstrumento360,
                i.nombre as nombreinstrumento,
                c.id as id_competencia360, 
                c.empresa_id, 
                c.nombre as nombrecompetencia, 
                c.descripcion, 
                c.tipo, 
                c.escala_ponderacion,
                p.id as idpregunta,
                p.seccion_id,
                sc.nombre,
                sc.orden as ordenseccion,
                p.pregunta,
                p.orden  
                
            FROM instrumento360 i
            JOIN pregunta_evaluacion360 p ON i.id = p.id_instrumento_id
            JOIN competencia360 c ON c.id = p.id_categoria_id
            JOIN seccion_evaluacion360 sc ON sc.instrumento360_id = i.id 
            WHERE
                c.tipo ='".'Cardinal'."' AND i.id = ".$id." ";

                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($EvaluacionDataCardinal);
                $stmt->execute();
                $dataUserRespondidaCardinal=$stmt->fetchAll();

            $dataEvaluacion=null;

        //$idpreguntaf = []; // 👈 Esto es lo que faltaba

        foreach($dataUserRespondidaCardinal as $clave => $valor22){
            $idpreguntaft[] = $valor22['idpregunta'];
        }

        foreach($dataUserRespondidaCardinal as $clave=>$valor2){
            //$idpregunta = $valor2['idpregunta'];
            $seccionId = $valor2['seccion_id'];
            if($valor2["seccion_id"]!=null){
                //foreach($valor1->getSeccions() as $seccion){
                    //$preguntas= $entityManager->getRepository(PreguntaEvaluacion360::class)->findByIdEvaluacionAndSeccion($id,$seccion->getId());
                    //$idpregunta1 = [958,959,960,961];
                    $idpregunta = $idpreguntaft;
                    $preguntas= $entityManager->getRepository(PreguntaEvaluacion360::class)->findByIdEncuestaAndSeccionT($id,$valor2['seccion_id'],$idpregunta);
                  $secciones[]=array(
                        "id"=>$seccionId,
                        "nombre"=>$valor2['nombre'],  
                        "orden"=>$valor2['ordenseccion'],
                        "preguntas"=>$preguntas);
            } 
           break;
        }

        //****************************************************************************************************
        //****************************************************************************************************


            /* if($valor->getSeccions()!=null){
                foreach($valor->getSeccions() as $seccion){
                    //$preguntas= $entityManager->getRepository(PreguntaEvaluacion360::class)->findByIdEvaluacionAndSeccion($id,$seccion->getId());

                     $dime= $seccion->getId();

                    $preguntas= $entityManager->getRepository(PreguntaEvaluacion360::class)->findByIdEncuestaAndSeccion($id,$seccion->getId());

                    $secciones[]=array(
                        "id"=>$seccion->getId(),
                        "nombre"=>$seccion->getNombre(),  
                        "orden"=>$seccion->getOrden(),
                        "preguntas"=>$preguntas);
                } 
            } */
            //$instrumentoDto->secciones=$secciones;
            $instrumentoDto->cardinales=$seccionesCardinales;
            $dataEvaluacion[]=$instrumentoDto;              
        }
       return new JsonResponse(['data'=>$dataEvaluacion],200);
    }
    


    public function seccionesUsers($data,$validator,$helper): JsonResponse
{
    $entityManager = $this->getEntityManager();
    $idUser = (int)$data["userId"];  
    $instrumentoId = (int)$data["instrumentoId"];
    $idpreguntaf2 = []; // 👈 Esto es lo que faltaba

    $sql = "SELECT
                i.id AS idinstrumento360,
                i.nombre AS nombreinstrumento,
                c.id AS id_competencia360, 
                c.empresa_id, 
                c.nombre AS nombrecompetencia, 
                c.descripcion, 
                c.tipo, 
                c.escala_ponderacion,
                p.id AS idpregunta,
                p.seccion_id,
                sc.nombre,
                sc.orden AS ordenseccion,
                p.pregunta,
                p.orden,
                iu.user_id,
                iu.unidad_user_id,
                iu.user_evaluador_id,  
                iu.unidad_evaluador_id,
                iu.cargo_user_id     
            FROM instrumento360 i
            JOIN pregunta_evaluacion360 p ON i.id = p.id_instrumento_id
            JOIN competencia360 c ON c.id = p.id_categoria_id
            JOIN seccion_evaluacion360 sc ON sc.instrumento360_id = i.id 
            JOIN instrumento360_usuarios_asignados iu ON i.id = iu.instrumento360_id
            WHERE c.tipo = 'Cardinal' 
              AND i.id = :instrumentoId 
              AND iu.user_id = :idUser";

    $conn = $entityManager->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute(['instrumentoId' => $instrumentoId, 'idUser' => $idUser]);
    $dataUserRespondidaCardinal = $stmt->fetchAll();

    // Inicializamos el array
    $cargoUsuario=0;
    $id_unidad_usuario=0;
    $seccionId=0;
    $idpreguntaft = [];
    foreach ($dataUserRespondidaCardinal as $valor22) {
        $seccionId = $valor22['seccion_id'];
        $idpreguntaft[] = $valor22['idpregunta'];
        $cargoUsuario=$valor22['cargo_user_id'];
        $id_unidad_usuario=$valor22['unidad_user_id'];

    }
 
    // Eliminar duplicados
    $idpreguntaft_unicos = array_unique($idpreguntaft);

    // Si deseas reindexar el array, puedes usar array_values
    $idpreguntaft_unicos = array_values($idpreguntaft_unicos);
    $idpreguntaft = $idpreguntaft_unicos;
    
     $tipo = utf8_encode("Tecnica"); 
     /* $PreguntasTecnicas = "select
                c.id as id_competencia360, 
                c.empresa_id, 
                c.nombre as nombrecompetencia, 
                c.descripcion, 
                c.tipo, 
                c.escala_ponderacion,
                cu.unidad_id,
                cu.cargo_id,
                p.id as idpregunta,
                p.seccion_id,
                p.pregunta,
                p.orden,
                sc.instrumento360_id
            from pafarco1_giep_stage_360.competencia360 c
            JOIN pafarco1_giep_stage_360.competencia_cargo_unidad cu ON cu.competencia_id = c.id
            JOIN pafarco1_giep_stage_360.pregunta_evaluacion360 p ON p.id_categoria_id = c.id
            JOIN pafarco1_giep_stage_360.seccion_evaluacion360 sc ON sc.id = p.seccion_id
            WHERE c.tipo ='". $tipo ."' AND cu.unidad_id = ".$id_unidad_usuario." AND cu.cargo_id = ".$cargoUsuario." AND p.seccion_id = ".$seccionId."  "; */


            $PreguntasTecnicas = "select
                c.id as id_competencia360, 
                c.empresa_id, 
                c.nombre as nombrecompetencia, 
                c.descripcion, 
                c.tipo, 
                c.escala_ponderacion,
                cu.unidad_id,
                cu.cargo_id,
                p.id as idpregunta,
                p.seccion_id,
                p.pregunta,
                p.orden,
                sc.instrumento360_id
            from competencia360 c
            JOIN competencia_cargo_unidad cu ON cu.competencia_id = c.id
            JOIN pregunta_evaluacion360 p ON p.id_categoria_id = c.id
            JOIN seccion_evaluacion360 sc ON sc.id = p.seccion_id
            WHERE c.tipo ='". $tipo ."' AND cu.unidad_id = ".$id_unidad_usuario." AND cu.cargo_id = ".$cargoUsuario."  ";
            
               $conn2 = $this->getEntityManager()->getConnection();
                $stmt2 = $conn2->prepare($PreguntasTecnicas);
                $stmt2->execute();
                $dataPreguntasTecnicas=$stmt2->fetchAll();

           // $dataEvaluacion=null;

        foreach($dataPreguntasTecnicas as $clave2 => $valor3){
            $idpreguntaft[] = $valor3['idpregunta'];
        }  
        

    $secciones = [];
    foreach ($dataUserRespondidaCardinal as $valor2) {
        $seccionId = $valor2['seccion_id'];
        if ($seccionId !== null) {
            $preguntas = $entityManager
                ->getRepository(PreguntaEvaluacion360::class)
                ->findByIdEncuestaAndSeccionT($instrumentoId, $seccionId, $idpreguntaft);

            $secciones[] = [
                "id"          => $seccionId,
                "nombre"      => $valor2['nombre'],  
                "descripcion" => $valor2['descripcion'],  // 👈 sin tilde
                "orden"       => $valor2['ordenseccion'],
                "preguntas"   => $preguntas
            ];
        }
        break; // 👈 ojo: esto corta el loop en la primera iteración
    }

    return new JsonResponse(['secciones' => $secciones], 200);
}



    /* public function seccionesUsers($data,$validator,$helper): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        $idUser = $data["userIdn"];  
        $instrumentoId = $data["instrumentoId"];
        $EvaluacionDataCardinal = "select
                i.id as idinstrumento360,
                i.nombre as nombreinstrumento,
                c.id as id_competencia360, 
                c.empresa_id, 
                c.nombre as nombrecompetencia, 
                c.descripcion, 
                c.tipo, 
                c.escala_ponderacion,
                p.id as idpregunta,
                p.seccion_id,
                sc.nombre,
                sc.orden as ordenseccion,
                p.pregunta,
                p.orden,
                iu.user_id,
                iu.unidad_user_id,
                iu.unidad_user_id,
                iu.user_evaluador_id,  
                iu.unidad_evaluador_id   
                
            FROM instrumento360 i
            JOIN pregunta_evaluacion360 p ON i.id = p.id_instrumento_id
            JOIN competencia360 c ON c.id = p.id_categoria_id
            JOIN seccion_evaluacion360 sc ON sc.instrumento360_id = i.id 
            JOIN pafarco1_giep.instrumento360_usuarios_asignados iu ON i.id = iu.instrumento360_id
            WHERE
                c.tipo ='".'Cardinal'."' AND i.id = ".$instrumentoId." AND iu.user_id = ".$idUser." ";

                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($EvaluacionDataCardinal);
                $stmt->execute();
                $dataUserRespondidaCardinal=$stmt->fetchAll();
            //$idpreguntaf = []; // 👈 Esto es lo que faltaba

        foreach($dataUserRespondidaCardinal as $clave => $valor22){
            $idpreguntaft[] = $valor22['idpregunta'];
        }
         $secciones=array();
        foreach($dataUserRespondidaCardinal as $clave=>$valor2){
            //$idpregunta = $valor2['idpregunta'];
            $seccionId = $valor2['seccion_id'];
            if($valor2["seccion_id"]!=null){
                //foreach($valor1->getSeccions() as $seccion){
                    //$preguntas= $entityManager->getRepository(PreguntaEvaluacion360::class)->findByIdEvaluacionAndSeccion($id,$seccion->getId());
                    //$idpregunta1 = [958,959,960,961];
                    $idpregunta = $idpreguntaft;
                    $preguntas= $entityManager->getRepository(PreguntaEvaluacion360::class)->findByIdEncuestaAndSeccionT($instrumentoId,$valor2['seccion_id'],$idpregunta);
                  $secciones[]=array(
                        "id"=>$seccionId,
                        "nombre"=>$valor2['nombre'],  
                        "descripción"=>$valor2['descripcion'],  
                        "orden"=>$valor2['ordenseccion'],
                        "preguntas"=>$preguntas);
            } 
           break;
        }
  
         return new JsonResponse(['secciones'=>$secciones],200);
        

    }  */

    /**
     * Publicar Instrumento.
     */
    public function publicar($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Instrumento360::class)->find($id);
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
            ->select('a, f, p, r')
            ->from("App\Entity\Instrumento360\Instrumento360", "a")
            ->leftJoin('a.seccions', 'f')
            ->leftJoin('f.preguntas', 'p')
            ->leftJoin('p.opciones', 'r')
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
//        $newEntity->setOrden(1);
        $newEntity->setPublicar(0);
        
        // 3. Clonar secciones y sus relaciones en una sola transacción
        $em->beginTransaction();
        try {
            $em->persist($newEntity);
            
            // Mapa para rastrear las competencias clonadas
            $competenciaMap = [];
            
            // Clonar secciones
            foreach ($originalEntity->getSeccions() as $seccion) {
                $newSeccion = clone $seccion;
                $newSeccion->setInstrumento($newEntity);
                $em->persist($newSeccion);
                
                // Clonar preguntas
                foreach ($seccion->getPreguntas() as $pregunta) {
                    $newPregunta = clone $pregunta;
                    $newPregunta->setIdInstrumento($newEntity);
                    $newPregunta->setSeccion($newSeccion);
                    
                    // Clonar la competencia si existe y no ha sido clonada antes
                    if ($pregunta->getIdCategoria()) {
                        $newPregunta->setIdCategoria($pregunta->getIdCategoria());
                    }
                    
                    $em->persist($newPregunta);
                    
                    // Clonar opciones
                    foreach ($pregunta->getOpciones() as $opcion) {
                        $newOpcion = clone $opcion;
                        $newOpcion->setIdPregunta($newPregunta);
                        $em->persist($newOpcion);
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

    /**
     * Eliminar sección y todos sus registros relacionados usando Doctrine ORM.
     */
    public function deleteSeccion($seccionId): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        
        // Buscar la sección
        $seccion = $entityManager->getRepository(SeccionEvaluacion360::class)->find($seccionId);
        
        if (!$seccion) {
            return new JsonResponse(['msg' => 'No existe la sección con el id: ' . $seccionId], 404);
        }
        
        // Verificar si el instrumento está publicado
        $instrumento = $seccion->getInstrumento();
        if ($instrumento && $instrumento->getPublicar() == 1) {
            return new JsonResponse(['msg' => 'No se puede eliminar una sección de un instrumento publicado'], 400);
        }
        
        $entityManager->beginTransaction();
        
        try {
            // 1. Eliminar todas las respuestas relacionadas con las preguntas de esta sección
            $preguntas = $seccion->getPreguntas();
            foreach ($preguntas as $pregunta) {
                // Eliminar respuestas de la pregunta
                $respuestas = $pregunta->getRespuestas();
                foreach ($respuestas as $respuesta) {
                    $entityManager->remove($respuesta);
                }
                
                // Eliminar opciones de la pregunta
                $opciones = $pregunta->getOpciones();
                foreach ($opciones as $opcion) {
                    // Eliminar respuestas de la opción
                    $respuestasOpcion = $opcion->getRespuestas();
                    foreach ($respuestasOpcion as $respuestaOpcion) {
                        $entityManager->remove($respuestaOpcion);
                    }
                    $entityManager->remove($opcion);
                }
                
                // Eliminar la pregunta
                $entityManager->remove($pregunta);
            }
            
            // 2. Eliminar la sección
            $entityManager->remove($seccion);
            
            // 3. Hacer commit de la transacción
            $entityManager->flush();
            $entityManager->commit();
            
            return new JsonResponse(['msg' => 'Sección eliminada correctamente con todos sus registros relacionados'], 200);
            
        } catch (\Exception $e) {
            // Rollback en caso de error
            $entityManager->rollback();
            return new JsonResponse(['msg' => 'Error al eliminar la sección: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar pregunta y todos sus registros relacionados usando Doctrine ORM.
     */
    public function deletePregunta($preguntaId): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        
        // Buscar la pregunta
        $pregunta = $entityManager->getRepository(PreguntaEvaluacion360::class)->find($preguntaId);
        
        if (!$pregunta) {
            return new JsonResponse(['msg' => 'No existe la pregunta con el id: ' . $preguntaId], 404);
        }
        
        // Verificar si el instrumento está publicado
        $instrumento = $pregunta->getIdInstrumento();
       
        if ($instrumento && $instrumento->getPublicar() == 1) {
            return new JsonResponse(['msg' => 'No se puede eliminar una pregunta de un instrumento publicado'], 400);
        }
 
        $entityManager->beginTransaction();
        
        try {
            // 1. Eliminar todas las respuestas relacionadas con la pregunta
            $respuestas = $pregunta->getRespuestas();
            foreach ($respuestas as $respuesta) {
                $entityManager->remove($respuesta);
            }
            
            // 2. Eliminar todas las opciones de la pregunta y sus respuestas
            $opciones = $pregunta->getOpciones();
            foreach ($opciones as $opcion) {
                // Eliminar respuestas de la opción
                $respuestasOpcion = $opcion->getRespuestas();
                foreach ($respuestasOpcion as $respuestaOpcion) {
                    $entityManager->remove($respuestaOpcion);
                }
                
                // Eliminar la opción
                $entityManager->remove($opcion);
            }
            
            // 3. Eliminar la pregunta
            $entityManager->remove($pregunta);
            
            // 4. Hacer commit de la transacción
            $entityManager->flush();
            $entityManager->commit();
            
            return new JsonResponse(['msg' => 'Pregunta eliminada correctamente con todos sus registros relacionados'], 200);
            
        } catch (\Exception $e) {
            // Rollback en caso de error
            $entityManager->rollback();
            return new JsonResponse(['msg' => 'Error al eliminar la pregunta: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar opción y todos sus registros relacionados usando Doctrine ORM.
     */
    public function deleteOpcion($opcionId): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        
        // Buscar la opción
        $opcion = $entityManager->getRepository(OpcionesEvaluacion360::class)->find($opcionId);
        
        if (!$opcion) {
            return new JsonResponse(['msg' => 'No existe la opción con el id: ' . $opcionId], 404);
        }
        
        // Verificar si el instrumento está publicado
        $pregunta = $opcion->getIdPregunta();
        $instrumento = $pregunta ? $pregunta->getIdInstrumento() : null;
        
        if ($instrumento && $instrumento->getPublicar() == 1) {
            return new JsonResponse(['msg' => 'No se puede eliminar una opción de un instrumento publicado'], 400);
        }
        
        $entityManager->beginTransaction();
        
        try {
            // 1. Eliminar todas las respuestas relacionadas con la opción
            $respuestas = $opcion->getRespuestas();
            foreach ($respuestas as $respuesta) {
                $entityManager->remove($respuesta);
            }
            
            // 2. Eliminar la opción
            $entityManager->remove($opcion);
            
            // 3. Hacer commit de la transacción
            $entityManager->flush();
            $entityManager->commit();
            
            return new JsonResponse(['msg' => 'Opción eliminada correctamente con todos sus registros relacionados'], 200);
            
        } catch (\Exception $e) {
            // Rollback en caso de error
            $entityManager->rollback();
            return new JsonResponse(['msg' => 'Error al eliminar la opción: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar instrumento360 y todos sus registros relacionados usando Doctrine ORM.
     */
    public function deleteInstrumento360($instrumentoId): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        
        // Buscar el instrumento
        $instrumento = $entityManager->getRepository(Instrumento360::class)->find($instrumentoId);
        
        if (!$instrumento) {
            return new JsonResponse(['msg' => 'No existe el instrumento con el id: ' . $instrumentoId], 404);
        }
        
        // Verificar si el instrumento está publicado
        if ($instrumento->getPublicar() == 1) {
            return new JsonResponse(['msg' => 'No se puede eliminar un instrumento publicado'], 400);
        }
        
        $entityManager->beginTransaction();
        
        try {
            // 1. Eliminar todas las evaluaciones relacionadas con usuarios asignados
            $usuariosAsignados = $instrumento->getInstrumento360UsuariosAsignados();
            foreach ($usuariosAsignados as $usuarioAsignado) {
                // Buscar y eliminar evaluaciones relacionadas
                $evaluaciones = $entityManager->getRepository(Instrumento360Evaluaciones::class)
                    ->findBy(['IdInstrumentoUsuario' => $usuarioAsignado]);
                
                foreach ($evaluaciones as $evaluacion) {
                    $entityManager->remove($evaluacion);
                }
                
                // Eliminar el usuario asignado
                $entityManager->remove($usuarioAsignado);
            }
            
            // 2. Eliminar todas las secciones y sus elementos relacionados
            $secciones = $instrumento->getSeccions();
            foreach ($secciones as $seccion) {
                // Eliminar todas las preguntas de la sección
                $preguntas = $seccion->getPreguntas();
                foreach ($preguntas as $pregunta) {
                    // Eliminar respuestas de la pregunta
                    $respuestas = $pregunta->getRespuestas();
                    foreach ($respuestas as $respuesta) {
                        $entityManager->remove($respuesta);
                    }
                    
                    // Eliminar opciones de la pregunta
                    $opciones = $pregunta->getOpciones();
                    foreach ($opciones as $opcion) {
                        // Eliminar respuestas de la opción
                        $respuestasOpcion = $opcion->getRespuestas();
                        foreach ($respuestasOpcion as $respuestaOpcion) {
                            $entityManager->remove($respuestaOpcion);
                        }
                        
                        // Eliminar la opción
                        $entityManager->remove($opcion);
                    }
                    
                    // Eliminar la pregunta
                    $entityManager->remove($pregunta);
                }
                
                // Eliminar la sección
                $entityManager->remove($seccion);
            }
            
            // 3. Eliminar todas las preguntas directas del instrumento (por si acaso)
            $preguntasDirectas = $instrumento->getPreguntas();
            foreach ($preguntasDirectas as $pregunta) {
                // Eliminar respuestas de la pregunta
                $respuestas = $pregunta->getRespuestas();
                foreach ($respuestas as $respuesta) {
                    $entityManager->remove($respuesta);
                }
                
                // Eliminar opciones de la pregunta
                $opciones = $pregunta->getOpciones();
                foreach ($opciones as $opcion) {
                    // Eliminar respuestas de la opción
                    $respuestasOpcion = $opcion->getRespuestas();
                    foreach ($respuestasOpcion as $respuestaOpcion) {
                        $entityManager->remove($respuestaOpcion);
                    }
                    
                    // Eliminar la opción
                    $entityManager->remove($opcion);
                }
                
                // Eliminar la pregunta
                $entityManager->remove($pregunta);
            }
            
            // 4. Eliminar el instrumento
            $entityManager->remove($instrumento);
            
            // 5. Hacer commit de la transacción
            $entityManager->flush();
            $entityManager->commit();
            
            return new JsonResponse(['msg' => 'Instrumento360 eliminado correctamente con todos sus registros relacionados'], 200);
            
        } catch (\Exception $e) {
            // Rollback en caso de error
            $entityManager->rollback();
            return new JsonResponse(['msg' => 'Error al eliminar el instrumento360: ' . $e->getMessage()], 500);
        }
    }

    

}
