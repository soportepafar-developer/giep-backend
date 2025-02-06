<?php

namespace App\Entity\StaExped;

use App\Repository\StaExped\EstudiosAcademicosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EstudiosAcademicosRepository::class)
 */
class EstudiosAcademicos
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
    private $iddatos_personales;

    /**
     * @ORM\Column(type="integer")
     */
    private $idprofesion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_graduado;

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

    public function setIdDatosPersonales(?int $iddatos_personales): self
    {
        $this->iddatos_personales = $iddatos_personales;

        return $this;
    }

    public function getIdProfesion(): ?int
    {
        return $this->idprofesion;
    }

    public function setIdProfesion(int $idprofesion): self
    {
        $this->idprofesion = $idprofesion;

        return $this;
    }

    public function getFechaGraduado(): ?\DateTimeInterface
    {
        return $this->fecha_graduado;
    }

    public function setFechaGraduado(\DateTimeInterface $fecha_graduado): self
    {
        $this->fecha_graduado = $fecha_graduado;

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
