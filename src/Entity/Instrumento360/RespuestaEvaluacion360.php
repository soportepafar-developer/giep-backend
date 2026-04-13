<?php

namespace App\Entity\Instrumento360;

use App\Entity\Proyecto\Empresa;
use App\Entity\User;
use App\Repository\Instrumento360\RespuestaEvaluacion360Repository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RespuestaEvaluacion360Repository::class)
 */
class RespuestaEvaluacion360
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $idUser;

    /**
     * @ORM\ManyToOne(targetEntity=PreguntaEvaluacion360::class, inversedBy="respuestas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idPregunta;

    /**
     * @ORM\ManyToOne(targetEntity=OpcionesEvaluacion360::class, inversedBy="respuestas")
     */
    private $idOpcion;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $entradaTexto;

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
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdPregunta(): ?PreguntaEvaluacion360
    {
        return $this->idPregunta;
    }

    public function setIdPregunta(?PreguntaEvaluacion360 $idPregunta): self
    {
        $this->idPregunta = $idPregunta;

        return $this;
    }

    public function getIdOpcion(): ?OpcionesEvaluacion360
    {
        return $this->idOpcion;
    }

    public function setIdOpcion(?OpcionesEvaluacion360 $idOpcion): self
    {
        $this->idOpcion = $idOpcion;

        return $this;
    }

    public function getEntradaTexto(): ?string
    {
        return $this->entradaTexto;
    }

    public function setEntradaTexto(?string $entradaTexto): self
    {
        $this->entradaTexto = $entradaTexto;

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
