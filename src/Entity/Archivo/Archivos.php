<?php

namespace App\Entity\Archivo;

use App\Entity\Archivo\HistArchivoBloqueado;
use App\Entity\Archivo\HistArchivoContVersion;
use App\Entity\Proyecto\Empresa;
use App\Repository\Archivo\ArchivosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArchivosRepository::class)
 */
class Archivos
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
    private $titulo;

    /**
     * @ORM\ManyToOne(targetEntity=TipoArchivo::class, inversedBy="idarchivos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_tipo_archivo;

    /**
     * @ORM\Column(type="integer")
     */
    private $tamano;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre_original;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $url_alojamiento;

    /**
     * @ORM\Column(type="integer")
     */
    private $publico;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $descripcion_archivo;

    /**
     * @ORM\ManyToOne(targetEntity=TipoLimitedBloqueo::class, inversedBy="idarchivolimitedbloqueo")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_limited_bloqueo;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nemotecnico;

      /**
     * @ORM\OneToMany(targetEntity=UsuarioArchivoBloqueado::class, mappedBy="idarchivo")
     */
    private $idarchivobloqueado;

    /**
     * @ORM\OneToMany(targetEntity=HistArchivoBloqueado::class, mappedBy="idarchivo")
     */
    private $idarchivohistbloqueo;

    /**
     * @ORM\OneToMany(targetEntity=HistArchivoContVersion::class, mappedBy="idarchivo")
     */
    private $idarchivo_hist;

    /**
     * @ORM\ManyToOne(targetEntity=TipoEstado::class, inversedBy="idestadoarchivos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idestado;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_actividad_registro;

    /**
     * @ORM\OneToMany(targetEntity=UsuarioArchivos::class, mappedBy="iduserarchivos")
     */
    private $iduserarchivos;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $hashtag;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idarchivos")
     */
    private $idempresa;

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

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getIdTipoArchivo(): ?TipoArchivo
    {
        return $this->id_tipo_archivo;
    }

    public function setIdTipoArchivo(?TipoArchivo $id_tipo_archivo): self
    {
        $this->id_tipo_archivo = $id_tipo_archivo;

        return $this;
    }

    public function getTamano(): ?int
    {
        return $this->tamano;
    }

    public function setTamano(int $tamano): self
    {
        $this->tamano = $tamano;

        return $this;
    }

    public function getNombreOriginal(): ?string
    {
        return $this->nombre_original;
    }

    public function setNombreOriginal(string $nombre_original): self
    {
        $this->nombre_original = $nombre_original;

        return $this;
    }

    public function getUrlAlojamiento(): ?string
    {
        return $this->url_alojamiento;
    }

    public function setUrlAlojamiento(string $url_alojamiento): self
    {
        $this->url_alojamiento = $url_alojamiento;

        return $this;
    }

    public function getPublico(): ?int
    {
        return $this->publico;
    }

    public function setPublico(int $publico): self
    {
        $this->publico = $publico;

        return $this;
    }

    public function getDescripcionArchivo(): ?string
    {
        return $this->descripcion_archivo;
    }

    public function setDescripcionArchivo(string $descripcion_archivo): self
    {
        $this->descripcion_archivo = $descripcion_archivo;

        return $this;
    }

    public function getIdLimitedBloqueo(): ?TipoLimitedBloqueo
    {
        return $this->id_limited_bloqueo;
    }

    public function setIdLimitedBloqueo(?TipoLimitedBloqueo $id_limited_bloqueo): self
    {
        $this->id_limited_bloqueo = $id_limited_bloqueo;

        return $this;
    }

    public function getNemotecnico(): ?string
    {
        return $this->nemotecnico;
    }

    public function setNemotecnico(string $nemotecnico): self
    {
        $this->nemotecnico = $nemotecnico;

        return $this;
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

    public function setIdestado(?TipoEstado $idestado): self
    {
        $this->idestado = $idestado;

        return $this;
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
    * @ORM\PrePersist
    */
    public function setFechaActividadRegistro()
    {
        $this->fecha_actividad_registro = new \DateTime();
        
    }

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

    public function getHashtag(): ?string
    {
        return $this->hashtag;
    }

    public function setHashtag(?string $hashtag): self
    {
        $this->hashtag = $hashtag;

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
