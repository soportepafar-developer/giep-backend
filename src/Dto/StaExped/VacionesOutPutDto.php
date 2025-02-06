<?php

namespace App\Dto\StaExped;

use App\Repository\StaExped\VacionesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VacionesRepository::class)
 */
class VacionesOutPutDto
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
    private $autorizacion_vacaciones;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodos_acumulados;

      /**
     * @ORM\Column(type="integer")
     */
    private $id_tipo_vacaciones;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_desde;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_hasta;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_incorporacion;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $periodo_difrute;

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

    public function getAutorizacionVacaciones(): ?string
    {
        return $this->autorizacion_vacaciones;
    }

    public function getPeriodosAcumulados(): ?int
    {
        return $this->periodos_acumulados;
    }

    public function getIdTipoVacaciones(): ?int
    {
        return $this->id_tipo_vacaciones;
    }

    public function getFechaDesde(): ?\DateTimeInterface
    {
        return $this->fecha_desde;
    }

    public function getFechaHasta(): ?\DateTimeInterface
    {
        return $this->fecha_hasta;
    }

    public function getFechaIncorporacion(): ?\DateTimeInterface
    {
        return $this->fecha_incorporacion;
    }

    public function getPeriodoDifrute(): ?string
    {
        return $this->periodo_difrute;
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
