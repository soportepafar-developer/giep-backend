<?php

namespace App\Dto\Instrumento360;

use App\Entity\Encuesta\TipoUnidad;
use App\Entity\Proyecto\Empresa;
use App\Repository\Instrumento360\Instrumento360Repository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * @ORM\Entity(repositoryClass=Instrumento360Repository::class)
 */
class Instrumento360OutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="text")
     */
    public $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity=TipoUnidad::class)
     */
    public $tipounidad;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $fechaVigencia;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $fechaPublicacion;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $publicar;

    /**
     * @ORM\Column(type="integer")
     */
    public $duracion;

    /**
     * @ORM\ManyToOne(targetEntity=TipoInstrumento360::class)
     * @ORM\JoinColumn(nullable=false)
     */
    public $tipoInstrumento;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    public $empresa;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $createAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $createBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $updateAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $updateBy;


    /**
     * @ORM\OneToMany(targetEntity=SeccionEvaluacion360::class, mappedBy="instrumento360")
     */
    public $seccions;

     /**
     * @ORM\OneToMany(targetEntity=Instrumento360UsuariosAsignados::class, mappedBy="instrumento360")
     */
    public $instrumento360UsuariosAsignados;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $questionsByCategory;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $puntosGlobales;


    public function __construct()
    {
        $this->instrumento360UsuariosAsignados = new ArrayCollection();
    }

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

    public function getTipoInstrumento(): ?TipoInstrumento360
    {
        return $this->tipoInstrumento;
    }

    public function setTipoInstrumento(?TipoInstrumento360 $tipoInstrumento): self
    {
        $this->tipoInstrumento = $tipoInstrumento;

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
 
    public function getInstrumento360UsuariosAsignados(): Collection
    {
        return $this->instrumento360UsuariosAsignados;
    }
    
     public function getQuestionsByCategory(): ?int
    {
        return $this->questionsByCategory;
    }

    public function getPuntosGlobales(): ?int
    {
        return $this->puntosGlobales;
    }

}
