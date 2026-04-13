<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\SubSubSerie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use App\Dto\DocumentoDigital\SubSubSerieOutPutDto;

/**
 * @method SubSerie|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubSerie|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubSerie[]    findAll()
 * @method SubSerie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubSubSerieRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, SubSubSerie::class);
    }


/**
     * Listar Tipo Almacen id.
     */
    public function findListid($id,$em)
    {
        $dataSubSubserie=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('subsubserie')
        ->from(SubSubSerie::class,'subsubserie')
        ->where("subsubserie.id_subserie ='".$id."'")
        ->addOrderBy('subsubserie.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $subSubSerieDto =new SubSubSerieOutPutDto();
          $subSubSerieDto->id=$valor->getId();
          $subSubSerieDto->nombresubsubserie=$valor->getNombreSubSubserie();
          $dataSubSubserie[]=$subSubSerieDto;
      }
       return array("data"=>$dataSubSubserie);
    }

    // /**
    //  * @return SubSerie[] Returns an array of SubSerie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubSerie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
