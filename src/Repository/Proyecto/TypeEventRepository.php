<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\TypeEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method TypeEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeEvent[]    findAll()
 * @method TypeEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeEventRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TypeEvent::class);
    }

       /**
     * Listar Tipe Items Event.
     */
    public function findtypeitemsevent()
    {
        $data= $this->createQueryBuilder('c')
            ->where("c.id <>2")
            ->andWhere("c.id <>4")
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $dataPanel=[];
        $dataNivelBoard=array();
        foreach($data as $clave=>$valor){
            $dataNivelBoard[]=array("id"=>$valor->getId(),"label"=>$valor->getTipodescripcion(),"icon_class"=>$valor->getIconClass(),"color"=>$valor->getColor());
        }
        return array("data"=>$dataNivelBoard);
    }



    // /**
    //  * @return TypeEvent[] Returns an array of TypeEvent objects
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
    public function findOneBySomeField($value): ?TypeEvent
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
