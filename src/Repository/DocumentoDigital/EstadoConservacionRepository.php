<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\EstadoConservacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use App\Dto\DocumentoDigital\EstadoConservacionOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
Use App\Entity\User;
use App\Entity\Proyecto\Empresa;

/**
 * @method EstadoConservacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoConservacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoConservacion[]    findAll()
 * @method EstadoConservacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoConservacionRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, EstadoConservacion::class);
    }

     /**
     * Listar Tipo Estado Conservacion.
     */
    public function findList($em)
    {
        $dataConservacion=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('estadoconservacion')
        ->from(EstadoConservacion::class,'estadoconservacion')
        ->addOrderBy('estadoconservacion.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $conservacionDto =new EstadoConservacionOutPutDto();
          $conservacionDto->id=$valor->getId();
          $conservacionDto->nombreconservacion=$valor->getNombreconservacion();
          $dataConservacion[]=$conservacionDto;
      }
       return array("data"=>$dataConservacion);
    }

    /**
     * Create Estado Conservacion.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entity = new EstadoConservacion();
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $entityManagerDefault = $this->getEntityManager();
            $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setCreateAt(new \DateTime());
            $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                  $entity->setIdempresa($empresa->getId());

            if($data['nombreconservacion']!="null"){
                  $entity->setNombreconservacion($data['nombreconservacion']);        
            }else{
                  return new JsonResponse(['msg'=>'Verifique Nombre Conservación Null '],404);
            }

            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }



    // /**
    //  * @return EstadoConservacion[] Returns an array of EstadoConservacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EstadoConservacion
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
