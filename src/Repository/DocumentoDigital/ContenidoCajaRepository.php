<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\ContenidoCaja;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use App\Dto\DocumentoDigital\ContenidoCajaOutPutDto;

/**
 * @method ContenidoCaja|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContenidoCaja|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContenidoCaja[]    findAll()
 * @method ContenidoCaja[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContenidoCajaRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, ContenidoCaja::class);
    }

    /**
     * Listar Tipo Almacen.
     */
    public function findList($em)
    {
        $dataContenidoCaja=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('contenidocaja')
        ->from(ContenidoCaja::class,'contenidocaja')
        ->addOrderBy('contenidocaja.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new ContenidoCajaOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->nombreestuche=$valor->getNombreEstuche();
          $dataContenidoCaja[]=$profesionDto;
      }
       return array("data"=>$dataContenidoCaja);
    }


    // /**
    //  * @return ContenidoCaja[] Returns an array of ContenidoCaja objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContenidoCaja
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
