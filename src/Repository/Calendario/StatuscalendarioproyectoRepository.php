<?php

namespace App\Repository\Calendario;

use App\Entity\Calendario\Statuscalendarioproyecto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Dto\Calendario\StatuscalendarioproyectoOutPutDto;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;

/**
 * @method Statuscalendarioproyecto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statuscalendarioproyecto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statuscalendarioproyecto[]    findAll()
 * @method Statuscalendarioproyecto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatuscalendarioproyectoRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Statuscalendarioproyecto::class);
    }

     /**
     * Create Status Calendario Proyecto.
     */
    public function post($data,$validator,$helper): JsonResponse  {

        //var_dump($data);die;

        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new Statuscalendarioproyecto(),$data);

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setCreateBy($currentUser->getUserName());
            //$entity->setHoraestimadas(intval($data['horaestimadas']));

            //$entity->setHoraestimadas(180);


            //var_dump($data['horaestimadas']);die;

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
     * Listar Status Calendario Proyecto.
     */
    public function findList()
    {
        $data= $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $dataStatuslaboral=[];
        foreach($data as $clave=>$valor){
            $statusloboralDto =new StatuscalendarioproyectoOutPutDto();
            $statusloboralDto->id=$valor->getId();
            $statusloboralDto->estado=$valor->getEstado();
            $statusloboralDto->color=$valor->getColor();

            //$cargoDto->status=($valor->getIdStatus()!=null)?array("id"=>$valor->getIdStatus()->getId(),"Descripcion"=>$valor->getIdStatus()->getDescripcion()):[];        
            $dataStatuslaboral[]=$statusloboralDto;
        }
       return array("data"=>$dataStatuslaboral);
     }

     /**
     * Update Status Calendario Proyecto.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Statuscalendarioproyecto::class)->find($id);
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
    //  * @return Statuscalendarioproyecto[] Returns an array of Statuscalendarioproyecto objects
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
    public function findOneBySomeField($value): ?Statuscalendarioproyecto
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
