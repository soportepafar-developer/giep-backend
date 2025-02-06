<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\TipoReposo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Dto\StaExped\TipoReposoOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method TipoReposo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoReposo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoReposo[]    findAll()
 * @method TipoReposo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoReposoRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TipoReposo::class);
    }

     /**
     * Listar Tipos Respuestas.
     */
    public function findList($em)
    {
        $dataArea=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('tipo_reposo')
        ->from(TipoReposo::class,'tipo_reposo')
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new TipoReposoOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->desctiporeposo=$valor->getTiporeposo();
          $dataArea[]=$profesionDto;
      }

      

       return array("data"=>$dataArea);
    }

    
      /**
     * Create Tipo Reposo.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new TipoReposo(),$data);
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
    //  * @return TipoReposo[] Returns an array of TipoReposo objects
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
    public function findOneBySomeField($value): ?TipoReposo
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
