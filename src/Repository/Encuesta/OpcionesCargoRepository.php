<?php

namespace App\Repository\Encuesta;

use App\Entity\Encuesta\OpcionesCargo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;



/**
 * @method OpcionesCargo|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpcionesCargo|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpcionesCargo[]    findAll()
 * @method OpcionesCargo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpcionesCargoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpcionesCargo::class);
    }


}
