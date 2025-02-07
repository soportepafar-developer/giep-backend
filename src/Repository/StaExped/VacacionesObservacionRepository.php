<?php

namespace App\Repository\StaExped;

use App\Entity\StaExped\VacacionesObservacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Dto\StaExped\VacacionesObservacionOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method VacacionesObservacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacacionesObservacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacacionesObservacion[]    findAll()
 * @method VacacionesObservacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacacionesObservacionRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, VacacionesObservacion::class);
    }

    public function getVacacionesobservacionByCi($id,$em){
        $dataDatosObservacion=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('vacaciones_observacion.id,vacaciones_observacion.id_datos_personales,
        vacaciones_observacion.observacion')
        ->from(VacacionesObservacion::class,'vacaciones_observacion') 
        ->Where('vacaciones_observacion.id_datos_personales='.$id)
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        foreach($data as $clave=>$valor){
          $observacionDto =new VacacionesObservacionOutPutDto();
          $observacionDto->id=$valor["id"];
          $observacionDto->idDatosPersonales=$valor["id_datos_personales"];
          $observacionDto->idDatosObservacion=$valor["observacion"];
          $dataDatosObservacion[]=$observacionDto;
      }
       return array("data"=>$dataDatosObservacion);
    }

      /**
     * Create Observación Vacaciones.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new VacacionesObservacion(),$data);
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
               $entity->setIdempresa($empresa->getId());
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }


/**
     * Update Observación Vacaciones.
     */
    public function put($data,$id,$validator,$helper,$em): JsonResponse  
    {
           $queryresp = $em->createQueryBuilder();
           $queryresp->update(VacacionesObservacion::class,'vacaciones_observacion')
          ->set('vacaciones_observacion.observacion', ':observacion')
          ->Where('vacaciones_observacion.id='.$id)
          ->setParameter('observacion', $data['observacion'])
          ->getQuery();
          $queryrespdata = $queryresp->getQuery();
          $dataresp =  $queryrespdata->execute();
          return new JsonResponse(['msg'=>'Registro Actualizado: '.$id],200);
    }




    // /**
    //  * @return VacacionesObservacion[] Returns an array of VacacionesObservacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VacacionesObservacion
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
