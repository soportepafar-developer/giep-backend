<?php

namespace App\Dto\Calendario;

use App\Repository\StatuscalendarioproyectoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatuscalendarioproyectoRepository::class)
 */
class StatuscalendarioproyectoOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    public $estado;

    /**
     * @ORM\Column(type="string", length=25)
     */
    public $color;

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

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function getColor(): ?string
    {
        return $this->color;
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
