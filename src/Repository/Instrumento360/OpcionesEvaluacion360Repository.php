<?php

namespace App\Repository\Instrumento360;

use App\Dto\Instrumento360\OpcionesEvaluacion360OutPutDto;
use App\Entity\Instrumento360\OpcionesEvaluacion360;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

Use App\Entity\User;
use App\Entity\Proyecto\Empresa;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method OpcionesEvaluacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpcionesEvaluacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpcionesEvaluacion[]    findAll()
 * @method OpcionesEvaluacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpcionesEvaluacion360Repository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, OpcionesEvaluacion360::class);
    }

    

}
