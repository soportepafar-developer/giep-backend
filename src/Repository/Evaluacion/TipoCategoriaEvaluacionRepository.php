<?php
namespace App\Repository\Evaluacion;
use App\Entity\Status;
Use App\Entity\User;
Use App\Entity\Cargo;
Use App\Entity\Nivel;
use App\Entity\Evaluacion\TipoCategoriaEvaluacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Dto\Evaluacion\TipoCategoriaEvaluacionDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method TipoCategoriaEvaluacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoCategoriaEvaluacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoCategoriaEvaluacion[]    findAll()
 * @method TipoCategoriaEvaluacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoCategoriaEvaluacionRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TipoCategoriaEvaluacion::class);
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
        $dataTipoCategoria=array();
        foreach($paginator as $clave=>$valor){
            $tipocategoriaDto =new TipoCategoriaEvaluacionDto();
            $tipocategoriaDto->id=$valor->getId();
            $tipocategoriaDto->nombre=$valor->getNombre();
            $tipocategoriaDto->escalaPonderacion=$valor->getEscalaPonderacion();
            if($valor->getStatus()!=null){
               $tipocategoriaDto->status=array("statusId"=>$valor->getStatus()->getId(),"labelStatus"=>$valor->getStatus()->getDescripcion());
            }else{
               $tipocategoriaDto->status=null;
            }
             $dataTipoCategoria[]=$tipocategoriaDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$dataTipoCategoria);
 
    }


    public function findList()
    {

        $data= $this->createQueryBuilder('c')
            ->where('c.status=1')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        
        foreach($data as $clave=>$valor){
            $tipocategoriaDto =new TipoCategoriaEvaluacionDto();
            $tipocategoriaDto->id=$valor->getId();
            $tipocategoriaDto->nombre=$valor->getNombre();
            $tipocategoriaDto->escalaPonderacion=$valor->getEscalaPonderacion();
     
            //$cargoDto->status=($valor->getIdStatus()!=null)?array("id"=>$valor->getIdStatus()->getId(),"Descripcion"=>$valor->getIdStatus()->getDescripcion()):[];        
            $dataTipoCategoria[]=$tipocategoriaDto;
        }
       return array("data"=>$dataTipoCategoria);
 



    }

    /**
     * Create Tipo Categoria.
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new TipoCategoriaEvaluacion(),$data);
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entityStatus = $entityManager->getRepository(Status::class)->findOneBy(array("descripcion"=>"Activo"));          
            $entity->setStatus($entityStatus);    
            $entity->setEscalaPonderacion($data["escalaPonderacion"]); 
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getNombre()],200);
        }    
    }

    /**
     * Buscar Tipo Categoria.
     */

    public function findTipoByCategoria($id){
        $moduleData= $this->createQueryBuilder('a')
            ->Where('a.id='.$id)
            ->orderBy('a.nombre', 'ASC')
            ->getQuery()
            ->getResult();
        if (count($moduleData)==0) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $dataTipocategoria=array();
        foreach($moduleData as $valor){
            $tipocategoriaDto =new TipoCategoriaEvaluacionDto();
            $tipocategoriaDto->id=$valor->getId();
            $tipocategoriaDto->nombre=$valor->getNombre();
            $dataTipocategoria[]=$tipocategoriaDto;
            $tipocategoriaDto->escalaPonderacion=$valor->getEscalaPonderacion();
           
            if($valor->getStatus()!=null){
                $tipocategoriaDto->status=array("statusId"=>$valor->getStatus()->getId(),"labelStatus"=>$valor->getStatus()->getDescripcion());
             }else{
                $tipocategoriaDto->status=null;
             }
  
        }
 
        return new JsonResponse($dataTipocategoria,200);  
    }

    /**
     * Update TipoCategoria.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(TipoCategoriaEvaluacion::class)->find($id);
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
     * Delete TipoCategoria.
     */
    public function delete($id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(TipoCategoriaEvaluacion::class)->find($id);
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
