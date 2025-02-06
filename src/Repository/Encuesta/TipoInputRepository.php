<?php

namespace App\Repository\Encuesta;
use App\Entity\Status;
use App\Entity\Encuesta\TipoInput;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Dto\Encuesta\TipoInputOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method TipoInput|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoInput|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoInput[]    findAll()
 * @method TipoInput[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoInputRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TipoInput::class);
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
        $dataTipoInput=array();
        foreach($paginator as $clave=>$valor){
            $tipoinputDto =new TipoInputOutPutDto();
            $tipoinputDto->id=$valor->getId();
            $tipoinputDto->nombre=$valor->getNombre();
            $tipoinputDto->multiple_seleccion=$valor->getSeleccionMultiple();
            if($valor->getEstatus()!=null){
                $tipoinputDto->status=array("statusId"=>$valor->getEstatus()->getId(),"labelStatus"=>$valor->getEstatus()->getDescripcion());
            }else{
                $tipoinputDto->status=null;
            }
            $dataTipoInput[]=$tipoinputDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$dataTipoInput);
 
    }

    public function findList()
    {

        $data= $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        foreach($data as $clave=>$valor){
            $tipoinputDto =new TipoInputOutPutDto();
            $tipoinputDto->id=$valor->getId();
            $tipoinputDto->nombre=$valor->getNombre();
            $tipoinputDto->multiple_seleccion=$valor->getSeleccionMultiple();
            $dataTipoInput[]=$tipoinputDto;
        }
       return array("data"=>$dataTipoInput);
     }

     /**
     * Create Tipo Input.
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new TipoInput(),$data);

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entityStatus = $entityManager->getRepository(Status::class)->findOneBy(array("descripcion"=>"activo"));          
            $entity->setEstatus($entityStatus);
    
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
     * Buscar Tipo Imput.
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
        $dataTipoinput=array();
        foreach($moduleData as $valor){
            $tipoinputDto =new TipoInputOutPutDto();

            $tipoinputDto->id=$valor->getId();
            $tipoinputDto->nombre=$valor->getNombre();
            $tipoinputDto->multiple_seleccion=$valor->getSeleccionMultiple();
            if($valor->getEstatus()!=null){
                $tipoinputDto->status=array("statusId"=>$valor->getEstatus()->getId(),"labelStatus"=>$valor->getEstatus()->getDescripcion());
            }else{
                $tipoinputDto->status=null;
            }
            $dataTipoinput[]=$tipoinputDto;
        }
        return new JsonResponse($dataTipoinput,200);  
    }

     /**
     * Update TipoInput.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(TipoInput::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $entity=$helper->setParametersToEntity($entity,$data);
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity->setUpdateBy($currentUser->getUserName());
        $entity->setUpdateAt(new \DateTime());
        $entityStatus = $entityManager->getRepository(Status::class)->find($data["statusId"]);          
        $entity->setEstatus($entityStatus);

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
     * Delete TipoInput.
     */
    public function delete($id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(TipoInput::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $entityStatus = $entityManager->getRepository(Status::class)->findOneBy(array("descripcion"=>"Inactivo"));          
        $entity->setEstatus($entityStatus);
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
