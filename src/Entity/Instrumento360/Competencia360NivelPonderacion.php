<?php

namespace App\Entity\Instrumento360;

use App\Entity\Nivel;
use App\Entity\Instrumento360\NivelDominio;
use App\Entity\Proyecto\Empresa;
use App\Repository\Instrumento360\Competencia360NivelPonderacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=Competencia360NivelPonderacionRepository::class)
 */
class Competencia360NivelPonderacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Competencia360::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $competencia;

    /**
     * @ORM\ManyToOne(targetEntity=Nivel::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $nivel;

    /**
     * @ORM\Column(type="integer")
     */
    private $ponderacion;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idcategorianivelponderacion")
     */
    private $idempresa;

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


    public function getNivel(): ?Nivel
    {
        return $this->nivel;
    }

    public function setNivel(?Nivel $nivel): self
    {
        $this->nivel = $nivel;

        return $this;
    }

    public function getPonderacion(): ?int
    {
        return $this->ponderacion;
    }

    public function setPonderacion(int $ponderacion): self
    {
        $this->ponderacion = $ponderacion;

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

    public function getCompetencia(): ?Competencia360
    {
        return $this->competencia;
    }

    public function setCompetencia(?Competencia360 $competencia): self
    {
        $this->competencia = $competencia;

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
