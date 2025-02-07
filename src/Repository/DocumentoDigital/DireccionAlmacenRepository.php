<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\DireccionAlmacen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use App\Dto\DocumentoDigital\DireccionAlmacenOutPutDto;

/**
 * @method DireccionAlmacen|null find($id, $lockMode = null, $lockVersion = null)
 * @method DireccionAlmacen|null findOneBy(array $criteria, array $orderBy = null)
 * @method DireccionAlmacen[]    findAll()
 * @method DireccionAlmacen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DireccionAlmacenRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, DireccionAlmacen::class);
    }

    /**
     * Listar Status.
     */
    public function findList($em)
    {
        $dataDireciionalmacen=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('direccionalmacen')
        ->from(DireccionAlmacen::class,'direccionalmacen')
        ->addOrderBy('direccionalmacen.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new DireccionAlmacenOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->nombre=$valor->getNombre();
          $profesionDto->direccionzona=$valor->getDireccionzona();
          $profesionDto->telefono=$valor->getTelefono();
          $dataDireciionalmacen[]=$profesionDto;
      }
       return array("data"=>$dataDireciionalmacen);
    }


    // /**
    //  * @return DireccionAlmacen[] Returns an array of DireccionAlmacen objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DireccionAlmacen
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
