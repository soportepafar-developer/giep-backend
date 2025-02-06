<?php

namespace App\Entity\StaExped;

use App\Repository\StaExped\OtrosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OtrosRepository::class)
 */
class Otros
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

     /**
     * @ORM\Column(type="integer")
     */
    private $id_datos_personales;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $expedientes_legal;

     /**
     * @ORM\Column(type="integer")
     */
    private $motivo;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $curso_desarrollo_area_laboral;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profesion_orientada_area_servicio;

     /**
     * @ORM\Column(type="integer")
     */
    private $id_categoria_otros;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $createBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $updateBy;

     /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDatosPersonales(): ?int
    {
        return $this->id_datos_personales;
    }

    public function setIdDatosPersonales(int $id_datos_personales): self
    {
        $this->id_datos_personales = $id_datos_personales;

        return $this;
    }

    public function getExpedientesLegal(): ?string
    {
        return $this->expedientes_legal;
    }

    public function setExpedientesLegal(string $expedientes_legal): self
    {
        $this->expedientes_legal = $expedientes_legal;

        return $this;
    }

    public function getMotivo(): ?int
    {
        return $this->motivo;
    }

    public function setMotivo(int $motivo): self
    {
        $this->motivo = $motivo;

        return $this;
    }

    public function getCursoDesarrolloAreaLaboral(): ?string
    {
        return $this->curso_desarrollo_area_laboral;
    }

    public function setCursoDesarrolloAreaLaboral(string $curso_desarrollo_area_laboral): self
    {
        $this->curso_desarrollo_area_laboral = $curso_desarrollo_area_laboral;

        return $this;
    }

    public function getProfesionOrientadaAreaServicio(): ?string
    {
        return $this->profesion_orientada_area_servicio;
    }

    public function setProfesionOrientadaAreaServicio(string $profesion_orientada_area_servicio): self
    {
        $this->profesion_orientada_area_servicio = $profesion_orientada_area_servicio;

        return $this;
    }

    public function getIdCategoriaOtros(): ?int
    {
        return $this->id_categoria_otros;
    }

    public function setIdCategoriaOtros(int $id_categoria_otros): self
    {
        $this->id_categoria_otros = $id_categoria_otros;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getCreateBy(): ?string
    {
        return $this->createBy;
    }

    public function setCreateBy(?string $createBy): self
    {
        $this->createBy = $createBy;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }

    public function setUpdateBy(string $updateBy): self
    {
        $this->updateBy = $updateBy;

        return $this;
    }

    public function getIdempresa(): ?int
    {
        return $this->idempresa;
    }

    public function setIdempresa(int $idempresa): self
    {
        $this->idempresa = $idempresa;

        return $this;
    }
}
