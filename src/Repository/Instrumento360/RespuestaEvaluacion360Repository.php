<?php

namespace App\Repository\Instrumento360;
use App\Entity\Instrumento360\RespuestaEvaluacion360;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


use App\Dto\Instrumento360\PreguntaEvaluacion360Dto;
use App\Entity\Instrumento360\PreguntaEvaluacion360;
//use App\Entity\Evaluacion\TipoCategoriaEvaluacion;
use App\Entity\Instrumento360\TipoInputEvaluacion;
//use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Instrumento360\OpcionesEvaluacion;
//use App\Entity\Evaluacion\EvaluacionUsuario;
use App\Entity\User;
use App\Entity\Status;
use App\Entity\Proyecto\Empresa;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
//use Proxies\__CG__\App\Entity\Evaluacion\Evaluacion as Evaluacion;

/**
 * @method RespuestaEvaluacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method RespuestaEvaluacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method RespuestaEvaluacion[]    findAll()
 * @method RespuestaEvaluacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RespuestaEvaluacion360Repository extends ServiceEntityRepository
{
    private $security;
    private $totalCount=0;
    private $muestra=0;

    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, RespuestaEvaluacion360::class);
    }



}
