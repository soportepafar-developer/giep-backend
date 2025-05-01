<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\CompetenciaCargoUnidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


use App\Entity\Status;

Use App\Entity\User;
use App\Dto\Instrumento360\CompetenciaCargoUnidadDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;


/**
 * @method CompetenciaCargoUnidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetenciaCargoUnidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetenciaCargoUnidad[]    findAll()
 * @method CompetenciaCargoUnidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetenciaCargoUnidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, CompetenciaCargoUnidad::class);
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
     
        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery()	
      	->setFirstResult($data['rowByPage'] *($data['page']-1))	
      	->setMaxResults($data['rowByPage']);	
        $dataUser=array();
        $data=array();
        foreach($paginator as $clave=>$valor){
            $competenciaCargoUnidadDto =new CompetenciaCargoUnidadDto();
            $competenciaCargoUnidadDto->id=$valor->getId();
            if($valor->getCargo()!=null){
                $competenciaCargoUnidadDto->cargo=array("id"=>$valor->getCargo()->getId(),"label"=>$valor->getCargo()->getNombre());
            }else{
                $competenciaCargoUnidadDto->cargo=null;
            }
            if($valor->getDominio()!=null){
                $competenciaCargoUnidadDto->dominio=array("id"=>$valor->getDominio()->getId(),"label"=>$valor->getDominio()->getNombre());
            }else{
                $competenciaCargoUnidadDto->dominio=null;
            }
            if($valor->getUnidad()!=null){
                $competenciaCargoUnidadDto->unidad=array("id"=>$valor->getUnidad()->getId(),"label"=>$valor->getUnidad()->getNombre());
            }else{
                $competenciaCargoUnidadDto->unidad=null;
            }
            if($valor->getCompetencia()!=null){
                $competenciaCargoUnidadDto->unidad=array("id"=>$valor->getCompetencia()->getId(),"label"=>$valor->getCompetencia()->getNombre());
            }else{
                $competenciaCargoUnidadDto->unidad=null;
            }
            $competenciaCargoUnidadDto->prioridad=$valor->getPrioridad();
            $data[]=$competenciaCargoUnidadDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$data);
 
    }

    /**
     * Create.
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new CompetenciaCargoUnidad(),$data);
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
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }

    /**
     * Buscar por Id.
     */
    public function findTipoByInput($id){
        $moduleData= $this->createQueryBuilder('a')
            ->Where('a.id='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
        if (count($moduleData)==0) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $data=array();
        foreach($moduleData as $valor){
            $competenciaCargoUnidadDto =new CompetenciaCargoUnidadDto();
            $competenciaCargoUnidadDto->id=$valor->getId();
            if($valor->getCargo()!=null){
                $competenciaCargoUnidadDto->cargo=array("id"=>$valor->getCargo()->getId(),"label"=>$valor->getCargo()->getDescripcion());
            }else{
                $competenciaCargoUnidadDto->cargo=null;
            }
            if($valor->getDominio()!=null){
                $competenciaCargoUnidadDto->dominio=array("id"=>$valor->getDominio()->getId(),"label"=>$valor->getDominio()->getNombre());
            }else{
                $competenciaCargoUnidadDto->dominio=null;
            }
            if($valor->getUnidad()!=null){
                $competenciaCargoUnidadDto->unidad=array("id"=>$valor->getUnidad()->getId(),"label"=>$valor->getUnidad()->getNombre());
            }else{
                $competenciaCargoUnidadDto->unidad=null;
            }
            if($valor->getCompetencia()!=null){
                $competenciaCargoUnidadDto->competencia=array("id"=>$valor->getCompetencia()->getId(),"label"=>$valor->getCompetencia()->getNombre());
            }else{
                $competenciaCargoUnidadDto->competencia=null;
            }
            $competenciaCargoUnidadDto->prioridad=$valor->getPrioridad();
            $data[]=$competenciaCargoUnidadDto;
        }
        return new JsonResponse($data,200);  
    }

    /**
     * Update.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(CompetenciaCargoUnidad::class)->find($id);
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
            $competenciaCargoUnidadDto =new CompetenciaCargoUnidadDto();
            $competenciaCargoUnidadDto->id=$valor->getId();
            if($valor->getCargo()!=null){
                $competenciaCargoUnidadDto->cargo=array("id"=>$valor->getCargo()->getId(),"label"=>$valor->getCargo()->getDescripcion());
            }else{
                $competenciaCargoUnidadDto->cargo=null;
            }
            if($valor->getDominio()!=null){
                $competenciaCargoUnidadDto->dominio=array("id"=>$valor->getDominio()->getId(),"label"=>$valor->getDominio()->getNombre());
            }else{
                $competenciaCargoUnidadDto->dominio=null;
            }
            if($valor->getUnidad()!=null){
                $competenciaCargoUnidadDto->unidad=array("id"=>$valor->getUnidad()->getId(),"label"=>$valor->getUnidad()->getNombre());
            }else{
                $competenciaCargoUnidadDto->unidad=null;
            }
            if($valor->getCompetencia()!=null){
                $competenciaCargoUnidadDto->competencia=array("id"=>$valor->getCompetencia()->getId(),"label"=>$valor->getCompetencia()->getNombre());
            }else{
                $competenciaCargoUnidadDto->competencia=null;
            }
            $competenciaCargoUnidadDto->prioridad=$valor->getPrioridad();
            $data[]=$competenciaCargoUnidadDto;
        }
       return new JsonResponse(array("data"=>$data));
    }
}
