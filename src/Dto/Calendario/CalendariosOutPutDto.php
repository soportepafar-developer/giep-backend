<?php

namespace App\Dto\Calendario;

use App\Repository\Calendario\CalendariosRepository;
use App\Entity\Calendario\Diasnolaborables;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalendariosRepository::class)
 */
class CalendariosOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity=Diasnolaborables::class, inversedBy="idcalendarios")
     * @ORM\JoinColumn(nullable=false)
     */
    public $id_dias_nolaborables;

    /**
     * @ORM\Column(type="datetime")
     */
    public $fecha_desde;

    /**
     * @ORM\Column(type="datetime")
     */
    public $fecha_hasta;

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

    public function getIdDiasNolaborables(): ?Diasnolaborables
    {
        return $this->id_dias_nolaborables;
    }

    public function getFechaDesde(): ?\DateTimeInterface
    {
        return $this->fecha_desde;
    }

    public function getFechaHasta(): ?\DateTimeInterface
    {
        return $this->fecha_hasta;
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
