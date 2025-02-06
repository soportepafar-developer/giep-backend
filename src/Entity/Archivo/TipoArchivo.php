<?php

namespace App\Entity\Archivo;

use App\Entity\Proyecto\Empresa;
use App\Repository\Archivo\TipoArchivoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoArchivoRepository::class)
 */
class TipoArchivo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre_archivo;

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
     * @ORM\OneToMany(targetEntity=ArchivosExtesiones::class, mappedBy="id_tipo_archivos")
     */
    private $tipo_archivo_extesiones;

    /**
     * @ORM\OneToMany(targetEntity=TamanoArchivoPermitido::class, mappedBy="id_status_tipo_archivo")
     */
    private $id_tipo_archivo_tamano;

    /**
     * @ORM\OneToMany(targetEntity=Archivos::class, mappedBy="id_tipo_archivo")
     */
    private $idarchivos;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idtipoarchivo")
     */
    private $idempresa;

    public function __construct()
    {
        $this->tipo_archivo_extesiones = new ArrayCollection();
        $this->id_tipo_archivo_tamano = new ArrayCollection();
        $this->idarchivos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreArchivo(): ?string
    {
        return $this->nombre_archivo;
    }

    public function setNombreArchivo(string $nombre_archivo): self
    {
        $this->nombre_archivo = $nombre_archivo;

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
     * @return Collection|ArchivosExtesiones[]
     */
    public function getTipoArchivoExtesiones(): Collection
    {
        return $this->tipo_archivo_extesiones;
    }

    public function addTipoArchivoExtesione(ArchivosExtesiones $tipoArchivoExtesione): self
    {
        if (!$this->tipo_archivo_extesiones->contains($tipoArchivoExtesione)) {
            $this->tipo_archivo_extesiones[] = $tipoArchivoExtesione;
            $tipoArchivoExtesione->setIdTipoArchivos($this);
        }

        return $this;
    }

    public function removeTipoArchivoExtesione(ArchivosExtesiones $tipoArchivoExtesione): self
    {
        if ($this->tipo_archivo_extesiones->removeElement($tipoArchivoExtesione)) {
            // set the owning side to null (unless already changed)
            if ($tipoArchivoExtesione->getIdTipoArchivos() === $this) {
                $tipoArchivoExtesione->setIdTipoArchivos(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TamanoArchivoPermitido[]
     */
    public function getIdTipoArchivoTamano(): Collection
    {
        return $this->id_tipo_archivo_tamano;
    }

    public function addIdTipoArchivoTamano(TamanoArchivoPermitido $idTipoArchivoTamano): self
    {
        if (!$this->id_tipo_archivo_tamano->contains($idTipoArchivoTamano)) {
            $this->id_tipo_archivo_tamano[] = $idTipoArchivoTamano;
            $idTipoArchivoTamano->setIdStatusTipoArchivo($this);
        }

        return $this;
    }

    public function removeIdTipoArchivoTamano(TamanoArchivoPermitido $idTipoArchivoTamano): self
    {
        if ($this->id_tipo_archivo_tamano->removeElement($idTipoArchivoTamano)) {
            // set the owning side to null (unless already changed)
            if ($idTipoArchivoTamano->getIdStatusTipoArchivo() === $this) {
                $idTipoArchivoTamano->setIdStatusTipoArchivo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Archivos[]
     */
    public function getIdarchivos(): Collection
    {
        return $this->idarchivos;
    }

    public function addIdarchivo(Archivos $idarchivo): self
    {
        if (!$this->idarchivos->contains($idarchivo)) {
            $this->idarchivos[] = $idarchivo;
            $idarchivo->setIdTipoArchivo($this);
        }

        return $this;
    }

    public function removeIdarchivo(Archivos $idarchivo): self
    {
        if ($this->idarchivos->removeElement($idarchivo)) {
            // set the owning side to null (unless already changed)
            if ($idarchivo->getIdTipoArchivo() === $this) {
                $idarchivo->setIdTipoArchivo(null);
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
