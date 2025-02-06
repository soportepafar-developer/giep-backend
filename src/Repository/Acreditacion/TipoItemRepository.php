<?php

namespace App\Repository\Acreditacion;
use App\Dto\Acreditacion\TipoItemOutputDto;
use App\Entity\Acreditacion\TipoItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
Use App\Entity\User;
Use App\Entity\Proyecto\Empresa;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method TipoItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoItem[]    findAll()
 * @method TipoItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TipoItem::class);
    }

    private $security;


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
        $dataTipo=array();
        foreach($data as $clave=>$valor){
            $tipoItemDto =new TipoItemOutputDto();
            $tipoItemDto->id=$valor->getId();
            $tipoItemDto->descripcion=$valor->getDescripcion();
            $dataTipo[]=$tipoItemDto;
        }
     
       return array("data"=>$dataTipo);
    }

    
         /**
     * Create Tipo Tem.
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new TipoItem(),$data);

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreatedBy($currentUser->getUserName());
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);
            // $entityStatus = $entityManager->getRepository(Status::class)->findOneBy(array("descripcion"=>"activo"));          
            // $entity->setEstatus($entityStatus);
    
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getDescripcion()],200);
        }    
    }


    /**
     * Update Tipo Item.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(TipoItem::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $entity=$helper->setParametersToEntity($entity,$data);
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity->setUpdateBy($currentUser->getUserName());
        $entity->setUpdatedAt(new \DateTime());
        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);
        // $entityStatus = $entityManager->getRepository(Status::class)->find($data["statusId"]);          
        // $entity->setEstatus($entityStatus);

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



    public function findAllPage($data){

        $entityManagerDefault = $this->getEntityManager();
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
       
        $query= $this->createQueryBuilder('a');
        $query->orderBy('a.id', 'ASC');
        if($data['word']!=null){
            $query->where("a.descripcion like '%".$data['word']."%' and a.idempresa = ".$empresa->getId()." ");
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
        $dataTipo=array();
        foreach($paginator as $clave=>$valor){
            $tipoDto =new TipoItemOutputDto();
            $tipoDto->id=$valor->getId();
            $tipoDto->descripcion=$valor->getDescripcion();
             $dataTipo[]=$tipoDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$dataTipo);
 
    }


}
