<?php

namespace App\Dto\StaExped;

use App\Repository\StaExped\DatosPersonalesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DatosPersonalesRepository::class)
 */
class DatosPersonalesOutPutDto
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
    public $cedula;


    /**
     * @ORM\Column(type="datetime")
     */
    public $fechaingreso;

    /**
     * @ORM\Column(type="string", length=2)
     */
    public $familiarempresa;

    /**
     * @ORM\Column(type="string", length=2)
     */
    public $autorizacioningreso;

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
     * @ORM\Column(type="string", length=255)
     */
    public $updateBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCedula(): ?int
    {
        return $this->cedula;
    }

    public function getFechaIngreso(): ?\DateTimeInterface
    {
        return $this->fechaingreso;
    }

    public function getFamiliarEmpresa(): ?string
    {
        return $this->familiarempresa;
    }

    public function getAutorizacionIngreso(): ?string
    {
        return $this->autorizacioningreso;
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
