<?php

namespace App\Entity\Archivo;

use App\Entity\Proyecto\Empresa;
use App\Repository\Archivo\TipoEstadoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoEstadoRepository::class)
 */
class TipoEstado
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $nombre_status;

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
     * @ORM\OneToMany(targetEntity=Archivos::class, mappedBy="idestado")
     */
    private $idestadoarchivos;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idtipoestado")
     */
    private $idempresa;

    
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

    public function setNombreStatus(string $nombre_status): self
    {
        $this->nombre_status = $nombre_status;

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
