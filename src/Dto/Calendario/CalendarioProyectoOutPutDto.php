<?php

namespace App\Dto\Calendario;

use App\Entity\Proyecto\Proyecto;
use App\Repository\Calendario\CalendarioProyectoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalendarioProyectoRepository::class)
 */
class CalendarioProyectoOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity=Proyecto::class, inversedBy="idcalendarioproyecto")
     * @ORM\JoinColumn(nullable=false)
     */
    public $id_proyecto;

    /**
     * @ORM\Column(type="datetime")
     */
    public $fecha_inicio_nolaboral;

    /**
     * @ORM\Column(type="datetime")
     */
    public $fecha_fin_nolaboral;

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


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProyecto(): ?Proyecto
    {
        return $this->id_proyecto;
    }

    public function getFechaInicioNolaboral(): ?\DateTimeInterface
    {
        return $this->fecha_inicio_nolaboral;
    }

    public function getFechaFinNolaboral(): ?\DateTimeInterface
    {
        return $this->fecha_fin_nolaboral;
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
