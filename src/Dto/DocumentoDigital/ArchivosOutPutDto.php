<?php
namespace App\Dto\DocumentoDigital;

use App\Entity\DocumentoDigital\HistArchivoBloqueadod;
use App\Entity\DocumentoDigital\HistArchivoContVersiond;
use App\Entity\Proyecto\Empresa;
use App\Repository\DocumentoDigital\ArchivosdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArchivosdRepository::class)
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
     * @ORM\ManyToOne(targetEntity=TipoArchivod::class, inversedBy="idarchivos")
     * @ORM\JoinColumn(nullable=false)
     */
    public $id_tipo_archivo;

    /**
     * @ORM\Column(type="integer")
     */
    public $tamano;

    /**
     * @ORM\Column(type="string", length=3000)
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
     * @ORM\ManyToOne(targetEntity=TipoLimitedBloqueod::class, inversedBy="idarchivolimitedbloqueo")
     * @ORM\JoinColumn(nullable=false)
     */
    public $id_limited_bloqueo;

    /**
     * @ORM\Column(type="string", length=50)
     */
    public $nemotecnico;

      /**
     * @ORM\OneToMany(targetEntity=UsuarioArchivoBloqueadod::class, mappedBy="idarchivo")
     */
    public $idarchivobloqueado;

    /**
     * @ORM\OneToMany(targetEntity=HistArchivoBloqueadod::class, mappedBy="idarchivo")
     */
    public $idarchivohistbloqueo;

    /**
     * @ORM\OneToMany(targetEntity=HistArchivoContVersiond::class, mappedBy="idarchivo")
     */
    public $idarchivo_hist;

    /**
     * @ORM\ManyToOne(targetEntity=TipoEstadod::class, inversedBy="idestadoarchivos")
     * @ORM\JoinColumn(nullable=false)
     */
    public $idestado;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $fecha_actividad_registro;

    /**
     * @ORM\OneToMany(targetEntity=UsuarioArchivosd::class, mappedBy="iduserarchivos")
     */
    public $iduserarchivos;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    public $hashtag;

    /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    public $idempresa_id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    public $folios;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    public $num_dela_caja;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $fecha_extrema_inicio;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $fecha_extrema_fin;

    /**
     * @ORM\ManyToOne(targetEntity=ControlArchivoDigital::class)
     * @ORM\JoinColumn(nullable=false)
     */
    public $id_control_archivo_digital;

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

    public function getIdTipoArchivo(): ?TipoArchivod
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

    public function getIdLimitedBloqueo(): ?TipoLimitedBloqueod
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

    public function addIdarchivobloqueado(UsuarioArchivoBloqueadod $idarchivobloqueado): self
    {
        if (!$this->idarchivobloqueado->contains($idarchivobloqueado)) {
            $this->idarchivobloqueado[] = $idarchivobloqueado;
            $idarchivobloqueado->setIdarchivo($this);
        }

        return $this;
    }

    public function removeIdarchivobloqueado(UsuarioArchivoBloqueadod $idarchivobloqueado): self
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

    public function addIdarchivohistbloqueo(HistArchivoBloqueadod $idarchivohistbloqueo): self
    {
        if (!$this->idarchivohistbloqueo->contains($idarchivohistbloqueo)) {
            $this->idarchivohistbloqueo[] = $idarchivohistbloqueo;
            $idarchivohistbloqueo->setIdarchivo($this);
        }

        return $this;
    }

    public function removeIdarchivohistbloqueo(HistArchivoBloqueadod $idarchivohistbloqueo): self
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

    public function addIdarchivoHist(HistArchivoContVersiond $idarchivoHist): self
    {
        if (!$this->idarchivo_hist->contains($idarchivoHist)) {
            $this->idarchivo_hist[] = $idarchivoHist;
            $idarchivoHist->setIdarchivo($this);
        }

        return $this;
    }

    public function removeIdarchivoHist(HistArchivoContVersiond $idarchivoHist): self
    {
        if ($this->idarchivo_hist->removeElement($idarchivoHist)) {
            // set the owning side to null (unless already changed)
            if ($idarchivoHist->getIdarchivo() === $this) {
                $idarchivoHist->setIdarchivo(null);
            }
        }

        return $this;
    }

    public function getIdestado(): ?TipoEstadod
    {
        return $this->idestado;
    }

    public function getFechaActividadRegistro(): ?\DateTimeInterface
    {
        return $this->fecha_actividad_registro;
    }

    /**
     * @return Collection|UsuarioArchivos[]
     */
    public function getIduserarchivos(): Collection
    {
        return $this->iduserarchivos;
    }

    public function addIduserarchivo(UsuarioArchivosd $iduserarchivo): self
    {
        if (!$this->iduserarchivos->contains($iduserarchivo)) {
            $this->iduserarchivos[] = $iduserarchivo;
            $iduserarchivo->setIduserarchivos($this);
        }

        return $this;
    }

    public function removeIduserarchivo(UsuarioArchivosd $iduserarchivo): self
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

    public function getIdempresa(): ?int
    {
        return $this->idempresa_id;
    }

    public function getFolios(): ?string
    {
        return $this->folios;
    }

    public function getNumDelaCaja(): ?string
    {
        return $this->num_dela_caja;
    }

    public function getFechaExtremaInicio(): ?\DateTimeInterface
    {
        return $this->fecha_extrema_inicio;
    }

    public function getFechaExtremaFin(): ?\DateTimeInterface
    {
        return $this->fecha_extrema_fin;
    }

    public function getIdControlArchivoDigital(): ?ControlArchivoDigitald
    {
        return $this->id_control_archivo_digital;
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
}
