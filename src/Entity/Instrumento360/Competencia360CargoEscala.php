<?php

namespace App\Entity\Instrumento360;

use App\Entity\Cargo;
use App\Entity\Proyecto\Empresa;
use App\Repository\Encuesta\Competencia360CargoEscalaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=Competencia360CargoEscalaRepository::class)
 */
class Competencia360CargoEscala
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cargo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $cargo;

    /**
     * @ORM\ManyToOne(targetEntity=Competencia360::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $competencia;

    /**
     * @ORM\Column(type="integer")
     */
    private $escala;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idcategoriacargoescala")
     */
    private $idempresa;



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

    public function getCompetencia(): ?Competencia360
    {
        return $this->competencia;
    }

    public function setCompetencia(?Competencia360 $competencia): self
    {
        $this->competencia = $competencia;

        return $this;
    }

    public function getEscala(): ?int
    {
        return $this->escala;
    }

    public function setEscala(int $escala): self
    {
        $this->escala = $escala;

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
}
