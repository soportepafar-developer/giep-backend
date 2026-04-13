<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\NivelDominio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Status;

Use App\Entity\User;
use App\Dto\Instrumento360\NivelDominioDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;


/**
 * @method NivelDominio|null find($id, $lockMode = null, $lockVersion = null)
 * @method NivelDominio|null findOneBy(array $criteria, array $orderBy = null)
 * @method NivelDominio[]    findAll()
 * @method NivelDominio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NivelDominioRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, NivelDominio::class);
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
        $hijos=[];
        $rolesUser=[];
        $data=array();
        foreach($paginator as $clave=>$valor){
            $nivelDominioDto =new NivelDominioDto();
            $nivelDominioDto->id=$valor->getId();
            $nivelDominioDto->nombre=$valor->getNombre();
            $nivelDominioDto->valor=$valor->getValor();
            $nivelDominioDto->descripcion=$valor->getDescripcion();
            $nivelDominioDto->status=$valor->getstatus();
            if($valor->getEmpresa()!=null){
                $nivelDominioDto->empresa=array("id"=>$valor->getEmpresa()->getId(),"label"=>$valor->getEmpresa()->getNombre());
            }else{
                $nivelDominioDto->empresa=null;
            }
            $data[]=$nivelDominioDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$data);
 
    }


    /**
     * Create.
     */
    public function post($data,$validator,$helper): JsonResponse  {

        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new NivelDominio(),$data);
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{

            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
                
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
            $nivelDominioDto =new NivelDominioDto();
            $nivelDominioDto->id=$valor->getId();
            $nivelDominioDto->nombre=$valor->getNombre();
            $nivelDominioDto->valor=$valor->getValor();
            $nivelDominioDto->descripcion=$valor->getDescripcion();
            if($valor->getEmpresa()!=null){
                $nivelDominioDto->empresa=array("id"=>$valor->getEmpresa()->getId(),"label"=>$valor->getEmpresa()->getNombre());
            }else{
                $nivelDominioDto->empresa=null;
            }
            if($valor->getStatus()!=null){
                $nivelDominioDto->status=array("statusId"=>$valor->getStatus()->getId(),"labelStatus"=>$valor->getStatus()->getDescripcion());
            }else{
                $nivelDominioDto->status=null;
            }
  
            $data[]=$nivelDominioDto;
        }
        return new JsonResponse($data,200);  
    }


    /**
     * Update.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(NivelDominio::class)->find($id);
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
            $unidaDto =new NivelDominioDto();
            $unidaDto->id=$valor->getId();
            $unidaDto->nombre=$valor->getNombre();
            $data[]=$unidaDto;
        }
       return new JsonResponse(array("data"=>$data));
 
    }

}
