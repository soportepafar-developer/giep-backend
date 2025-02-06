<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\Traza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


Use App\Entity\User;
Use App\Entity\Proyecto\AccionTraza;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;

/**
 * @method Traza|null find($id, $lockMode = null, $lockVersion = null)
 * @method Traza|null findOneBy(array $criteria, array $orderBy = null)
 * @method Traza[]    findAll()
 * @method Traza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrazaRepository extends ServiceEntityRepository
{


    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Traza::class);
    }


     /**
     * Create Traza.
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new Traza(),$data);

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $entity->setAccion($entityManager->getRepository(AccionTraza::class)->find($data["accion"])); 
            $entity->setCreateAt(new \DateTime('now'));
            $currentUser =$entityManager->getRepository(User::class)->find($data["createdBy"]);
            $entity->setCreatedBy($currentUser);
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);  
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }


    /**
     * Update Traza.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Traza::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }
        $entity=$helper->setParametersToEntity($entity,$data);
        $entity->setAccion($entityManager->getRepository(AccionTraza::class)->find($data["accion"])); 
        $entity->setCreateAt(new \DateTime('now'));
        $currentUser =$entityManager->getRepository(User::class)->find($data["createdBy"]);
        $entity->setCreatedBy($currentUser);
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


}
