<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\SeccionEvaluacion360;
use App\Entity\User;
use App\Entity\Status;
use App\Entity\Instrumento360\PreguntaEvaluacion360;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use App\Dto\Instrumento360\SeccionEvaluacion360Dto;
//use App\Entity\Evaluacion\Evaluacion;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;
/**
 * @method SeccionEvaluacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeccionEvaluacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeccionEvaluacion[]    findAll()
 * @method SeccionEvaluacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeccionEvaluacion360Repository extends ServiceEntityRepository
{
    private $security;
    private $passwordEncoder;
    private $assetPackage;

    public function __construct(ManagerRegistry $registry,Security $security)
    {
       
        $this->security = $security;
        parent::__construct($registry, SeccionEvaluacion360::class);
    }  

    

}
