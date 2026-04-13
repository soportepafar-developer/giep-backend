<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\TipoEstadod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Dto\DocumentoDigital\TipoEstadoOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method TipoEstadod|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoEstadod|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoEstadod[]    findAll()
 * @method TipoEstadod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoEstadodRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TipoEstadod::class);
    }

     /**
     * Listar Tipo Estado.
     */
    public function findList()
    {
        $data= $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $dataStatuslaboral=[];
        foreach($data as $clave=>$valor){
            $statusloboralDto =new TipoEstadoOutPutDto();
            $statusloboralDto->id=$valor->getId();
            $statusloboralDto->nombre_status=$valor->getNombreStatus();
            //$cargoDto->status=($valor->getIdStatus()!=null)?array("id"=>$valor->getIdStatus()->getId(),"Descripcion"=>$valor->getIdStatus()->getDescripcion()):[];        
            $dataStatuslaboral[]=$statusloboralDto;
        }
       return array("data"=>$dataStatuslaboral);
 



    }

    



    // /**
    //  * @return TipoEstadod[] Returns an array of TipoEstado objects
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
    public function findOneBySomeField($value): ?TipoEstadod
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
