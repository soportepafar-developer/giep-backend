<?php

namespace App\Dto\StaExped;

use App\Repository\StaExped\PermisosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PermisosRepository::class)
 */
class PermisosOutPutDto
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
    private $autorizacion_permisos;

     /**
     * @ORM\Column(type="integer")
     */
    private $id_motivo_permiso;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_desde;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_hasta;

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

    public function setIdDatosPersonales(int $id_datos_personales): self
    {
        $this->id_datos_personales = $id_datos_personales;

        return $this;
    }

    public function getAutorizacionPermisos(): ?string
    {
        return $this->autorizacion_permisos;
    }

    public function setAutorizacionPermisos(string $autorizacion_permisos): self
    {
        $this->autorizacion_permisos = $autorizacion_permisos;

        return $this;
    }

    public function getIdMotivoPermiso(): ?int
    {
        return $this->id_motivo_permiso;
    }

    public function setIdMotivoPermiso(int $id_motivo_permiso): self
    {
        $this->id_motivo_permiso = $id_motivo_permiso;

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

}
