<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\Ubicacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use App\Dto\DocumentoDigital\UbicacionOutPutDto;

/**
 * @method Ubicacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ubicacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ubicacion[]    findAll()
 * @method Ubicacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UbicacionRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Ubicacion::class);
    }

/**
     * Listar Tipo Almacen.
     */
    public function findList($em)
    {
        $dataUbicacion=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('ubicacion')
        ->from(Ubicacion::class,'ubicacion')
        ->addOrderBy('ubicacion.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new UbicacionOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->descripcion=$valor->getDescripcion();
          $dataUbicacion[]=$profesionDto;
      }
       return array("data"=>$dataUbicacion);
    }

    // /**
    //  * @return Ubicacion[] Returns an array of Ubicacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ubicacion
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
