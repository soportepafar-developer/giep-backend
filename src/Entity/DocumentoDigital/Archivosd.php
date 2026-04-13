<?php
namespace App\Entity\DocumentoDigital;

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
class Archivosd
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
     * @ORM\ManyToOne(targetEntity=TipoArchivod::class, inversedBy="idarchivos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_tipo_archivo;

    /**
     * @ORM\Column(type="integer")
     */
    private $tamano;

    /**
     * @ORM\Column(type="string", length=3000)
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
     * @ORM\ManyToOne(targetEntity=TipoLimitedBloqueod::class, inversedBy="idarchivolimitedbloqueo")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_limited_bloqueo;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nemotecnico;

      /**
     * @ORM\OneToMany(targetEntity=UsuarioArchivoBloqueadod::class, mappedBy="idarchivo")
     */
    private $idarchivobloqueado;

    /**
     * @ORM\OneToMany(targetEntity=HistArchivoBloqueadod::class, mappedBy="idarchivo")
     */
    private $idarchivohistbloqueo;

    /**
     * @ORM\OneToMany(targetEntity=HistArchivoContVersiond::class, mappedBy="idarchivo")
     */
    private $idarchivo_hist;

    /**
     * @ORM\ManyToOne(targetEntity=TipoEstadod::class, inversedBy="idestadoarchivos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idestado;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_actividad_registro;

    /**
     * @ORM\OneToMany(targetEntity=UsuarioArchivosd::class, mappedBy="iduserarchivos")
     */
    private $iduserarchivos;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $hashtag;

    /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $idempresa_id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $folios;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $num_dela_caja;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_extrema_inicio;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_extrema_fin;

    /**
     * @ORM\ManyToOne(targetEntity=ControlArchivoDigital::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_control_archivo_digital;

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

    public function getIdTipoArchivo(): ?TipoArchivod
    {
        return $this->id_tipo_archivo;
    }

    public function setIdTipoArchivo(?TipoArchivod $id_tipo_archivo): self
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

    public function getIdLimitedBloqueo(): ?TipoLimitedBloqueod
    {
        return $this->id_limited_bloqueo;
    }

    public function setIdLimitedBloqueo(?TipoLimitedBloqueod $id_limited_bloqueo): self
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
     * @return Collection|UsuarioArchivoBloqueadod[]
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
     * @return Collection|HistArchivoBloqueadod[]
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
     * @return Collection|HistArchivoContVersiond[]
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

    public function setIdestado(?TipoEstadod $idestado): self
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
     * @return Collection|UsuarioArchivosd[]
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

    public function setHashtag(?string $hashtag): self
    {
        $this->hashtag = $hashtag;

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

    public function getFolios(): ?string
    {
        return $this->folios;
    }

    public function setFolios(string $folios): self
    {
        $this->folios = $folios;

        return $this;
    }

    public function getNumDelaCaja(): ?string
    {
        return $this->num_dela_caja;
    }

    public function setNumDelaCaja(string $num_dela_caja): self
    {
        $this->num_dela_caja = $num_dela_caja;

        return $this;
    }

    public function getFechaExtremaInicio(): ?\DateTimeInterface
    {
        return $this->fecha_extrema_inicio;
    }

    public function setFechaExtremaInicio(?\DateTimeInterface $fecha_extrema_inicio): self
    {
        $this->fecha_extrema_inicio = $fecha_extrema_inicio;

        return $this;
    }

    public function getFechaExtremaFin(): ?\DateTimeInterface
    {
        return $this->fecha_extrema_fin;
    }

    public function setFechaExtremaFin(?\DateTimeInterface $fecha_extrema_fin): self
    {
        $this->fecha_extrema_fin = $fecha_extrema_fin;

        return $this;
    }

    public function getIdControlArchivoDigital(): ?ControlArchivoDigital
    {
        return $this->id_control_archivo_digital;
    }

    public function setIdControlArchivoDigital(?ControlArchivoDigital $id_control_archivo_digital): self
    {
        $this->id_control_archivo_digital = $id_control_archivo_digital;

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
}
