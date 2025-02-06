<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\SprintItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;

/**
 * @method SprintItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method SprintItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method SprintItem[]    findAll()
 * @method SprintItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SprintItemRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, SprintItem::class);
    }  

    
     /**
     * Lista Spring.
     */
    public function findList()
    {
        /* $data= $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ; */
        $entityManager = $this->getEntityManager();        
        $dataItems["Items"]=[];
        
        $entity= $this->getEntityManager()->createQueryBuilder();

        $data= $entity->select("p,q,x")
            ->from("App\Entity\Proyecto\SprintItem","p")
            ->innerJoin('p.IdSpring', 'q')
            ->innerJoin('p.IdItem', "x")
            ->where('x.idBacklogPadre is null')
            ->getQuery()
            ->getResult();  
        $datapais=array();
        $dataspringitem=array();
        foreach($data as $clave=>$valor){

            $dataspringitem[]=array("id"=>$valor->getId(),
            "titulo"=>$valor->getIdItem()->getTitulo(),
            "descripcion"=>$valor->getIdItem()->getDescripcion(),
            "iduser"=>$valor->getIdItem()->getIdUser()->getId(),
            "nameuser"=>!is_null($valor->getIdItem()->getIdUser())?$valor->getIdItem()->getIdUser()->getPrimerNombre()." ".$valor->getIdItem()->getIdUser()->getPrimerApellido():[]
            );

/* 
            $paisDto =new EmpresaOutPutDto();
            $paisDto->id=$valor->getId();
            $paisDto->nombre=$valor->getNombre();
            $paisDto->status=($valor->getStatus()!=null)?array("id"=>$valor->getStatus()->getId(),"Descripcion"=>$valor->getStatus()->getDescripcion()):[];        
            //$paisDto->status=($valor->getIdStatus()!=null)?array("id"=>$valor->getIdStatus()->getId(),"Descripcion"=>$valor->getIdStatus()->getDescripcion()):[];        
            $datapais[]=$paisDto;
 */

        }
       return array("data"=>$dataspringitem);
       //return $dataspringitem;
 
    } 




    public function findAllPage($data){

        $entityManager = $this->getEntityManager();        
        $dataItems["Items"]=[];
        
        $entity= $this->getEntityManager()->createQueryBuilder();
        $items= $entity->select("p,q,x")
            ->from("App\Entity\Proyecto\SprintItem","p")
            ->innerJoin('p.IdSpring', 'q')
            ->innerJoin('p.IdItem', "x")
            ->where('x.idBacklogPadre is null')
            ->getQuery()
            ->getResult();
            $itemsComentarios=[];
            $itemsAdjuntos=[];
            $dataItems=array();
            $dataspringitem=array();
            foreach($items as $item){

                if($item->getIdItem()->getSprintItems()!=null){
                    foreach($item->getIdItem()->getSprintItems()as $comentario){
                        $dataspringitem[]=array("id"=>$comentario->getIdItem()->getId(),
                        "titulo"=>$comentario->getIdItem()->getTitulo(),
                        "descripcion"=>$comentario->getIdItem()->getDescripcion(),
                        "iduser"=>$item->getIdItem()->getIdUser()->getId(),
                        "nameuser"=>!is_null($item->getIdItem()->getIdUser())?$item->getIdItem()->getIdUser()->getPrimerNombre()." ".$item->getIdItem()->getIdUser()->getPrimerApellido():[]
                    
                    );
                  }
                } 

               
            }    

            //$dataItems["Items"][]=array(
           
         return $dataspringitem;  

    }


    public function findItemsByIdSprint($param){
       
        $entityManager = $this->getEntityManager();        
        $dataItems["Items"]=[];
        
        $entity= $this->getEntityManager()->createQueryBuilder();
        $items= $entity->select("p,q,x")
            ->from("App\Entity\Proyecto\SprintItem","p")
            ->innerJoin('p.IdSpring', 'q')
            ->innerJoin('p.IdItem', "x")
            ->where('x.idBacklogPadre is null')
            ->andWhere('p.IdSpring='.$param)
            ->getQuery()
            ->getResult();
            $itemsComentarios=[];
            $itemsAdjuntos=[];
            $dataItems=array();
            foreach($items as $item){
               
                    if($item->getIdItem()->getItemAdjuntos()!=null){
                        foreach($item->getIdItem()->getItemAdjuntos()as $adjunto){
                            $itemsAdjuntos[]=array("id"=>$adjunto->getId(),
                            "Path"=>$adjunto->getPath(),
                            "user"=>$adjunto->getIdUser()->getPrimerNombre()." ".$adjunto->getIdUser()->getPrimerApellido());
                        }
                    } 
                    if($item->getIdItem()->getItemComentario()!=null){
                        foreach($item->getIdItem()->getItemComentario()as $comentario){
                            $itemsComentarios[]=array("id"=>$comentario->getId(),
                            "Comentario"=>$comentario->getComentario(),
                            "user"=>$comentario->getIdUser()->getPrimerNombre()." ".$comentario->getIdUser()->getPrimerApellido());

                        }
                    } 
                    $dataItems=array(
                    "titulo"=>$item->getIdItem()->getTitulo(),
                    "user"=>!is_null($item->getIdItem()->getIdUser())?$item->getIdItem()->getIdUser()->getPrimerNombre()." ".$item->getIdItem()->getIdUser()->getPrimerApellido():[],
                    "descripcion"=>$item->getIdItem()->getDescripcion(),
                    "tareas"=>array($this->getTareas($item->getIdItem()->getId())),
                    "comentarios"=>$itemsComentarios,
                    "adjuntos"=>$itemsAdjuntos);
            }    

            //$dataItems["Items"][]=array(
           
         return $dataItems;
    }

    private function getTareas($item){
        
        $dataItems=[];
        $entity= $this->getEntityManager()->createQueryBuilder();
        $items= $entity->select("p,q,x")
            ->from("App\Entity\Proyecto\SprintItem","p")
            ->innerJoin('p.IdSpring', 'q')
            ->innerJoin('p.IdItem', "x")
            ->where('x.idBacklogPadre='.$item)
            ->orderBy('p.IdItem','Asc')
            ->getQuery()
            ->getResult();
        $itemsComentarios=[];  
        $itemsAdjuntos=[]; 
        $dataItems=array(); 
        foreach($items as $item){
            if($item->getIdItem()->getItemComentario()!=null){
                foreach($item->getIdItem()->getItemComentario()as $comentario){
                    $itemsComentarios[]=array("id"=>$comentario->getId(),
                    "Comentario"=>$comentario->getComentario(),
                    "user"=>$comentario->getIdUser()->getPrimerNombre()." ".$comentario->getIdUser()->getPrimerApellido());
                
                }
            } 
            if($item->getIdItem()->getItemAdjuntos()!=null){
                foreach($item->getIdItem()->getItemAdjuntos()as $adjunto){
                    $itemsAdjuntos[]=array("id"=>$adjunto->getId(),
                    "Path"=>$adjunto->getPath(),
                    "user"=>$adjunto->getIdUser()->getPrimerNombre()." ".$adjunto->getIdUser()->getPrimerApellido());
                
                }
            } 
            $dataItems=array(
                    "titulo"=>$item->getIdItem()->getTitulo(),
                    "user"=>!is_null($item->getIdItem()->getIdUser())?$item->getIdItem()->getIdUser()->getPrimerNombre()." ".$item->getIdItem()->getIdUser()->getPrimerApellido():[],
                    "descripcion"=>$item->getIdItem()->getDescripcion(),
                    "tareas"=>$this->getTareas($item->getIdItem()->getId()),
                    "comentarios"=>$itemsComentarios,
                    "adjuntos"=>$itemsAdjuntos);
        }    
        return $dataItems;
    }

   
    /**
     * Create SpringItem vs Actividades y Tareas .
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new SprintItem(),$data);

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            //$currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            //$entity->setCreateBy($currentUser->getUserName());
            //$entity->setCreateBy($currentUser->getUserName());

            //$entity->setIdStatus($entityManager->getRepository(Status::class)->find(1)); 
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }

}
