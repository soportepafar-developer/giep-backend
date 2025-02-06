<?php

namespace App\Repository\Calendario;

use App\Entity\Calendario\Calendarios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
Use App\Entity\Calendario\Diasnolaborables;
use App\Dto\Calendario\CalendariosOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method Calendarios|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calendarios|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calendarios[]    findAll()
 * @method Calendarios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendariosRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Calendarios::class);
    }
    
    /**
     * Create Calendarios.
     */
    public function post($data,$validator,$helper): JsonResponse  {

        //var_dump($data);die;

        $entityManager = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new Calendarios(),$data);

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
     * Listar Calendarios.
     */
    public function findList()
    {
        $entityManager = $this->getEntityManager();
        $data= $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $dataCalendarios=[];
        foreach($data as $clave=>$valor){
            $CalendariosDto =new CalendariosOutPutDto();
            $CalendariosDto->id=$valor->getId();
            $Diasnolaborables = $entityManager->getRepository(Diasnolaborables::class)->find($valor->getIdDiasNolaborables());
            $CalendariosDto->id_dias_nolaborables=$Diasnolaborables->getId();
            $CalendariosDto->descripcion=($Diasnolaborables->getDescripcion());
            $CalendariosDto->fecha_desde=!is_null($valor->getFechaDesde())?$valor->getFechaDesde()->format("Y-m-d"):null;
            $CalendariosDto->fecha_hasta=!is_null($valor->getFechaHasta())?$valor->getFechaHasta()->format("Y-m-d"):null;
            $dataCalendarios[]=$CalendariosDto;
        }
       return array("data"=>$dataCalendarios);
 
    }
   
    /**
     * Update Calendarios.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Calendarios::class)->find($id);
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
    //  * @return Calendarios[] Returns an array of Calendarios objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Calendarios
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
