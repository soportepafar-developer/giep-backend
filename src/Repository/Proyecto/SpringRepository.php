<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\Spring;
use App\Dto\Proyecto\SpringOutPutDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Entity\Proyecto\Empresa;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Spring|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spring|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spring[]    findAll()
 * @method Spring[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpringRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Spring::class);
    }
    /**
     * Lista Spring.
     */
    public function findList()
    {
        $data= $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $dataspring=array();
        foreach($data as $clave=>$valor){
            $springDto =new SpringOutPutDto();
            $springDto->id=$valor->getId();
            $springDto->nombre=$valor->getNombre();
            $springDto->fechainicio=$valor->getFechainicio();
            $springDto->fechafin=$valor->getFechafin();
            $springDto->idproyecto=($valor->getIdproyecto()!=null)?array("id"=>$valor->getIdproyecto()->getId(),"Nombre"=>$valor->getIdproyecto()->getNombre()):[];        
            //$paisDto->status=($valor->getIdStatus()!=null)?array("id"=>$valor->getIdStatus()->getId(),"Descripcion"=>$valor->getIdStatus()->getDescripcion()):[];        
            $dataspring[]=$springDto;
        }
       return array("data"=>$dataspring);
 
    }

     /**
     * Create Spring.
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new Spring(),$data);

        $entityFind =$entityManager->getRepository(Spring::class)->findBy(array("nombre"=>$data["nombre"],"idproyecto"=>$data["idproyecto"]));
        if (count($entityFind)>0) {
            return new JsonResponse(['msg'=>'Ya existe el nombre del spring para el proyecto: '],409);  
        }

        $sql="select id from spring where  idproyecto_id=".$data["idproyecto"]." and fechainicio >= '".$data["fechainicio"]."' and fechafin <= '".$data["fechafin"]."'";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rango=$stmt->fetchAll();
        if(count($rango)){
            return new JsonResponse(['msg'=>'El rango de fechas ya existe para otro spring: '],409);  
        }

        $sql="select max(fechafin) as fechafin from spring where  idproyecto_id=".$data["idproyecto"];
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rango=$stmt->fetchAll();
        if(count($rango)){
            if($rango[0]["fechafin"]>$data["fechainicio"]){
                return new JsonResponse(['msg'=>'La fecha de inicio debe ser mayor a la fecha final del sprint anterior: '],409);  
            }
        }


        if($data["fechafin"]<=$data["fechainicio"]){
            return new JsonResponse(['msg'=>'La fecha fin debe ser mayor a la fecha de inicio '],409);  
        }
     


        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());

            //$entity->setIdStatus($entityManager->getRepository(Status::class)->find(1)); 
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa); 
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }


    /**
     * Update Spring.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Spring::class)->find($id);
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


    // /**
    //  * @return Spring[] Returns an array of Spring objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Spring
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
