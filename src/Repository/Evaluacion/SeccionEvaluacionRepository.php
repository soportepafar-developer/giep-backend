<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\SeccionEvaluacion;
use App\Entity\User;
use App\Entity\Status;
use App\Entity\Evaluacion\PreguntaEvaluacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use App\Dto\Evaluacion\SeccionEvaluacionDto;
use App\Entity\Evaluacion\Evaluacion;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method SeccionEvaluacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeccionEvaluacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeccionEvaluacion[]    findAll()
 * @method SeccionEvaluacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeccionEvaluacionRepository extends ServiceEntityRepository
{
    private $security;
    private $passwordEncoder;
    private $assetPackage;

    public function __construct(ManagerRegistry $registry,Security $security)
    {
       
        $this->security = $security;
        parent::__construct($registry, SeccionEvaluacion::class);
    }  

    
    public function findAllPage($data){
        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
        $query= $this->createQueryBuilder('a');
        $query->orderBy('a.id', 'ASC');
        if($data['word']!=null){
            $query->where("a.nombre like '%".$data['word']."%'");
        }
        $query->orderBy('a.id', 'ASC');     
        $query->getQuery();

        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery()	
      	->setFirstResult($data['rowByPage'] *($data['page']-1))	
      	->setMaxResults($data['rowByPage']);	
        $dataSeccion=array();
        $preguntasData=[];
        foreach($paginator as $clave=>$valor){
            $seccionDto =new SeccionEvaluacionDto();
            $seccionDto->id=$valor->getId();
            $seccionDto->nombre=$valor->getNombre();
            $seccionDto->status=($valor->getStatus()!=null)?array("id"=>$valor->getStatus()->getId(),"Descripcion"=>$valor->getStatus()->getDescripcion()):[];
            if($valor->getCreateAt()!=null){
                $seccionDto->createAt=$valor->getCreateAt()->format("d/m/Y");
            }    
            $seccionDto->updateBy=$valor->getUpdateBy();
            if($valor->getUpdateAt()!=null){           
                 $seccionDto->updateAt=$valor->getUpdateAt()->format("d/m/Y");
            }
            $seccionDto->createBy=$valor->getCreateBy();
            foreach($valor->getPreguntas()as $preguntas){
                $preguntasData[]=array("id"=>$preguntas->getId(),"pregunta"=>$preguntas->getPregunta());
            }
            $seccionDto->preguntas=$preguntasData;
            $preguntasData=[];
            $dataSeccion[]=$seccionDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$dataSeccion);
 
    }

    public function findList()
    {

        $data= $this->createQueryBuilder('c')
            ->where('c.status = 1')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $dataseccion=array();
        foreach($data as $clave=>$valor){
            $seccionDto =new SeccionEvaluacionDto();
            $seccionDto->id=$valor->getId();
            $seccionDto->nombre=$valor->getNombre();
            $dataseccion[]=$seccionDto;
        }
     
       return array("data"=>$dataseccion);
    }
   
    /**
     * Create Seccion.
    ***/
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new SeccionEvaluacion(),$data);
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return new JsonResponse($messages,409);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setUpdateBy($currentUser->getUserName());
            $entityInstrumentoCaptura = $entityManager->getRepository(InstrumentoCaptura::class)->findOneById($data["IdInstrumentoCaptura"]);          
            $entity->setInstrumento($entityInstrumentoCaptura);                    
            foreach ($data["preguntas"] as $key => $value) {
                $entityPregunta = $entityManager->getRepository(Pregunta::class)->find($value["id"]);          
                if($entityPregunta!=null){
                    $entity->addPregunta($entityPregunta);         
                }
            }         
            $entityStatus = $entityManager->getRepository(Status::class)->findOneById(1);          
            $entity->setStatus($entityStatus );  
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);  
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }

     /**
     * Update Seccion.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(SeccionEvaluacion::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $entity=$helper->setParametersToEntity($entity,$data);
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity->setCreateBy($currentUser->getUserName());
        $entity->setUpdateBy($currentUser->getUserName());
        $entityInstrumentoCaptura = $entityManager->getRepository(InstrumentoCaptura::class)->findOneById($data["IdInstrumentoCaptura"]);          
        $entity->setInstrumento($entityInstrumentoCaptura);                    
        foreach ($data["preguntas"] as $key => $value) {
            $entityPregunta = $entityManager->getRepository(Pregunta::class)->find($value["id"]);          
            if($entityPregunta!=null){
                $entity->addPregunta($entityPregunta);         
            }
        }         
        $entityStatus = $entityManager->getRepository(Status::class)->findOneById(1);          
        $entity->setStatus($entityStatus );  
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
     * Delete Seccion.
     */
    public function delete($id,$validator,$helper): JsonResponse  
    {
        
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(SeccionEvaluacion::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $entityStatus = $entityManager->getRepository(Status::class)->findOneById(2);          
        $entity->setStatus($entityStatus );  
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return new JsonResponse($messages,500);
        }else{
            foreach($entity->getPreguntas() as $preguntas){
                foreach($preguntas->getOpciones() as $opciones){
                    $entityManager->remove($opciones);
                    $entityManager->flush(); 
                }
                $entityManager->remove($preguntas);
                $entityManager->flush(); 
            }
            //$entityManager->persist($entity);
            $entityManager->remove($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Eliminado: '.$entity->getId()],200);
        }

    }    

}
