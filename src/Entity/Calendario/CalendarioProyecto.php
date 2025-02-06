<?php

namespace App\Entity\Calendario;

use App\Entity\Proyecto\Empresa;
use App\Entity\Proyecto\Proyecto;
use App\Repository\Calendario\CalendarioProyectoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalendarioProyectoRepository::class)
 */
class CalendarioProyecto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Proyecto::class, inversedBy="idcalendarioproyecto")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_proyecto;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_inicio_nolaboral;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_fin_nolaboral;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $swactivo;

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

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idcalendarioproyecto")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProyecto(): ?Proyecto
    {
        return $this->id_proyecto;
    }

    public function setIdProyecto(?Proyecto $id_proyecto): self
    {
        $this->id_proyecto = $id_proyecto;

        return $this;
    }

    public function getFechaInicioNolaboral(): ?\DateTimeInterface
    {
        return $this->fecha_inicio_nolaboral;
    }

    public function setFechaInicioNolaboral(\DateTimeInterface $fecha_inicio_nolaboral): self
    {
        $this->fecha_inicio_nolaboral = $fecha_inicio_nolaboral;

        return $this;
    }

    public function getFechaFinNolaboral(): ?\DateTimeInterface
    {
        return $this->fecha_fin_nolaboral;
    }

    public function setFechaFinNolaboral(\DateTimeInterface $fecha_fin_nolaboral): self
    {
        $this->fecha_fin_nolaboral = $fecha_fin_nolaboral;

        return $this;
    }
    
    public function getSwactivo(): ?int
    {
        return $this->swactivo;
    }

    public function setSwactivo(int $swactivo): self
    {
        $this->swactivo = $swactivo;

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
