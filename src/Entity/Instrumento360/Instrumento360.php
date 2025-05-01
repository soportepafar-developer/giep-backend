<?php

namespace App\Entity\Instrumento360;

use App\Entity\Encuesta\TipoUnidad;
use App\Entity\Proyecto\Empresa;
use App\Repository\Instrumento360\Instrumento360Repository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=Instrumento360Repository::class)
 */
class Instrumento360
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity=TipoUnidad::class)
     */
    private $tipounidad;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaVigencia;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaPublicacion;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $publicar;

    /**
     * @ORM\Column(type="integer")
     */
    private $duracion;

    /**
     * @ORM\ManyToOne(targetEntity=TipoInstrumento360::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ManyToOne;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    private $empresa;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $updateBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getTipounidad(): ?TipoUnidad
    {
        return $this->tipounidad;
    }

    public function setTipounidad(?TipoUnidad $tipounidad): self
    {
        $this->tipounidad = $tipounidad;

        return $this;
    }

    public function getFechaVigencia(): ?\DateTimeInterface
    {
        return $this->fechaVigencia;
    }

    public function setFechaVigencia(?\DateTimeInterface $fechaVigencia): self
    {
        $this->fechaVigencia = $fechaVigencia;

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fechaPublicacion;
    }

    public function setFechaPublicacion(?\DateTimeInterface $fechaPublicacion): self
    {
        $this->fechaPublicacion = $fechaPublicacion;

        return $this;
    }

    public function getPublicar(): ?bool
    {
        return $this->publicar;
    }

    public function setPublicar(?bool $publicar): self
    {
        $this->publicar = $publicar;

        return $this;
    }

    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    public function setDuracion(int $duracion): self
    {
        $this->duracion = $duracion;

        return $this;
    }

    public function getManyToOne(): ?TipoInstrumento360
    {
        return $this->ManyToOne;
    }

    public function setManyToOne(?TipoInstrumento360 $ManyToOne): self
    {
        $this->ManyToOne = $ManyToOne;

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

    public function setUpdateBy(?string $updateBy): self
    {
        $this->updateBy = $updateBy;

        return $this;
    }
}
