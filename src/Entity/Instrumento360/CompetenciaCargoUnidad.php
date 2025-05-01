<?php

namespace App\Entity\Instrumento360;

use App\Entity\Cargo;
use App\Entity\EstructuraOrganizativa;
use App\Entity\Proyecto\Empresa;
use App\Repository\Instrumento360\CompetenciaCargoUnidadRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompetenciaCargoUnidadRepository::class)
 */
class CompetenciaCargoUnidad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cargo::class)
     */
    private $cargo;

    /**
     * @ORM\ManyToOne(targetEntity=NivelDominio::class)
     */
    private $dominio;

    /**
     * @ORM\ManyToOne(targetEntity=Competencia360::class)
     */
    private $competencia;

    /**
     * @ORM\ManyToOne(targetEntity=EstructuraOrganizativa::class)
     */
    private $unidad;

    /**
     * @ORM\Column(type="integer")
     */
    private $prioridad;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    private $empresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCargo(): ?Cargo
    {
        return $this->cargo;
    }

    public function setCargo(?Cargo $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    public function getDominio(): ?NivelDominio
    {
        return $this->dominio;
    }

    public function setDominio(?NivelDominio $dominio): self
    {
        $this->dominio = $dominio;

        return $this;
    }

    public function getCompetencia(): ?Competencia360
    {
        return $this->competencia;
    }

    public function setCompetencia(?Competencia360 $competencia): self
    {
        $this->competencia = $competencia;

        return $this;
    }

    public function getUnidad(): ?EstructuraOrganizativa
    {
        return $this->unidad;
    }

    public function setUnidad(?EstructuraOrganizativa $unidad): self
    {
        $this->unidad = $unidad;

        return $this;
    }

    public function getPrioridad(): ?int
    {
        return $this->prioridad;
    }

    public function setPrioridad(int $prioridad): self
    {
        $this->prioridad = $prioridad;

        return $this;
    }

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }
}
