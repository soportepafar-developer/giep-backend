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
     * @ORM\ManyToOne(targetEntity=Cargo::class)
     */
    public $cargo;

    /**
     * @ORM\ManyToOne(targetEntity=NivelDominio::class)
     */
    public $dominio;

    /**
     * @ORM\ManyToOne(targetEntity=Competencia360::class)
     */
    public $competencia;

    /**
     * @ORM\ManyToOne(targetEntity=EstructuraOrganizativa::class)
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

    public function getCargo(): ?Cargo
    {
        return $this->cargo;
    }


    public function getDominio(): ?NivelDominio
    {
        return $this->dominio;
    }


    public function getCompetencia(): ?Competencia360
    {
        return $this->competencia;
    }


    public function getUnidad(): ?EstructuraOrganizativa
    {
        return $this->unidad;
    }


    public function getPrioridad(): ?int
    {
        return $this->prioridad;
    }

    
}
