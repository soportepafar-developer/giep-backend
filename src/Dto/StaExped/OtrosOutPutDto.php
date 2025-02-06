<?php

namespace App\Dto\StaExped;
use App\Repository\StaExped\OtrosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OtrosRepository::class)
 */
class OtrosOutPutDto
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
     * @ORM\Column(type="string", length=255)
     */
    private $updateBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDatosPersonales(): ?int
    {
        return $this->id_datos_personales;
    }

    public function getExpedientesLegal(): ?string
    {
        return $this->expedientes_legal;
    }

    public function getMotivo():  ?int
    {
        return $this->motivo;
    }

    public function getCursoDesarrolloAreaLaboral(): ?string
    {
        return $this->curso_desarrollo_area_laboral;
    }

    public function getProfesionOrientadaAreaServicio(): ?string
    {
        return $this->profesion_orientada_area_servicio;
    }

    public function getIdCategoriaOtros(): ?int
    {
        return $this->id_categoria_otros;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function getCreateBy(): ?string
    {
        return $this->createBy;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }

}
