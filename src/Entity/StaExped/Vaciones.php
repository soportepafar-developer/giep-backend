<?php

namespace App\Entity\StaExped;

use App\Repository\StaExped\VacionesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VacionesRepository::class)
 */
class Vaciones
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

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

    public function getAutorizacionVacaciones(): ?string
    {
        return $this->autorizacion_vacaciones;
    }

    public function setAutorizacionVacaciones(string $autorizacion_vacaciones): self
    {
        $this->autorizacion_vacaciones = $autorizacion_vacaciones;

        return $this;
    }

    public function getPeriodosAcumulados(): ?int
    {
        return $this->periodos_acumulados;
    }

    public function setPeriodosAcumulados(?int $periodos_acumulados): self
    {
        $this->periodos_acumulados = $periodos_acumulados;

        return $this;
    }

    public function getIdTipoVacaciones(): ?int
    {
        return $this->id_tipo_vacaciones;
    }

    public function setIdTipoVacaciones(int $id_tipo_vacaciones): self
    {
        $this->id_tipo_vacaciones = $id_tipo_vacaciones;

        return $this;
    }

    public function getFechaDesde(): ?\DateTimeInterface
    {
        return $this->fecha_desde;
    }

    public function setFechaDesde(\DateTimeInterface $fecha_desde): self
    {
        $this->fecha_desde = $fecha_desde;

        return $this;
    }

    public function getFechaHasta(): ?\DateTimeInterface
    {
        return $this->fecha_hasta;
    }

    public function setFechaHasta(\DateTimeInterface $fecha_hasta): self
    {
        $this->fecha_hasta = $fecha_hasta;

        return $this;
    }

    public function getFechaIncorporacion(): ?\DateTimeInterface
    {
        return $this->fecha_incorporacion;
    }

    public function setFechaIncorporacion(\DateTimeInterface $fecha_incorporacion): self
    {
        $this->fecha_incorporacion = $fecha_incorporacion;

        return $this;
    }

    public function getPeriodoDifrute(): ?string
    {
        return $this->periodo_difrute;
    }

    public function setPeriodoDifrute(string $periodo_difrute): self
    {
        $this->periodo_difrute = $periodo_difrute;

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
