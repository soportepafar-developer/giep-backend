<?php
namespace App\Repository\Encuesta;
use App\Entity\Status;
Use App\Entity\User;
Use App\Entity\Cargo;
Use App\Entity\Nivel;
use App\Entity\Encuesta\TipoCategoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Dto\Encuesta\TipoCategoriaOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Encuesta\CategoriaCargoEscala;
use App\Entity\Encuesta\CategoriaNivelPonderacion;
use App\Entity\Proyecto\Empresa;
/**
 * @method TipoCategoria|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoCategoria|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoCategoria[]    findAll()
 * @method TipoCategoria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoCategoriaRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TipoCategoria::class);
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
            $query->where("a.nombre like '%".$data['word']."%' and a.idempresa = ".$empresa->getId()." "); 
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
        $hijos=[];
        $rolesUser=[];
        $dataTipoCategoria=array();
        foreach($paginator as $clave=>$valor){
            $tipocategoriaDto =new TipoCategoriaOutPutDto();
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
        $entityManagerDefault = $this->getEntityManager();
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        $data= $this->createQueryBuilder('c')
            ->where('c.status=1')
            ->andwhere('c.idempresa ='.$empresa->getId())
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $dataTipoCategoria=array();
        foreach($data as $clave=>$valor){
            $tipocategoriaDto =new TipoCategoriaOutPutDto();
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
        $entity=$helper->setParametersToEntity(new TipoCategoria(),$data);
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
            $entityManager->persist($entity);
            $entityManager->flush();
            if($data["escalas"]!=null)    
            foreach($data["escalas"] as $valor){
                $entityCargo = $entityManager->getRepository(Cargo::class)->find($valor["idCargo"]);          
                if($entityCargo!=null){
                    $categoriaCargoEscala= new CategoriaCargoEscala();
                    $categoriaCargoEscala->setCargo($entityCargo);
                    $categoriaCargoEscala->setCategoria($entity);         
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
                    $categoriaNivelPonderacion= new CategoriaNivelPonderacion();
                    $categoriaNivelPonderacion->setNivel($entityNivel);
                    $categoriaNivelPonderacion->setCategoria($entity);         
                    $categoriaNivelPonderacion->setPonderacion($valor["ponderacion"]);         
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                      $categoriaNivelPonderacion->setIdempresa($empresa);         
                    $entityManager->persist($categoriaNivelPonderacion);
                    $entityManager->flush();
                }
            }
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
            $tipocategoriaDto =new TipoCategoriaOutPutDto();
            $tipocategoriaDto->id=$valor->getId();
            $tipocategoriaDto->nombre=$valor->getNombre();
            $dataTipocategoria[]=$tipocategoriaDto;
            $tipocategoriaDto->escalaPonderacion=$valor->getEscalaPonderacion();
            $dataArrayCategoriaEscala=array();
            $dataArrayCategoriaPonderacion=array();
            foreach($valor->getCategoriaCargoEscala() as $categoriaCargoEscala){
                $dataArrayCategoriaEscala[]=array(
                    "idCargo"=>$categoriaCargoEscala->getCargo()->getId(),
                    "nombreCargo"=>$categoriaCargoEscala->getCargo()->getDescripcion(),
                    "escala"=>$categoriaCargoEscala->getEscala()

                );
            }
            foreach($valor->getCategpriaNivelPonderacion() as $categoriaNivelPonderacion){
                $dataArrayCategoriaPonderacion[]=array(
                    "idNivel"=>$categoriaNivelPonderacion->getNivel()->getId(),
                    "nombreNivel"=>$categoriaNivelPonderacion->getNivel()->getNombre(),
                    "ponderacion"=>$categoriaNivelPonderacion->getPonderacion()
                );
            }
            $tipocategoriaDto->escalas=count($dataArrayCategoriaEscala)>0?$dataArrayCategoriaEscala:null;
            $tipocategoriaDto->ponderaciones=count($dataArrayCategoriaPonderacion)>0?$dataArrayCategoriaPonderacion:null;
           
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
        $entity =$entityManager->getRepository(TipoCategoria::class)->find($id);
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
            foreach($entity->getCategoriaCargoEscala() as $categoriaCargoEscala){
                $entityManager->remove($categoriaCargoEscala);
                $entityManager->flush(); 
            }
            foreach($entity->getCategpriaNivelPonderacion() as $categoriaNivelPonderacion){
                $entityManager->remove($categoriaNivelPonderacion);
                $entityManager->flush(); 
            }
            if($data["escalas"]!=null)
                foreach($data["escalas"] as $valor){
                    $entityCargo = $entityManager->getRepository(Cargo::class)->find($valor["idCargo"]);          
                    if($entityCargo!=null){
                        $categoriaCargoEscala= new CategoriaCargoEscala();
                        $categoriaCargoEscala->setCargo($entityCargo);
                        $categoriaCargoEscala->setCategoria($entity);         
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
                        $categoriaNivelPonderacion= new CategoriaNivelPonderacion();
                        $categoriaNivelPonderacion->setNivel($entityNivel);
                        $categoriaNivelPonderacion->setCategoria($entity);         
                        $categoriaNivelPonderacion->setPonderacion($valor["ponderacion"]);         
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                          $categoriaNivelPonderacion->setIdempresa($empresa);         
                        $entityManager->persist($categoriaNivelPonderacion);
                        $entityManager->flush();
                    }
                }
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }

    }    

    /**
     * Delete TipoCategoria.
     */
    public function delete($id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(TipoCategoria::class)->find($id);
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
