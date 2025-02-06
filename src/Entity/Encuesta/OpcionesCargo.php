<?php

namespace App\Entity\Encuesta;

use App\Entity\Cargo;
use App\Entity\Proyecto\Empresa;
use App\Repository\Encuesta\OpcionesCargoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OpcionesCargoRepository::class)
 */
class OpcionesCargo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Opciones::class, inversedBy="opcionesCargos")
     */
    private $opcion;

    /**
     * @ORM\ManyToOne(targetEntity=Cargo::class, inversedBy="opcionesCargos1")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCargo;

    /**
     * @ORM\Column(type="float")
     */
    private $score;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $createBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idopcionescargo")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpcion(): ?Opciones
    {
        return $this->opcion;
    }

    public function setOpcion(?Opciones $opcion): self
    {
        $this->opcion = $opcion;

        return $this;
    }


    public function getIdCargo(): ?Cargo
    {
        return $this->idCargo;
    }

    public function setIdCargo(?Cargo $idCargo): self
    {
        $this->idCargo = $idCargo;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): self
    {
        $this->score = $score;

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

    public function setCreateBy(string $createBy): self
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
