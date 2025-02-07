<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\TipoAlmacen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Dto\DocumentoDigital\TipoAlmacenOutPutDto;
Use App\Entity\User;
use App\Entity\Proyecto\Empresa;

/**
 * @method TipoAlmacen|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoAlmacen|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoAlmacen[]    findAll()
 * @method TipoAlmacen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoAlmacenRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TipoAlmacen::class);
    }

     /**
     * Listar Tipo Almacen.
     */
    public function findList($em)
    {
        $dataAlmacen=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('tipoalmacen')
        ->from(TipoAlmacen::class,'tipoalmacen')
        ->addOrderBy('tipoalmacen.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new TipoAlmacenOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->nombrealmacen=$valor->getNombrealmacen();
          $profesionDto->iddireccionalmacen=($valor->getIddireccionalmacen()!=null)?array("id"=>$valor->getIddireccionalmacen()->getId(),"Nombre"=>$valor->getIddireccionalmacen()->getNombre(),"Direccion"=>$valor->getIddireccionalmacen()->getDireccionzona()):[]; 

          $dataAlmacen[]=$profesionDto;
      }
       return array("data"=>$dataAlmacen);
    }

    /**
     * Listar Tipo Almacen id.
     */
    public function findListid($id,$em)
    {
        $dataAlmacen=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('tipoalmacen')
        ->from(TipoAlmacen::class,'tipoalmacen')
        ->where("tipoalmacen.iddireccionalmacen ='".$id."'")
        ->addOrderBy('tipoalmacen.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new TipoAlmacenOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->nombrealmacen=$valor->getNombrealmacen();
          $profesionDto->iddireccionalmacen=($valor->getIddireccionalmacen()!=null)?array("id"=>$valor->getIddireccionalmacen()->getId(),"Nombre"=>$valor->getIddireccionalmacen()->getNombre(),"Direccion"=>$valor->getIddireccionalmacen()->getDireccionzona()):[]; 

          $dataAlmacen[]=$profesionDto;
      }
       return array("data"=>$dataAlmacen);
    }

     /**
     * Create Tipo Almacen.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entityManagerDefault = $this->getEntityManager();
        $entity=$helper->setParametersToEntity(new TipoAlmacen(),$data);
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
    //  * @return TipoAlmacen[] Returns an array of TipoAlmacen objects
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
    public function findOneBySomeField($value): ?TipoAlmacen
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
