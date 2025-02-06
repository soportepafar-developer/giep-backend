<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\TipoMotivoPermiso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


Use App\Entity\User;
use App\Dto\StaExped\TipoMotivoPermisoOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;

/**
 * @method TipoMotivoPermiso|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoMotivoPermiso|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoMotivoPermiso[]    findAll()
 * @method TipoMotivoPermiso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoMotivoPermisoRepository extends ServiceEntityRepository
{

    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TipoMotivoPermiso::class);
    }

     /**
     * Listar Tipos Respuestas.
     */
    public function findList($em)
    {
        $dataArea=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('tipo_motivo_permiso')
        ->from(TipoMotivoPermiso::class,'tipo_motivo_permiso')
        ->addOrderBy('tipo_motivo_permiso.permiso', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new TipoMotivoPermisoOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->descpermisos=$valor->getPermiso();
          $dataArea[]=$profesionDto;
      }

      

       return array("data"=>$dataArea);
    }


      /**
     * Create Tipo Motivo Permiso.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new TipoMotivoPermiso(),$data);
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
    //  * @return TipoMotivoPermiso[] Returns an array of TipoMotivoPermiso objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TipoMotivoPermiso
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
