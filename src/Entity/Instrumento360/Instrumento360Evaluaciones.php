<?php

namespace App\Entity\Instrumento360;

use App\Entity\Proyecto\Empresa;
use App\Repository\Instrumento360\Instrumento360EvaluacionesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=Instrumento360EvaluacionesRepository::class)
 */
class Instrumento360Evaluaciones
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Instrumento360UsuariosAsignados::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdInstrumentoUsuario;

    /**
     * @ORM\ManyToOne(targetEntity=Competencia360::class, inversedBy="nivelDominio")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Competencia360;

    /**
     * @ORM\ManyToOne(targetEntity=NivelDominio::class, inversedBy="instrumento360Evaluaciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nivelDominio;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $empresa;

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

    public function getIdInstrumentoUsuario(): ?Instrumento360UsuariosAsignados
    {
        return $this->IdInstrumentoUsuario;
    }

    public function setIdInstrumentoUsuario(?Instrumento360UsuariosAsignados $IdInstrumentoUsuario): self
    {
        $this->IdInstrumentoUsuario = $IdInstrumentoUsuario;

        return $this;
    }

    public function getCompetencia360(): ?Competencia360
    {
        return $this->Competencia360;
    }

    public function setCompetencia360(?Competencia360 $Competencia360): self
    {
        $this->Competencia360 = $Competencia360;

        return $this;
    }

    public function getNivelDominio(): ?NivelDominio
    {
        return $this->nivelDominio;
    }

    public function setNivelDominio(?NivelDominio $nivelDominio): self
    {
        $this->nivelDominio = $nivelDominio;

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
}
