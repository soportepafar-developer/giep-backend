<?php

namespace App\Dto\Archivo;

use App\Repository\Archivo\TipoEstadoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoEstadoRepository::class)
 */
class TipoEstadoOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    public $nombre_status;

                /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $createAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $createBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $updateAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $updateBy;

    /**
     * @ORM\OneToMany(targetEntity=Archivos::class, mappedBy="idestado")
     */
    public $idestadoarchivos;

    
    public function __construct()
    {
        $this->idstatus_versionado_archivo = new ArrayCollection();
        $this->idestadoarchivos = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreStatus(): ?string
    {
        return $this->nombre_status;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function getCreateBy(): ?string
    {
        return $this->createBy;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }

    /**
     * @return Collection|Archivos[]
     */
    public function getIdestadoarchivos(): Collection
    {
        return $this->idestadoarchivos;
    }

    public function addIdestadoarchivo(Archivos $idestadoarchivo): self
    {
        if (!$this->idestadoarchivos->contains($idestadoarchivo)) {
            $this->idestadoarchivos[] = $idestadoarchivo;
            $idestadoarchivo->setIdestado($this);
        }

        return $this;
    }

    public function removeIdestadoarchivo(Archivos $idestadoarchivo): self
    {
        if ($this->idestadoarchivos->removeElement($idestadoarchivo)) {
            // set the owning side to null (unless already changed)
            if ($idestadoarchivo->getIdestado() === $this) {
                $idestadoarchivo->setIdestado(null);
            }
        }

        return $this;
    }

    
}
