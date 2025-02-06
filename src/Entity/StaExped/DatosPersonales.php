<?php

namespace App\Entity\StaExped;

use App\Repository\StaExped\DatosPersonalesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DatosPersonalesRepository::class)
 */
class DatosPersonales
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
    private $cedula;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_ingreso;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $familiar_empresa;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $autorizacion_ingreso;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idempresa;    

    public function __construct()
    {
    
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCedula(): ?int
    {
        return $this->cedula;
    }

    public function setCedula(int $cedula): self
    {
        $this->cedula = $cedula;

        return $this;
    }

    public function getFechaIngreso(): ?\DateTimeInterface
    {
        return $this->fecha_ingreso;
    }

    public function setFechaIngreso(\DateTimeInterface $fecha_ingreso): self
    {
        $this->fecha_ingreso = $fecha_ingreso;

        return $this;
    }

    public function getFamiliarEmpresa(): ?string
    {
        return $this->familiar_empresa;
    }

    public function setFamiliarEmpresa(string $familiar_empresa): self
    {
        $this->familiar_empresa = $familiar_empresa;

        return $this;
    }

    public function getAutorizacionIngreso(): ?string
    {
        return $this->autorizacion_ingreso;
    }

    public function setAutorizacionIngreso(string $autorizacion_ingreso): self
    {
        $this->autorizacion_ingreso = $autorizacion_ingreso;

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