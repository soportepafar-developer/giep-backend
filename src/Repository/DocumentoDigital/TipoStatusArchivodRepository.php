<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\TipoStatusArchivod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use App\Dto\DocumentoDigital\TipoStatusArchivoOutPutDto;

/**
 * @method TipoStatusArchivod|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoStatusArchivod|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoStatusArchivod[]    findAll()
 * @method TipoStatusArchivod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoStatusArchivodRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TipoStatusArchivod::class);
    }

    /**
     * Listar Status.
     */
    public function findList($em)
    {
        $dataStatus=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('tipostatusarchivo')
        ->from(TipoStatusArchivod::class,'tipostatusarchivo')
        ->addOrderBy('tipostatusarchivo.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new TipoStatusArchivoOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->nombrestatus=$valor->getNombreStatus();
          $profesionDto->idempresa=$valor->getIdempresa();
          $dataStatus[]=$profesionDto;
      }
       return array("data"=>$dataStatus);
    }

    // /**
    //  * @return TipoStatusArchivod[] Returns an array of TipoStatusArchivo objects
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
    public function findOneBySomeField($value): ?TipoStatusArchivod
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