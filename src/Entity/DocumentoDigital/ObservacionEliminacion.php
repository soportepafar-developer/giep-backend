<?php

namespace App\Entity\DocumentoDigital;

use App\Repository\DocumentoDigital\ObservacionEliminacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ObservacionEliminacionRepository::class)
 */
class ObservacionEliminacion
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
    private $idusers_elimn;

    /**
     * @ORM\ManyToOne(targetEntity=TipoDescripcionError::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_descripcionerror;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $observacion;

    /**
     * @ORM\ManyToOne(targetEntity=Archivosd::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $idarchivo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_elimina;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdusersElimn(): ?int
    {
        return $this->idusers_elimn;
    }

    public function setIdusersElimn(int $idusers_elimn): self
    {
        $this->idusers_elimn = $idusers_elimn;

        return $this;
    }

    public function getIdDescripcionerror(): ?TipoDescripcionError
    {
        return $this->id_descripcionerror;
    }

    public function setIdDescripcionerror(?TipoDescripcionError $id_descripcionerror): self
    {
        $this->id_descripcionerror = $id_descripcionerror;

        return $this;
    }

    public function getObservacion(): ?string
    {
        return $this->observacion;
    }

    public function setObservacion(?string $observacion): self
    {
        $this->observacion = $observacion;

        return $this;
    }

    public function getIdarchivo(): ?Archivosd
    {
        return $this->idarchivo;
    }

    public function setIdarchivo(?Archivosd $idarchivo): self
    {
        $this->idarchivo = $idarchivo;

        return $this;
    }

    public function getFechaElimina(): ?\DateTimeInterface
    {
        return $this->fecha_elimina;
    }

    public function setFechaElimina(\DateTimeInterface $fecha_elimina): self
    {
        $this->fecha_elimina = $fecha_elimina;

        return $this;
    }
}
