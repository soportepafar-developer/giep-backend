<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\Competencia360NivelPonderacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Competencia360NivelPonderacionRepository|null find($id, $lockMode = null, $lockVersion = null)
 * @method Competencia360NivelPonderacionRepository|null findOneBy(array $criteria, array $orderBy = null)
 * @method Competencia360NivelPonderacionRepository[]    findAll()
 * @method Competencia360NivelPonderacionRepository[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Competencia360NivelPonderacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competencia360NivelPonderacion::class);
    }

    
}
