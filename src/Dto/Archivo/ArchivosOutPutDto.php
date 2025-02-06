<?php

namespace App\Dto\Archivo;

use App\Entity\Archivo\HistArchivoBloqueado;
use App\Entity\Archivo\HistArchivoContVersion;
use App\Repository\Archivo\ArchivosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArchivosRepository::class)
 */
class ArchivosOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $titulo;

    /**
     * @ORM\ManyToOne(targetEntity=TipoArchivo::class, inversedBy="idarchivos")
     * @ORM\JoinColumn(nullable=false)
     */
    public $id_tipo_archivo;

    /**
     * @ORM\Column(type="integer")
     */
    public $tamano;

    /**
     * @ORM\Column(type="string", length=50)
     */
    public $nombre_original;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    public $url_alojamiento;

    /**
     * @ORM\Column(type="integer")
     */
    public $publico;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    public $descripcion_archivo;

    /**
     * @ORM\ManyToOne(targetEntity=TipoLimitedBloqueo::class, inversedBy="idarchivolimitedbloqueo")
     * @ORM\JoinColumn(nullable=false)
     */
    public $id_limited_bloqueo;

    /**
     * @ORM\Column(type="string", length=50)
     */
    public $nemotecnico;

      /**
     * @ORM\OneToMany(targetEntity=UsuarioArchivoBloqueado::class, mappedBy="idarchivo")
     */
    public $idarchivobloqueado;

    /**
     * @ORM\OneToMany(targetEntity=HistArchivoBloqueado::class, mappedBy="idarchivo")
     */
    public $idarchivohistbloqueo;

    /**
     * @ORM\OneToMany(targetEntity=HistArchivoContVersion::class, mappedBy="idarchivo")
     */
    public $idarchivo_hist;

    /**
     * @ORM\ManyToOne(targetEntity=TipoEstado::class, inversedBy="idestadoarchivos")
     * @ORM\JoinColumn(nullable=false)
     */
    public $idestado;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $fecha_actividad_registro;

    /**
     * @ORM\OneToMany(targetEntity=UsuarioArchivos::class, mappedBy="iduserarchivos")
     */
    public $iduserarchivos;

    public function __construct()
    {
        $this->idarchivobloqueado = new ArrayCollection();
        $this->idarchivohistbloqueo = new ArrayCollection();
        $this->idarchivo_hist = new ArrayCollection();
        $this->iduserarchivos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function getIdTipoArchivo(): ?TipoArchivo
    {
        return $this->id_tipo_archivo;
    }

    public function getTamano(): ?int
    {
        return $this->tamano;
    }

    public function getNombreOriginal(): ?string
    {
        return $this->nombre_original;
    }

    public function getUrlAlojamiento(): ?string
    {
        return $this->url_alojamiento;
    }

    public function getPublico(): ?int
    {
        return $this->publico;
    }

    public function getDescripcionArchivo(): ?string
    {
        return $this->descripcion_archivo;
    }

    public function getIdLimitedBloqueo(): ?TipoLimitedBloqueo
    {
        return $this->id_limited_bloqueo;
    }

    public function getNemotecnico(): ?string
    {
        return $this->nemotecnico;
    }

    /**
     * @return Collection|UsuarioArchivoBloqueado[]
     */
    public function getIdarchivobloqueado(): Collection
    {
        return $this->idarchivobloqueado;
    }

    public function addIdarchivobloqueado(UsuarioArchivoBloqueado $idarchivobloqueado): self
    {
        if (!$this->idarchivobloqueado->contains($idarchivobloqueado)) {
            $this->idarchivobloqueado[] = $idarchivobloqueado;
            $idarchivobloqueado->setIdarchivo($this);
        }

        return $this;
    }

    public function removeIdarchivobloqueado(UsuarioArchivoBloqueado $idarchivobloqueado): self
    {
        if ($this->idarchivobloqueado->removeElement($idarchivobloqueado)) {
            // set the owning side to null (unless already changed)
            if ($idarchivobloqueado->getIdarchivo() === $this) {
                $idarchivobloqueado->setIdarchivo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HistArchivoBloqueado[]
     */
    public function getIdarchivohistbloqueo(): Collection
    {
        return $this->idarchivohistbloqueo;
    }

    public function addIdarchivohistbloqueo(HistArchivoBloqueado $idarchivohistbloqueo): self
    {
        if (!$this->idarchivohistbloqueo->contains($idarchivohistbloqueo)) {
            $this->idarchivohistbloqueo[] = $idarchivohistbloqueo;
            $idarchivohistbloqueo->setIdarchivo($this);
        }

        return $this;
    }

    public function removeIdarchivohistbloqueo(HistArchivoBloqueado $idarchivohistbloqueo): self
    {
        if ($this->idarchivohistbloqueo->removeElement($idarchivohistbloqueo)) {
            // set the owning side to null (unless already changed)
            if ($idarchivohistbloqueo->getIdarchivo() === $this) {
                $idarchivohistbloqueo->setIdarchivo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HistArchivoContVersion[]
     */
    public function getIdarchivoHist(): Collection
    {
        return $this->idarchivo_hist;
    }

    public function addIdarchivoHist(HistArchivoContVersion $idarchivoHist): self
    {
        if (!$this->idarchivo_hist->contains($idarchivoHist)) {
            $this->idarchivo_hist[] = $idarchivoHist;
            $idarchivoHist->setIdarchivo($this);
        }

        return $this;
    }

    public function removeIdarchivoHist(HistArchivoContVersion $idarchivoHist): self
    {
        if ($this->idarchivo_hist->removeElement($idarchivoHist)) {
            // set the owning side to null (unless already changed)
            if ($idarchivoHist->getIdarchivo() === $this) {
                $idarchivoHist->setIdarchivo(null);
            }
        }

        return $this;
    }

    public function getIdestado(): ?TipoEstado
    {
        return $this->idestado;
    }

    public function getFechaActividadRegistro(): ?\DateTimeInterface
    {
        return $this->fecha_actividad_registro;
    }

    /**
    * @ORM\PrePersist
    */
    /* public function setFechaActividadRegistro(?\DateTimeInterface $fecha_actividad_registro): self
    {
        //$this->fecha_actividad_registro = $fecha_actividad_registro;
        //$this->createAt = new \DateTime();
        $this->fecha_actividad_registro = new \DateTime();

        return $this;
    } */

    /**
     * @return Collection|UsuarioArchivos[]
     */
    public function getIduserarchivos(): Collection
    {
        return $this->iduserarchivos;
    }

    public function addIduserarchivo(UsuarioArchivos $iduserarchivo): self
    {
        if (!$this->iduserarchivos->contains($iduserarchivo)) {
            $this->iduserarchivos[] = $iduserarchivo;
            $iduserarchivo->setIduserarchivos($this);
        }

        return $this;
    }

    public function removeIduserarchivo(UsuarioArchivos $iduserarchivo): self
    {
        if ($this->iduserarchivos->removeElement($iduserarchivo)) {
            // set the owning side to null (unless already changed)
            if ($iduserarchivo->getIduserarchivos() === $this) {
                $iduserarchivo->setIduserarchivos(null);
            }
        }

        return $this;
    }

}
