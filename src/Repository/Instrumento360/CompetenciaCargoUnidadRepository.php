<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\CompetenciaCargoUnidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


use App\Entity\Status;
use App\Entity\Cargo;
Use App\Entity\Instrumento360\NivelDominio;
Use App\Entity\Instrumento360\Competencia360;
Use App\Entity\EstructuraOrganizativa;
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
            $query->leftJoin('a.competencia', 'comp')
                  ->leftJoin('a.cargo', 'carg')
                  ->leftJoin('a.dominio', 'dom')
                  ->leftJoin('a.unidad','unidad')
                  ->where("comp.nombre like '%".$data['word']."%' ")
                  ->orWhere("carg.descripcion like '%".$data['word']."%' ")
                  ->orWhere("unidad.estructura_organizativa like '%".$data['word']."%' ")
                  ->orWhere("dom.nombre like '%".$data['word']."%' ");
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
                $competenciaCargoUnidadDto->unidad=array("id"=>$valor->getUnidad()->getId(),"label"=>$valor->getUnidad()->getEstructuraOrganizativa());
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
       return array("count"=>count($paginatorTotalCount),"data"=>$data);
 
    }

    /**
     * Create.
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
            if(isset($data["cargosNivelDominioPrioridad"])){
                foreach($data["cargosNivelDominioPrioridad"] as $valor){
                    $entity = new CompetenciaCargoUnidad();
                    $cargo =$entityManager->getRepository(Cargo::class)->find($valor["cargoId"]);
                    if($cargo)
                        $entity->setCargo($cargo);
                    $dominio =$entityManager->getRepository(NivelDominio::class)->find($valor["dominioId"]);
                    if($dominio)
                         $entity->setDominio($dominio);
                    
                    $competencia =$entityManager->getRepository(Competencia360::class)->find($data["competencia"]);
                    if($competencia)
                        $entity->setCompetencia($competencia);
                    $estructura =$entityManager->getRepository(EstructuraOrganizativa::class)->find($data["unidad"]);
                    if($estructura)
                        $entity->setUnidad($estructura);
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
        
                    if($empresa)
                        $entity->setEmpresa($empresa);

                    $entity->setPrioridad($valor["prioridad"]);   

                     $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                    $entity->setCreateAt(new \DateTime());
                    $entity->setCreateBy($currentUser->getUserName());
                        
                    $entityManager->persist($entity);
                    $entityManager->flush();
                                                    
                }            
            }
            
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
           
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
                $competenciaCargoUnidadDto->unidad=array("id"=>$valor->getUnidad()->getId(),"label"=>$valor->getUnidad()->getEstructuraOrganizativa());
            }else{
                $competenciaCargoUnidadDto->unidad=null;
            }
            if($valor->getCompetencia()!=null){
                $competenciaCargoUnidadDto->competencia=array("id"=>$valor->getCompetencia()->getId(),"label"=>$valor->getCompetencia()->getNombre());
            }else{
                $competenciaCargoUnidadDto->competencia=null;
            }
            $competenciaCargoUnidadDto->prioridad=$valor->getPrioridad();

            $val = $this->Estructura_Organizativa($valor->getUnidad()->getId());
            $competenciaCargoUnidadDto->niveles=$val;

            $data[]=$competenciaCargoUnidadDto;
        }
        return new JsonResponse($data,200);  
    }

    public function Estructura_Organizativa($idestructura){
    $dataEstructura=[];    
    $regSalida=false;
    $entityManager = $this->getEntityManager();
    $queryresp = $entityManager->createQueryBuilder();
    $estructuraQuery = $queryresp->select('eo.id, eo.padre_id, eo.estructura_organizativa')
        ->from(EstructuraOrganizativa::class, 'eo')
        ->where('eo.id = :id')
        ->setParameter('id', $idestructura)
        ->getQuery();
        $queryrespdata = $queryresp->getQuery();
        $dataresp =  $queryrespdata->execute();
        $regUnidad =  $dataresp[0]["padre_id"];

        $dataEstructura[] = array(
            "id" => $dataresp[0]["id"],
            "label" => $dataresp[0]["estructura_organizativa"]
        );

   if (!is_null($dataresp[0]["padre_id"])) {

    do {
        $queryRecursivo = $entityManager->createQueryBuilder();
        $estructuraQueryRec = $queryRecursivo->select('eo.id, eo.padre_id, eo.estructura_organizativa')
        ->from(EstructuraOrganizativa::class, 'eo')
        ->where('eo.id = :id')
        ->setParameter('id', $regUnidad)
        ->getQuery();
        $queryrecursivodata = $queryRecursivo->getQuery();
        $datarecursivo =  $queryrecursivodata->execute();

        if (is_null($datarecursivo[0]["padre_id"])) {
            $dataEstructura[] = array(
                "id" => $datarecursivo[0]["id"],
                "label" => $datarecursivo[0]["estructura_organizativa"]
            );
            $regSalida=true;
        }else{
            $regUnidad =  $datarecursivo[0]["padre_id"];
            $dataEstructura[] = array(
                "id" => $datarecursivo[0]["id"],
                "label" => $datarecursivo[0]["estructura_organizativa"]
            );
        }
        

   } while (!$regSalida);
}
   // Ordenar el array de menor a mayor por el campo 'id'
    usort($dataEstructura, function($a, $b) {
        return $a['id'] <=> $b['id'];
    });
   return $dataEstructura;

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
        // $entity->setUpdateBy($currentUser->getUserName());
        // $entity->setUpdateAt(new \DateTime());

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

            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setUpdateBy($currentUser->getUserName());
            $entity->setUpdateAt(new \DateTime());

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
