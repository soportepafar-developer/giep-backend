<?php

namespace App\Entity\Instrumento360;

use App\Entity\Nivel;
use App\Entity\Instrumento360\NivelDominio;
use App\Entity\Proyecto\Empresa;
use App\Repository\Instrumento360\Competencia360NivelPonderacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=Competencia360NivelPonderacionRepository::class)
 */
class Competencia360NivelPonderacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Competencia360::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $competencia;

    /**
     * @ORM\ManyToOne(targetEntity=Nivel::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $nivel;

    /**
     * @ORM\Column(type="integer")
     */
    private $ponderacion;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idcategorianivelponderacion")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNivel(): ?Nivel
    {
        return $this->nivel;
    }

    public function setNivel(?Nivel $nivel): self
    {
        $this->nivel = $nivel;

        return $this;
    }

    public function getPonderacion(): ?int
    {
        return $this->ponderacion;
    }

    public function setPonderacion(int $ponderacion): self
    {
        $this->ponderacion = $ponderacion;

        return $this;
    }

    public function getIdempresa(): ?Empresa
    {
        return $this->idempresa;
    }

    public function setIdempresa(?Empresa $idempresa): self
    {
        $this->idempresa = $idempresa;

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
}
