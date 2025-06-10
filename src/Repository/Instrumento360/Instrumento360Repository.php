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


    public function post($data,$validator,$helper): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        $entityManagerInstrumento = $this->getEntityManager();

        $entity = new Instrumento360();
        $entity->setNombre(!is_null($data["name"])?$data["name"]:null);
        $entity->setDuracion(!is_null($data["dutation"])?$data["dutation"]:null);
        $entity->setTipounidad(!is_null($data["unitType"])?$entityManager->getRepository(TipoUnidad::class)->find($data["unitType"]["id"]):null);
        $entity->setTipoInstrumento(!is_null($data["tipoInstrumento"])?$entityManager->getRepository(TipoInstrumento360::class)->find($data["tipoInstrumento"]):null);

        //$entity->setQuestionsByCategory(!is_null($data["questionsByCategory"])?$data["questionsByCategory"]:null);
        //$entity->setPath(!is_null($data["path"])?$data["path"]:null);
        $entity->setFechaVigencia(!is_null($data["expirationDate"])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data["expirationDate"] )))):null);
        $entity->setDescripcion(!is_null($data["description"])?$data["description"]:null);
        //$entity->setPuntosGlobales(!is_null($data["puntosGlobales"])?$data["puntosGlobales"]:null);
       // $entityStatus = $entityManager->getRepository(Status::class)->findOneById(1);          
        //$entity->setStatusId($entityStatus); 
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


    public function findById($id){
        $entityManager = $this->getEntityManager();        
        $entity= $this->getEntityManager()->createQueryBuilder();

        /* $encuestaData= $entity->select("a,q,x,f,e")
            ->from("App\Entity\Instrumento360\Instrumento360","a")
            ->leftJoin('a.Instrumento360UsuariosAsignados', 'q')
            ->leftJoin('q.user', 'x')
            ->leftJoin('q.userEvaluador', 'e')
            ->leftjoin('a.seccions', 'f')
            ->andWhere('a.id='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult(); */

        $encuestaData= $entity->select("a,q")
            ->from("App\Entity\Instrumento360\Instrumento360","a")
            //->leftJoin('a.Instrumento360UsuariosAsignados', 'q')
            ->leftJoin('a.instrumento360UsuariosAsignados', 'q')
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

            //$instrumentoDto->questionsByCategory= !is_null($valor->getQuestionsByCategory())?$valor->getQuestionsByCategory():0;

            //$instrumentoDto->unidad=$valor->getUnidad();

            //$instrumentoDto->path=$valor->getPath();

            //$instrumentoDto->idTipoUnidad=($valor->getIdTipoUnidad()!=null)?array("id"=>$valor->getIdTipoUnidad()->getId(),"Descripcion"=>$valor->getIdTipoUnidad()->getNombre()):[];

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
            /* if($valor->getInstrumentoUsuarios()!=null){
                foreach($valor->getInstrumentoUsuarios() as $instrumentosuser){
                        if($instrumentosuser->getRespondida()==1){
                            $editable=0;
                        }
                        $usersData[]=array("id"=>$instrumentosuser->getIdUser()->getId(),"nombre"=>$instrumentosuser->getIdUser()->getPrimerNombre(). " ".$instrumentosuser->getIdUser()->getPrimerApellido(),"email"=>$instrumentosuser->getIdUser()->getEmail()
                        ,"respondida"=>$instrumentosuser->getRespondida(),"roles"=>$instrumentosuser->getIdUser()->getRoles());                       
                }
            }   */
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
            /* if($valor->getSeccions()!=null){
                foreach($valor->getSeccions() as $seccion){
                    $preguntas= $entityManager->getRepository(Pregunta::class)->findByIdEncuestaAndSeccion($id,$seccion->getId());
                    $secciones[]=array(
                        "id"=>$seccion->getId(),
                        "nombre"=>$seccion->getNombre(),  
                        "orden"=>$seccion->getOrden(),
                        "preguntas"=>$preguntas);
                } 
            }
            $instrumentoDto->secciones=$secciones; */
            $dataInstrumento[]=$instrumentoDto;              
        }
       return new JsonResponse(['data'=>$dataInstrumento],200);
    }



}
