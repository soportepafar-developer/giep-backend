<?php

namespace App\Dto\Proyecto;

use App\Repository\Proyecto\SpringRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpringRepository::class)
 */
class SpringOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="datetime")
     */
    public $fechainicio;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $fechafin;

    /**
     * @ORM\Column(type="string")
     */
    public $nombre;

    /**
     * @ORM\ManyToOne(targetEntity=Proyecto::class)
     * @ORM\JoinColumn(nullable=false)
     */
    public $idproyecto;

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

    public function getFechainicio(): ?\DateTimeInterface
    {
        return $this->fechainicio;
    }

    public function setFechainicio(\DateTimeInterface $fechainicio): self
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    public function getFechafin(): ?\DateTimeInterface
    {
        return $this->fechafin;
    }

    public function setFechafin(?\DateTimeInterface $fechafin): self
    {
        $this->fechafin = $fechafin;

        return $this;
    }

    public function getNroiteracion(): ?int
    {
        return $this->nroiteracion;
    }

    public function setNroiteracion(int $nroiteracion): self
    {
        $this->nroiteracion = $nroiteracion;

        return $this;
    }

    public function getIdproyecto(): ?Proyecto
    {
        return $this->idproyecto;
    }

    public function setIdproyecto(?Proyecto $idproyecto): self
    {
        $this->idproyecto = $idproyecto;

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
