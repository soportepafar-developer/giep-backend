<?php

namespace App\Entity\Archivo;

use App\Entity\Proyecto\Empresa;
use App\Entity\User;
use App\Entity\Archivo\Archivos;
use App\Repository\Archivo\HistArchivoBloqueadoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistArchivoBloqueadoRepository::class)
 */
class HistArchivoBloqueado
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="iduserhistbloqueo")
     * @ORM\JoinColumn(nullable=false)
     */
    private $iduser;

    /**
     * @ORM\ManyToOne(targetEntity=Archivos::class, inversedBy="idarchivohistbloqueo")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idarchivo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_bloqueo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_desbloqueo;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idhistarchivobloqueado")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdarchivo(): ?Archivos
    {
        return $this->idarchivo;
    }

    public function setIdarchivo(?Archivos $idarchivo): self
    {
        $this->idarchivo = $idarchivo;

        return $this;
    }

    public function getFechaBloqueo(): ?\DateTimeInterface
    {
        return $this->fecha_bloqueo;
    }

    /**
    * @ORM\PrePersist
    */
    public function setFechaBloqueo()
    {
        $this->fecha_bloqueo = new \DateTime();
        
    }

    public function getFechaDesbloqueo(): ?\DateTimeInterface
    {
        return $this->fecha_desbloqueo;
    }

    public function setFechaDesbloqueo(\DateTimeInterface $fecha_desbloqueo): self
    {
        $this->fecha_desbloqueo = $fecha_desbloqueo;

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
