<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\Area;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Dto\StaExped\AreaOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method Area|null find($id, $lockMode = null, $lockVersion = null)
 * @method Area|null findOneBy(array $criteria, array $orderBy = null)
 * @method Area[]    findAll()
 * @method Area[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreaRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Area::class);
    }

     /**
     * Listar Tipos Respuestas.
     */
    public function findList($em)
    {
        $dataArea=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('area')
        ->from(Area::class,'area')
        ->addOrderBy('area.descarea', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new AreaOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->descprofesion=$valor->getDescarea();
          $dataArea[]=$profesionDto;
      }

      

       return array("data"=>$dataArea);
    }


    public function getAreaById($id,$em){
        //$entityManager = $this->getEntityManager();
        $dataArea=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('area')
        ->from(Area::class,'area')
        ->Where('area.iddepartamentos='.$id)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new AreaOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->descprofesion=$valor->getDescarea();
          $dataArea[]=$profesionDto;
      }
  
       return array("data"=>$dataArea);
    }

      /**
     * Create Area.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new Area(),$data);
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setCreateAt(new \DateTime());
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }


    // /**
    //  * @return Area[] Returns an array of Area objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Area
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
