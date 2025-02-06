<?php

namespace App\Entity\StaExped;

use App\Repository\StaExped\ReposoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReposoRepository::class)
 */
class Reposo
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
     * @ORM\Column(type="integer")
     */
    private $id_tiporeposo;

     /**
     * @ORM\Column(type="integer")
     */
    private $id_motivo_reposo;

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

    public function getIdTiporeposo(): ?int
    {
        return $this->id_tiporeposo;
    }

    public function setIdTiporeposo(int $id_tiporeposo): self
    {
        $this->id_tiporeposo = $id_tiporeposo;

        return $this;
    }

    public function getIdMotivoReposo(): ?int
    {
        return $this->id_motivo_reposo;
    }

    public function setIdMotivoReposo(int $id_motivo_reposo): self
    {
        $this->id_motivo_reposo = $id_motivo_reposo;

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
