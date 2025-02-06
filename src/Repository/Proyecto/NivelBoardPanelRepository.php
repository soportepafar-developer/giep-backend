<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\NivelBoardPanel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method NivelBoardPanel|null find($id, $lockMode = null, $lockVersion = null)
 * @method NivelBoardPanel|null findOneBy(array $criteria, array $orderBy = null)
 * @method NivelBoardPanel[]    findAll()
 * @method NivelBoardPanel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NivelBoardPanelRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, NivelBoardPanel::class);
    }


    /**
     * Listar Nivel Board Panel.
     */
    public function findList2()
    {
        $data= $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $dataPanel=[];
        $dataNivelBoard=array();
        foreach($data as $clave=>$valor){
            $dataNivelBoard[]=array("id"=>$valor->getId(),"nombrenivelpanel"=>$valor->getNivelpanel(),"attr_key"=>$valor->getAttrKey());
        }
        return array("data"=>$dataNivelBoard);
    }

    // /**
    //  * @return NivelBoardPanel[] Returns an array of NivelBoardPanel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NivelBoardPanel
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
