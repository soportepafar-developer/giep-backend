<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\EstadoConservacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use App\Dto\DocumentoDigital\EstadoConservacionOutPutDto;

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
