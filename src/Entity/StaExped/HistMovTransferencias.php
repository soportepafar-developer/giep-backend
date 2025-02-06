<?php

namespace App\Entity\StaExped;

use App\Repository\StaExped\HistMovTransferenciasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistMovTransferenciasRepository::class)
 */
class HistMovTransferencias
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
     * @ORM\Column(type="datetime")
     */
    private $fecha_transferencia;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_departamento;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_area;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_region;

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

    public function getFechaTransferencia(): ?\DateTimeInterface
    {
        return $this->fecha_transferencia;
    }

    public function setFechaTransferencia(\DateTimeInterface $fecha_transferencia): self
    {
        $this->fecha_transferencia = $fecha_transferencia;

        return $this;
    }

    public function getIdDepartamento(): ?int
    {
        return $this->id_departamento;
    }

    public function setIdDepartamento(int $id_departamento): self
    {
        $this->id_departamento = $id_departamento;

        return $this;
    }

    public function getIdArea(): ?int
    {
        return $this->id_area;
    }

    public function setIdArea(?int $id_area): self
    {
        $this->id_area = $id_area;

        return $this;
    }

    public function getIdRegion(): ?int
    {
        return $this->id_region;
    }

    public function setIdRegion(int $id_region): self
    {
        $this->id_region = $id_region;

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
