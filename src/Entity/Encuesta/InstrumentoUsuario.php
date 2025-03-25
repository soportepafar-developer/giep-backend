<?php

namespace App\Entity\Encuesta;

use App\Entity\Estado;
use App\Entity\Pais;
use App\Entity\Proyecto\Empresa;
use App\Entity\User;
use App\Entity\Cargo;
use App\Repository\Encuesta\InstrumentoUsuarioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InstrumentoUsuarioRepository::class)
 */
class InstrumentoUsuario
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=InstrumentoCaptura::class, inversedBy="instrumentoUsuarios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdInstrumento;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="instrumentoUsuarios")
     */
    private $idUser;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $respondida;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaInicio;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaFin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaAsignacion;

    /**
     * @ORM\ManyToOne(targetEntity=Pais::class)
     */
    private $paisId;

    /**
     * @ORM\ManyToOne(targetEntity=Estado::class)
     */
    private $estadoId;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idinstrumentousuario")
     */
    private $idempresa;

    /**
     * @ORM\ManyToOne(targetEntity=Cargo::class)
     */
    private $idCargo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdInstrumento(): ?InstrumentoCaptura
    {
        return $this->IdInstrumento;
    }

    public function setIdInstrumento(?InstrumentoCaptura $IdInstrumento): self
    {
        $this->IdInstrumento = $IdInstrumento;

        return $this;
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

    public function getRespondida(): ?int
    {
        return $this->respondida;
    }

    public function setRespondida(?int $respondida): self
    {
        $this->respondida = $respondida;

        return $this;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(?\DateTimeInterface $fechaInicio): self
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(?\DateTimeInterface $fechaFin): self
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    public function getFechaAsignacion(): ?\DateTimeInterface
    {
        return $this->fechaAsignacion;
    }

    public function setFechaAsignacion(?\DateTimeInterface $fechaAsignacion): self
    {
        $this->fechaAsignacion = $fechaAsignacion;

        return $this;
    }

    public function getPaisId(): ?Pais
    {
        return $this->paisId;
    }

    public function setPaisId(?Pais $paisId): self
    {
        $this->paisId = $paisId;

        return $this;
    }

    public function getEstadoId(): ?Estado
    {
        return $this->estadoId;
    }

    public function setEstadoId(?Estado $estadoId): self
    {
        $this->estadoId = $estadoId;

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

    public function getIdCargo(): ?Cargo
    {
        return $this->idCargo;
    }

    public function setIdCargo(?Cargo $idCargo): self
    {
        $this->idCargo = $idCargo;
        return $this;
    }
}
