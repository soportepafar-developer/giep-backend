<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\TiposRespuestas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

Use App\Entity\User;
use App\Dto\StaExped\TiposRespuestasOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method TiposRespuestas|null find($id, $lockMode = null, $lockVersion = null)
 * @method TiposRespuestas|null findOneBy(array $criteria, array $orderBy = null)
 * @method TiposRespuestas[]    findAll()
 * @method TiposRespuestas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TiposRespuestasRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TiposRespuestas::class);
    }

     /**
     * Listar Tipos Respuestas.
     */
    public function findList($em)
    {
        $dataTiposRespuestas=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('tipos_respuestas')
        ->from(TiposRespuestas::class,'tipos_respuestas')
        //->addOrderBy('id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $tiposrespuestasDto =new TiposRespuestasOutPutDto();
          $tiposrespuestasDto->id=$valor->getId();
          $tiposrespuestasDto->respuestas=$valor->getRespuestas();
          $dataTiposRespuestas[]=$tiposrespuestasDto;
      }
       return array("data"=>$dataTiposRespuestas);
    }
    
      /**
     * Create Tipos Respuestas.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new TiposRespuestas(),$data);
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
    //  * @return TiposRespuestas[] Returns an array of TiposRespuestas objects
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
    public function findOneBySomeField($value): ?TiposRespuestas
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
