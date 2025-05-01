<?php
namespace App\Dto\Instrumento360;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class CompetenciaCargoUnidadDto
{
    /**
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $cargo;

    /**
     * @ORM\Column(type="integer")
     */

    public $dominio;

    /**
     * @ORM\Column(type="integer")
     */
    public $competencia;

    /**
     * @ORM\Column(type="integer")
     */

    public $unidad;

    /**
     * @ORM\Column(type="integer")
     */
    public $prioridad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCargo(): ?int
    {
        return $this->cargo;
    }


    public function getDominio(): ?int
    {
        return $this->dominio;
    }


    public function getCompetencia(): ?int
    {
        return $this->competencia;
    }


    public function getUnidad(): ?int
    {
        return $this->unidad;
    }


    public function getPrioridad(): ?int
    {
        return $this->prioridad;
    }

    
}
