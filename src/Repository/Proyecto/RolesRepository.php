<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\Roles;
use App\Dto\Proyecto\RolesOutPutDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method Roles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Roles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Roles[]    findAll()
 * @method Roles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolesRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Roles::class);
    }

     /**
     * Lista Empresa Roles.
     */
    public function findList()
    {
        $data= $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $dataroles=array();
        foreach($data as $clave=>$valor){
            $rolesDto =new RolesOutPutDto();
            $rolesDto->id=$valor->getId();
            $rolesDto->roles=$valor->getRoles();
            $dataroles[]=$rolesDto;
        }
       return array("data"=>$dataroles);
 
    } 

     /**
     * Create Empresa Roles.
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new Roles(),$data);

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
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getRoles()],200);
        }    
    }
 
    /**
     * Update Empresa Roles.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Roles::class)->find($id);
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
    //  * @return Roles[] Returns an array of Roles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Roles
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
