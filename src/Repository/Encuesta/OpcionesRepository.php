<?php

namespace App\Repository\Encuesta;

use App\Dto\Encuesta\OpcionesOutPutDto;
use App\Entity\Encuesta\Opciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method Opciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Opciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Opciones[]    findAll()
 * @method Opciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpcionesRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Opciones::class);
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
        $dataopciones=array();
        foreach($paginator as $clave=>$valor){
            $opcionesDto =new OpcionesOutPutDto();
            $opcionesDto->id=$valor->getId();
            $opcionesDto->nombre=$valor->getNombre();
            $opcionesDto->valor=$valor->getValor();
            $opcionesDto->puntos=$valor->getPuntos();
            $opcionesDto->correcta=$valor->getCorrecta();
            ///$opcionesDto->idpregunta=$valor->getIdPregunta();
            $opcionesDto->idpregunta=($valor->getIdPregunta()!=null)?array("id"=>$valor->getIdPregunta()->getId(),
            "Pregunta"=>$valor->getIdPregunta()->getPregunta(),
            "Orden"=>$valor->getIdPregunta()->getOrden(),
            "Idinput"=>$valor->getIdPregunta()->getIdInput()->getId(),"InputNombre"=>$valor->getIdPregunta()->getIdInput()->getNombre(),
            
            "Class"=>$valor->getIdPregunta()->getClass(),
            "Obligatorio"=>$valor->getIdPregunta()->getObligatorio(),
            "Puntos"=>$valor->getIdPregunta()->getPuntos(),

            "IdCategoria"=>$valor->getIdPregunta()->getIdCategoria()->getId(),"CategoriaNombre"=>$valor->getIdPregunta()->getIdCategoria()->getNombre(),
            //"Categoria"=>$valor->getIdPregunta()->getIdCategoria(),
            "IdInstrumento"=>$valor->getIdPregunta()->getIdInstrumento()->getId(),"InstrumentoNombre"=>$valor->getIdPregunta()->getIdInstrumento()->getNombre()):[];
            //"Id Instrumento"=>$valor->getIdPregunta()->getIdInstrumento()):[];
            $dataopciones[]=$opcionesDto;
        }
       return array("count"=>count($paginatorTotalCount),"data"=>$dataopciones);
 
    }

          /**
     * Create Opciones.
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new Opciones(),$data);

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setCreateBy($currentUser->getUserName());

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
     * Buscar Opciones.
     */

    public function findInstrumentoByCaptura($id){
        $moduleData= $this->createQueryBuilder('a')
            ->Where('a.id='.$id)
            ->orderBy('a.nombre', 'ASC')
            ->getQuery()
            ->getResult();
        if (count($moduleData)==0) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $dataopciones=array();
        $datacategoria=array();
        $datainstrumento=array();
        foreach($moduleData as $valor){
            $opcionesDto =new OpcionesOutPutDto();
            $opcionesDto->id=$valor->getId();
            $opcionesDto->nombre=$valor->getNombre();
            $opcionesDto->valor=$valor->getValor();
            $opcionesDto->puntos=$valor->getPuntos();
            $opcionesDto->correcta=$valor->getCorrecta();
            ///$opcionesDto->idpregunta=$valor->getIdPregunta();
            $opcionesDto->idpregunta=($valor->getIdPregunta()!=null)?array("id"=>$valor->getIdPregunta()->getId(),
            "Pregunta"=>$valor->getIdPregunta()->getPregunta(),
            "Orden"=>$valor->getIdPregunta()->getOrden(),
            "Idinput"=>$valor->getIdPregunta()->getIdInput()->getId(),"InputNombre"=>$valor->getIdPregunta()->getIdInput()->getNombre(),
            
            "Class"=>$valor->getIdPregunta()->getClass(),
            "Obligatorio"=>$valor->getIdPregunta()->getObligatorio(),
            "Puntos"=>$valor->getIdPregunta()->getPuntos(),
            
            //$datacategoria[]=$valor->getIdPregunta()->getIdCategoria()->getId(),"CategoriaNombre"=>$valor->getIdPregunta()->getIdCategoria()->getNombre(),
            $datacategoria[]=$valor->getIdPregunta()->getIdCategoria()->getId(),
            $datacategoria[]=$valor->getIdPregunta()->getIdCategoria()->getNombre(),
            "CategoriaNombre"=>$datacategoria,
            //"IdCategoria"=>$valor->getIdPregunta()->getIdCategoria()->getId(),"CategoriaNombre"=>$valor->getIdPregunta()->getIdCategoria()->getNombre(),

            //"Categoria"=>$valor->getIdPregunta()->getIdCategoria(),
            //"IdInstrumento"=>$datainstrumento[]=$valor->getIdPregunta()->getIdInstrumento()->getId(),"InstrumentoNombre"=>$valor->getIdPregunta()->getIdInstrumento()->getNombre()):[];
            $datainstrumento[]=$valor->getIdPregunta()->getIdInstrumento()->getId(),
            $datainstrumento[]=$valor->getIdPregunta()->getIdInstrumento()->getNombre(),
            "Instrumentos"=>$datainstrumento):[];


            //"IdInstrumento"=>$datainstrumento,
            //"IdInstrumento"=>$valor->getIdPregunta()->getIdInstrumento()->getId(),"InstrumentoNombre"=>$valor->getIdPregunta()->getIdInstrumento()->getNombre()):[];

            //"Id Instrumento"=>$valor->getIdPregunta()->getIdInstrumento()):[];
            $dataopciones[]=$opcionesDto;
        }
        return new JsonResponse($dataopciones,200);  
    }

    /**
     * Update Opciones.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Opciones::class)->find($id);
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
                $entity->setIdempresa($empresa);  
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Actualizado: '.$entity->getId()],200);
        }

    }

        
   public function delete($id,$validator): JsonResponse  
   {
       $entityManager = $this->getEntityManager();
       $entityOpciones =$entityManager->getRepository(Opciones::class)->find($id);
       if (!$entityOpciones) {
           return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
       }
        if (count($entityOpciones->getRespuestas())>0) {
            return new JsonResponse(['msg'=>'No se puede eliminar la opcion tiene respuesta vinculada: '.$id],409);  
        }
       $errors = $validator->validate($entityOpciones);
       if($errors->count() > 0){
           $errorsString = (string) $errors;
           return new JsonResponse(['msg'=>$errorsString],409);
       }else{
           $entityManager->remove($entityOpciones);
           $entityManager->flush();
           return new JsonResponse(['msg'=>'Registro Eliminado: '.$entityOpciones->getId()],200);
       }

   }    


}
