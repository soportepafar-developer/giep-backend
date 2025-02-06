<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\TipoUnidadEvaluacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Status;
Use App\Entity\User;
use App\Dto\Evaluacion\TipoUnidadEvaluacionDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method TipoUnidadEvaluacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoUnidadEvaluacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoUnidadEvaluacion[]    findAll()
 * @method TipoUnidadEvaluacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoUnidadEvaluacionRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TipoUnidadEvaluacion::class);
    }

    public function findAllPage($data){

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
       
        $query= $this->createQueryBuilder('a');
        $query->orderBy('a.id', 'ASC');
        if($data['word']!=null){
            $query->where("a.nombre like '%".$data['word']."%' ");
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
        $dataTipoUnidad=array();
        foreach($paginator as $clave=>$valor){
            $tipounidadDto =new TipoUnidadEvaluacionDto();
            $tipounidadDto->id=$valor->getId();
            $tipounidadDto->nombre=$valor->getNombre();
            $tipounidadDto->factor=$valor->getFactor();
            if($valor->getStatus()!=null){
                $tipounidadDto->status=array("statusId"=>$valor->getStatus()->getId(),"labelStatus"=>$valor->getStatus()->getDescripcion());

            }else{
                $tipounidadDto->status=null;
            }    
            $dataTipoUnidad[]=$tipounidadDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$dataTipoUnidad);
 
    }


    public function findList()
    {

        $data= $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        foreach($data as $clave=>$valor){
            $tipounidadDto =new TipoUnidadEvaluacionDto();
            $tipounidadDto->id=$valor->getId();
            $tipounidadDto->nombre=$valor->getNombre();
            $tipounidadDto->factor=$valor->getFactor();
            $dataTipoUnidad[]=$tipounidadDto;
        }
       return array("data"=>$dataTipoUnidad);
 
    }

    /**
     * Create Tipo Unidad.
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new TipoUnidadEvaluacion(),$data);
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entityStatus = $entityManager->getRepository(Status::class)->findOneBy(array("descripcion"=>"Activo"));          
            $entity->setStatus($entityStatus);
       
            //$entity->setIdStatus($entityManager->getRepository(Status::class)->find(1)); 
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getNombre()],200);
        }    
    }

    /**
     * Buscar Tipo Unidad.
     */

    public function findTipoByUnidad($id){
        $moduleData= $this->createQueryBuilder('a')
            ->Where('a.id='.$id)
            ->orderBy('a.nombre', 'ASC')
            ->getQuery()
            ->getResult();
        if (count($moduleData)==0) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $dataTipounidad=array();
        foreach($moduleData as $valor){
            $tipounidadDto =new TipoUnidadEvaluacionDto();
            $tipounidadDto->id=$valor->getId();
            $tipounidadDto->nombre=$valor->getNombre();
            $tipounidadDto->factor=$valor->getFactor();
            if($valor->getStatus()!=null){
                $tipounidadDto->status=array("statusId"=>$valor->getStatus()->getId(),"labelStatus"=>$valor->getStatus()->getDescripcion());

            }else{
                $tipounidadDto->status=null;
            }    

            $dataTipounidad[]=$tipounidadDto;
        }
        return new JsonResponse($dataTipounidad,200);  
    }

    /**
     * Update TipoUnidad.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(TipoUnidadEvaluacion::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $entity=$helper->setParametersToEntity($entity,$data);
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity->setUpdateBy($currentUser->getUserName());
        $entity->setUpdateAt(new \DateTime());
        $entityStatus = $entityManager->getRepository(Status::class)->find($data["statusId"]);          
        $entity->setStatus($entityStatus);

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
     * Delete TipoUnidad.
     */
    public function delete($id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(TipoUnidadEvaluacion::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $entityStatus = $entityManager->getRepository(Status::class)->findOneBy(array("descripcion"=>"Inactivo"));          
        $entity->setStatus($entityStatus);
        $entityManager->flush();
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],409);
        }else{
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Eliminado: '.$entity->getId()],200);
        }

    }

}