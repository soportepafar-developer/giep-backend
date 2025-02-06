<?php

namespace App\Entity\Encuesta;

use App\Entity\Proyecto\Empresa;
use App\Entity\Rol;
use App\Entity\Status;
use App\Repository\Encuesta\InstrumentoCapturaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InstrumentoCapturaRepository::class)
 * @ORM\HasLifecycleCallbacks() 
 */
class InstrumentoCaptura
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
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity=TipoUnidad::class)
     */
    private $idTipoUnidad;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unidad;

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
     * @ORM\ManyToMany(targetEntity=Rol::class, inversedBy="instrumentoCapturas")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity=Pregunta::class, mappedBy="idInstrumento")
     */
    private $preguntas;

    /**
     * @ORM\OneToMany(targetEntity=Seccion::class, mappedBy="instrumento")
     */
    private $seccions;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class)
     */
    private $statusId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaVigencia;

    /**
     * @ORM\OneToMany(targetEntity=InstrumentoUsuario::class, mappedBy="IdInstrumento", orphanRemoval=true)
     */
    private $instrumentoUsuarios;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaPublicacion;

    /**
     * @ORM\Column(type="integer")
     */
    private $publicar;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $questionsByCategory;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $orden;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $puntosGlobales;

     /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idinstrumentocaptura")
     */
    private $idempresa;


    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->preguntas = new ArrayCollection();
        $this->seccions = new ArrayCollection();
        $this->instrumentoUsuarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getIdTipoUnidad(): ?TipoUnidad
    {
        return $this->idTipoUnidad;
    }

    public function setIdTipoUnidad(?TipoUnidad $idTipoUnidad): self
    {
        $this->idTipoUnidad = $idTipoUnidad;

        return $this;
    }

    public function getUnidad(): ?string
    {
        return $this->unidad;
    }

    public function setUnidad(?string $unidad): self
    {
        $this->unidad = $unidad;

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
     * @return Collection|Rol[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Rol $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Rol $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }


    /**
     * @return Collection|Pregunta[]
     */
    public function getPreguntas(): Collection
    {
        return $this->preguntas;
    }

    public function addPregunta(Pregunta $pregunta): self
    {
        if (!$this->preguntas->contains($pregunta)) {
            $this->preguntas[] = $pregunta;
            $pregunta->setIdInstrumento($this);
        }

        return $this;
    }

    public function removePregunta(Pregunta $pregunta): self
    {
        if ($this->preguntas->removeElement($pregunta)) {
            // set the owning side to null (unless already changed)
            if ($pregunta->getIdInstrumento() === $this) {
                $pregunta->setIdInstrumento(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Seccion[]
     */
    public function getSeccions(): Collection
    {
        return $this->seccions;
    }

    public function addSeccion(Seccion $seccion): self
    {
        if (!$this->seccions->contains($seccion)) {
            $this->seccions[] = $seccion;
            $seccion->setInstrumento($this);
        }

        return $this;
    }

    public function removeSeccion(Seccion $seccion): self
    {
        if ($this->seccions->removeElement($seccion)) {
            // set the owning side to null (unless already changed)
            if ($seccion->getInstrumento() === $this) {
                $seccion->setInstrumento(null);
            }
        }

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getStatusId(): ?Status
    {
        return $this->statusId;
    }

    public function setStatusId(?Status $statusId): self
    {
        $this->statusId = $statusId;

        return $this;
    }

    public function getFechaVigencia(): ?\DateTimeInterface
    {
        return $this->fechaVigencia;
    }

    public function setFechaVigencia(?\DateTimeInterface $fechaVigencia): self
    {
        $this->fechaVigencia = $fechaVigencia;

        return $this;
    }

    /**
     * @return Collection|InstrumentoUsuario[]
     */
    public function getInstrumentoUsuarios(): Collection
    {
        return $this->instrumentoUsuarios;
    }

    public function addInstrumentoUsuario(InstrumentoUsuario $instrumentoUsuario): self
    {
        if (!$this->instrumentoUsuarios->contains($instrumentoUsuario)) {
            $this->instrumentoUsuarios[] = $instrumentoUsuario;
            $instrumentoUsuario->setIdInstrumento($this);
        }

        return $this;
    }

    public function removeInstrumentoUsuario(InstrumentoUsuario $instrumentoUsuario): self
    {
        if ($this->instrumentoUsuarios->removeElement($instrumentoUsuario)) {
            // set the owning side to null (unless already changed)
            if ($instrumentoUsuario->getIdInstrumento() === $this) {
                $instrumentoUsuario->setIdInstrumento(null);
            }
        }

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fechaPublicacion;
    }

    public function setFechaPublicacion(?\DateTimeInterface $fechaPublicacion): self
    {
        $this->fechaPublicacion = $fechaPublicacion;

        return $this;
    }

    public function getPublicar(): ?int
    {
        return $this->publicar;
    }

    public function setPublicar(int $publicar): self
    {
        $this->publicar = $publicar;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getQuestionsByCategory(): ?int
    {
        return $this->questionsByCategory;
    }

    public function setQuestionsByCategory(?int $questionsByCategory): self
    {
        $this->questionsByCategory = $questionsByCategory;

        return $this;
    }


    /**
    * @ORM\PrePersist
    */
    public function setCreatedAtValue()
    {
        $this->createAt = new \DateTime();
        
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(?int $orden): self
    {
        $this->orden = $orden;

        return $this;
    }

    public function getPuntosGlobales(): ?int
    {
        return $this->puntosGlobales;
    }

    public function setPuntosGlobales(?int $puntosGlobales): self
    {
        $this->puntosGlobales = $puntosGlobales;

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
