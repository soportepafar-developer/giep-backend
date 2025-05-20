<?php

namespace App\Repository\Instrumento360;

use App\Dto\Evaluacion\PreguntaEvaluacion360Dto;
use App\Entity\Instrumento360\PreguntaEvaluacion360;
//use App\Entity\Instrumento360\TipoCategoriaEvaluacion;
use App\Entity\Instrumento360\TipoInputEvaluacion360;
//use App\Entity\Instrumento360\OpcionesCargoEvaluacion;
//use App\Entity\Instrumento360\Evaluacion;
use App\Entity\Instrumento360\OpcionesEvaluacion360;
use App\Entity\User;
use App\Entity\Status;
use App\Entity\Instrumento360\SeccionEvaluacion360;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;


/**
 * @method PreguntaEvaluacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method PreguntaEvaluacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method PreguntaEvaluacion[]    findAll()
 * @method PreguntaEvaluacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreguntaEvaluacionRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, PreguntaEvaluacion360::class);
    }



}
