<?php

namespace App\Entity\Proyecto;

use App\Entity\User;
use App\Repository\Proyecto\TrazaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrazaRepository::class)
 */
class Traza
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tipoEntidad;

    /**
     * @ORM\Column(type="integer")
     */
    private $idEntidad;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="string", length=4000, nullable=true)
     */
    private $sqlInstruction;

    /**
     * @ORM\ManyToOne(targetEntity=AccionTraza::class, inversedBy="trazas")
     */
    private $accion;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idtraza")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoEntidad(): ?string
    {
        return $this->tipoEntidad;
    }

    public function setTipoEntidad(string $tipoEntidad): self
    {
        $this->tipoEntidad = $tipoEntidad;

        return $this;
    }

    public function getIdEntidad(): ?int
    {
        return $this->idEntidad;
    }

    public function setIdEntidad(int $idEntidad): self
    {
        $this->idEntidad = $idEntidad;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getSqlInstruction(): ?string
    {
        return $this->sqlInstruction;
    }

    public function setSqlInstruction(?string $sqlInstruction): self
    {
        $this->sqlInstruction = $sqlInstruction;

        return $this;
    }

    public function getAccion(): ?AccionTraza
    {
        return $this->accion;
    }

    public function setAccion(?AccionTraza $accion): self
    {
        $this->accion = $accion;

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
