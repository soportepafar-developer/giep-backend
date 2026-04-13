<?php

namespace App\Entity\DocumentoDigital;
use App\Repository\DocumentoDigital\TipoArchivoRepository;
use App\Entity\Proyecto\Empresa;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoArchivodRepository::class)
 */
class TipoArchivod
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
     * @ORM\OneToMany(targetEntity=ArchivosExtesionesd::class, mappedBy="id_tipo_archivos")
     */
    private $tipo_archivo_extesiones;

    /**
     * @ORM\OneToMany(targetEntity=TamanoArchivoPermitidod::class, mappedBy="id_status_tipo_archivo")
     */
    private $id_tipo_archivo_tamano;

    /**
     * @ORM\OneToMany(targetEntity=Archivosd::class, mappedBy="id_tipo_archivo")
     */
    private $idarchivos;

     /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $idempresa_id;

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
     * @return Collection|ArchivosExtesionesd[]
     */
    public function getTipoArchivoExtesiones(): Collection
    {
        return $this->tipo_archivo_extesiones;
    }

    public function addTipoArchivoExtesione(ArchivosExtesionesd $tipoArchivoExtesione): self
    {
        if (!$this->tipo_archivo_extesiones->contains($tipoArchivoExtesione)) {
            $this->tipo_archivo_extesiones[] = $tipoArchivoExtesione;
            $tipoArchivoExtesione->setIdTipoArchivos($this);
        }

        return $this;
    }

    public function removeTipoArchivoExtesione(ArchivosExtesionesd $tipoArchivoExtesione): self
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
     * @return Collection|TamanoArchivoPermitidod[]
     */
    public function getIdTipoArchivoTamano(): Collection
    {
        return $this->id_tipo_archivo_tamano;
    }

    public function addIdTipoArchivoTamano(TamanoArchivoPermitidod $idTipoArchivoTamano): self
    {
        if (!$this->id_tipo_archivo_tamano->contains($idTipoArchivoTamano)) {
            $this->id_tipo_archivo_tamano[] = $idTipoArchivoTamano;
            $idTipoArchivoTamano->setIdStatusTipoArchivo($this);
        }

        return $this;
    }

    public function removeIdTipoArchivoTamano(TamanoArchivoPermitidod $idTipoArchivoTamano): self
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

    public function addIdarchivo(Archivosd $idarchivo): self
    {
        if (!$this->idarchivos->contains($idarchivo)) {
            $this->idarchivos[] = $idarchivo;
            $idarchivo->setIdTipoArchivo($this);
        }

        return $this;
    }

    public function removeIdarchivo(Archivosd $idarchivo): self
    {
        if ($this->idarchivos->removeElement($idarchivo)) {
            // set the owning side to null (unless already changed)
            if ($idarchivo->getIdTipoArchivo() === $this) {
                $idarchivo->setIdTipoArchivo(null);
            }
        }

        return $this;
    }

    public function getIdempresa(): ?int
    {
        return $this->idempresa_id;
    }

    public function setIdempresa(int $idempresa_id): self
    {
        $this->idempresa_id = $idempresa_id;

        return $this;
    }
}
