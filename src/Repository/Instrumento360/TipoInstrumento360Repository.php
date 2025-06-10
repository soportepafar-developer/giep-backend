<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\TipoInstrumento360;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


use App\Entity\Status;

Use App\Entity\User;
use App\Dto\Instrumento360\TipoInstrumento360Dto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;


/**
 * @method TipoInstrumento360|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoInstrumento360|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoInstrumento360[]    findAll()
 * @method TipoInstrumento360[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoInstrumento360Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TipoInstrumento360::class);
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
        $x= $query->getQuery();

        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery()	
      	->setFirstResult($data['rowByPage'] *($data['page']-1))	
      	->setMaxResults($data['rowByPage']);	
        $dataUser=array();
        $data=array();
        foreach($paginator as $clave=>$valor){
            $tipoDto =new TipoInstrumento360Dto();
            $tipoDto->id=$valor->getId();
            $tipoDto->nombre=$valor->getNombre();
            $tipoDto->status=$valor->getstatus();
            if($valor->getEmpresa()!=null){
                $tipoDto->empresa=array("id"=>$valor->getEmpresa()->getId(),"label"=>$valor->getEmpresa()->getNombre());
            }else{
                $tipoDto->empresa=null;
            }
            if($valor->getStatus()!=null){
                $tipoDto->status=array("statusId"=>$valor->getStatus()->getId(),"labelStatus"=>$valor->getStatus()->getDescripcion());
            }else{
                $tipoDto->status=null;
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
        $entity=$helper->setParametersToEntity(new TipoInstrumento360(),$data);
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
            $entityStatus = $entityManager->getRepository(Status::class)->findOneBy(array("descripcion"=>"activo"));          
            $entity->setStatus($entityStatus);      
            $entityManager->persist($entity);
            $entityManager->flush();
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
            $tipoInstrumentoDto =new TipoInstrumento360Dto();
            $tipoInstrumentoDto->id=$valor->getId();
            $tipoInstrumentoDto->nombre=$valor->getNombre();
            if($valor->getEmpresa()!=null){
                $tipoInstrumentoDto->empresa=array("id"=>$valor->getEmpresa()->getId(),"label"=>$valor->getEmpresa()->getNombre());
            }else{
                $tipoInstrumentoDto->empresa=null;
            }
            if($valor->getStatus()!=null){
                $tipoInstrumentoDto->status=array("statusId"=>$valor->getStatus()->getId(),"labelStatus"=>$valor->getStatus()->getDescripcion());
            }else{
                $tipoInstrumentoDto->status=null;
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
        $entity =$entityManager->getRepository(TipoInstrumento360::class)->find($id);
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
                $entity->setEmpresa($empresa);   
            $entityManager->persist($entity);
            $entityManager->flush();
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
            $tipoDto =new TipoInstrumento360Dto();
            $tipoDto->id=$valor->getId();
            $tipoDto->nombre=$valor->getNombre();
            $data[]=$tipoDto;
        }
       return new JsonResponse(array("data"=>$data));
 
    }


}
