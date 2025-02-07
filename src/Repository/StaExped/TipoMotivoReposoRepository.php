<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\TipoMotivoReposo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


Use App\Entity\User;
use App\Dto\StaExped\TipoMotivoReposoOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;


/**
 * @method TipoMotivoReposo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoMotivoReposo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoMotivoReposo[]    findAll()
 * @method TipoMotivoReposo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoMotivoReposoRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TipoMotivoReposo::class);
    }

     /**
     * Listar Tipos Respuestas.
     */
    public function findList($em)
    {
        $dataArea=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('tipo_motivo_reposo')
        ->from(TipoMotivoReposo::class,'tipo_motivo_reposo')
        ->addOrderBy('tipo_motivo_reposo.motivo', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new TipoMotivoReposoOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->descreposo=$valor->getMotivo();
          $dataArea[]=$profesionDto;
      }

      

       return array("data"=>$dataArea);
    }


      /**
     * Create Tipo Motivos Reposo.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new TipoMotivoReposo(),$data);
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setCreateAt(new \DateTime());
            $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
             $entity->setIdempresa($empresa);
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }



    // /**
    //  * @return TipoMotivoReposo[] Returns an array of TipoMotivoReposo objects
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
    public function findOneBySomeField($value): ?TipoMotivoReposo
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
