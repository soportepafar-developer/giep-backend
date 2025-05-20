<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\TipoInstrumento360;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


use App\Entity\Status;

Use App\Entity\User;
use App\Dto\Instrumento360\TipoInstrumento360Dto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;


/**
 * @method TipoInstrumento360|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoInstrumento360|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoInstrumento360[]    findAll()
 * @method TipoInstrumento360[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoInstrumento360Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, TipoInstrumento360::class);
    }



}
