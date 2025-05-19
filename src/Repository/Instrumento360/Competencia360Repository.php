<?php

namespace App\Repository\Instrumento360;
Use App\Entity\Cargo;
use App\Entity\Instrumento360\Competencia360;
use App\Entity\Instrumento360\Competencia360NivelPonderacion;
Use App\Entity\Nivel;
Use App\Entity\Instrumento360\Competencia360CargoEscala;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


Use App\Entity\User;
use App\Dto\Instrumento360\Competencia360Dto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;


/**
 * @method Competencia360|null find($id, $lockMode = null, $lockVersion = null)
 * @method Competencia360|null findOneBy(array $criteria, array $orderBy = null)
 * @method Competencia360[]    findAll()
 * @method Competencia360[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Competencia360Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Competencia360::class);
    }

    public function findAllPage($data){
        $entityManagerDefault = $this->getEntityManager();

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
       
        $query= $this->createQueryBuilder('a');
        if($data['word']!=null){
            $query->where("a.nombre like '%".$data['word']."%' and a.empresa = ".$empresa->getId()." "); 
        }else{
            $query->where("a.empresa = ".$empresa->getId());
        }

        $query->orderBy('a.id', 'ASC');   
        $x= $query->getQuery();

        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery()	
      	->setFirstResult($data['rowByPage'] *($data['page']-1))	
      	->setMaxResults($data['rowByPage']);	
        $dataUser=array();
        $data=array();
        foreach($paginator as $clave=>$valor){
            $tipoDto =new Competencia360Dto();
            $tipoDto->id=$valor->getId();
            $tipoDto->nombre=$valor->getNombre();
            $tipoDto->descripcion=$valor->getDescripcion();
            $tipoDto->tipo=$valor->getTipo();
            $tipoDto->escalaPonderacion=$valor->getEscalaPonderacion();
        
            if($valor->getEmpresa()!=null){
                $tipoDto->empresa=array("id"=>$valor->getEmpresa()->getId(),"label"=>$valor->getEmpresa()->getNombre());
            }else{
                $tipoDto->empresa=null;
            }
            $data[]=$tipoDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$data);
 
    }


        /**
     * Create.
     */
    public function post($data,$validator,$helper): JsonResponse  {

        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new Competencia360(),$data);
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{

            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setUpdateBy($currentUser->getUserName());
                
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setEmpresa($empresa);
            $entityManager->persist($entity);
            $entityManager->flush();
            if($data["escalas"]!=null)    
                foreach($data["escalas"] as $valor){
                    $entityCargo = $entityManager->getRepository(Cargo::class)->find($valor["idCargo"]);          
                    if($entityCargo!=null){
                        $categoriaCargoEscala= new Competencia360CargoEscala();
                        $categoriaCargoEscala->setCargo($entityCargo);
                        $categoriaCargoEscala->setCompetencia($entity);         
                        $categoriaCargoEscala->setEscala($valor["escala"]);   
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                        $categoriaCargoEscala->setIdempresa($empresa);         
                        $entityManager->persist($categoriaCargoEscala);
                        $entityManager->flush();
            
                    }
                }
            if($data["ponderaciones"]!=null)    
                foreach($data["ponderaciones"] as $valor){
                    $entityNivel = $entityManager->getRepository(Nivel::class)->find($valor["idNivel"]);          
                    if($entityNivel!=null){
                        $competencia360NivelPonderacion= new Competencia360NivelPonderacion();
                        $competencia360NivelPonderacion->setNivel($entityNivel);
                        $competencia360NivelPonderacion->setCompetencia($entity);         
                        $competencia360NivelPonderacion->setPonderacion($valor["ponderacion"]);         
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                            $competencia360NivelPonderacion->setIdempresa($empresa);         
                        $entityManager->persist($competencia360NivelPonderacion);
                        $entityManager->flush();
                    }
                }
    
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }


    /**
        * Buscar por Id.
     */

     public function findTipoByInput($id){

        $moduleData= $this->createQueryBuilder('a')
            ->Where('a.id='.$id)
            ->orderBy('a.nombre', 'ASC')
            ->getQuery()
            ->getResult();
        if (count($moduleData)==0) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $data=array();
        foreach($moduleData as $valor){
            $tipoInstrumentoDto =new Competencia360Dto();
            $tipoInstrumentoDto->id=$valor->getId();
            $tipoInstrumentoDto->nombre=$valor->getNombre();
            $tipoInstrumentoDto->descripcion=$valor->getDescripcion();
            $tipoInstrumentoDto->tipo=$valor->getTipo();
            $tipoInstrumentoDto->escalaPonderacion=$valor->getEscalaPonderacion();
        
            $dataArrayCompetenciaEscala=array();
            $dataArrayCompetenciaPonderacion=array();
            foreach($valor->getCompetenciaCargoEscala() as $competenciaCargoEscala){
                $dataArrayCompetenciaEscala[]=array(
                    "idCargo"=>$competenciaCargoEscala->getCargo()->getId(),
                    "nombreCargo"=>$competenciaCargoEscala->getCargo()->getDescripcion(),
                    "escala"=>$competenciaCargoEscala->getEscala()
                );
            }
            $tipoInstrumentoDto->escalas=count($dataArrayCompetenciaEscala)>0?$dataArrayCompetenciaEscala:null;
           
            foreach($valor->getCompetenciasNivelPonderacion() as $competenciaNivelPonderacion){
                $dataArrayCompetenciaPonderacion[]=array(
                    "idNivel"=>$competenciaNivelPonderacion->getNivel()->getId(),
                    "nombreNivel"=>$competenciaNivelPonderacion->getNivel()->getNombre(),
                    "ponderacion"=>$competenciaNivelPonderacion->getPonderacion()
                );
            }
            $tipoInstrumentoDto->ponderaciones=count($dataArrayCompetenciaPonderacion)>0?$dataArrayCompetenciaPonderacion:null;

            if($valor->getEmpresa()!=null){
                $tipoInstrumentoDto->empresa=array("id"=>$valor->getEmpresa()->getId(),"label"=>$valor->getEmpresa()->getNombre());
            }else{
                $tipoInstrumentoDto->empresa=null;
            }
  
            $data[]=$tipoInstrumentoDto;
        }
        return new JsonResponse($data,200);  
    }


    /**
     * Update.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Competencia360::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $entity=$helper->setParametersToEntity($entity,$data);
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
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setEmpresa($empresa);   
            $entityManager->persist($entity);
            $entityManager->flush();

            foreach($entity->getCompetenciaCargoEscala() as $competenciaCargoEscala){
                $entityManager->remove($competenciaCargoEscala);
                $entityManager->flush(); 
            }

            foreach($entity->getCompetenciasNivelPonderacion() as $competenciaNivelPonderacion){
                $entityManager->remove($competenciaNivelPonderacion);
                $entityManager->flush(); 
            }
            if($data["escalas"]!=null)    
                foreach($data["escalas"] as $valor){
                    $entityCargo = $entityManager->getRepository(Cargo::class)->find($valor["idCargo"]);          
                    if($entityCargo!=null){
                        $categoriaCargoEscala= new Competencia360CargoEscala();
                        $categoriaCargoEscala->setCargo($entityCargo);
                        $categoriaCargoEscala->setCompetencia($entity);         
                        $categoriaCargoEscala->setEscala($valor["escala"]);   
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                        $categoriaCargoEscala->setIdempresa($empresa);         
                        $entityManager->persist($categoriaCargoEscala);
                        $entityManager->flush();
            
                    }
                }
            if($data["ponderaciones"]!=null)    
                foreach($data["ponderaciones"] as $valor){
                    $entityNivel = $entityManager->getRepository(Nivel::class)->find($valor["idNivel"]);          
                    if($entityNivel!=null){
                        $competencia360NivelPonderacion= new Competencia360NivelPonderacion();
                        $competencia360NivelPonderacion->setNivel($entityNivel);
                        $competencia360NivelPonderacion->setCompetencia($entity);         
                        $competencia360NivelPonderacion->setPonderacion($valor["ponderacion"]);         
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                            $competencia360NivelPonderacion->setIdempresa($empresa);         
                        $entityManager->persist($competencia360NivelPonderacion);
                        $entityManager->flush();
                    }
                }


            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }

    }


    public function list(){

        $query= $this->createQueryBuilder('a')
        ->Where('a.empresa='.$this->security->getUser()->getIdempresa()->getId());
        $query->orderBy('a.id', 'ASC');

        $x= $query->getQuery();

        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery();	
        $data=array();
        foreach($paginator as $clave=>$valor){
            $tipoDto =new Competencia360Dto();
            $tipoDto->id=$valor->getId();
            $tipoDto->nombre=$valor->getNombre();
            $tipoDto->descripcion=$valor->getDescripcion();
            $tipoDto->tipo=$valor->getTipo();
         
            $data[]=$tipoDto;
        }
       return new JsonResponse(array("data"=>$data));
 
    }

}
