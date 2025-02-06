<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\Profesion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Dto\StaExped\ProfesionOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;

/**
 * @method Profesion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profesion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profesion[]    findAll()
 * @method Profesion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfesionRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Profesion::class);
    }

     /**
     * Listar Tipos Respuestas.
     */
    public function findList($em)
    {
        $dataProfesion=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('profesion')
        ->from(Profesion::class,'profesion')
        ->addOrderBy('profesion.descprofesion', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new ProfesionOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->descprofesion=$valor->getDescprofesion();
          $dataProfesion[]=$profesionDto;
      }

      

       return array("data"=>$dataProfesion);
    }

    /**
     * Create Datos Profesiones.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new Profesion(),$data);
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
    //  * @return Profesion[] Returns an array of Profesion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Profesion
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
