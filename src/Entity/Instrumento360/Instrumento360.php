<?php

namespace App\Entity\Instrumento360;

use App\Entity\Encuesta\TipoUnidad;
use App\Entity\Proyecto\Empresa;
use App\Entity\Instrumento360\PreguntaEvaluacion360;
use App\Repository\Instrumento360\Instrumento360Repository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * @ORM\Entity(repositoryClass=Instrumento360Repository::class)
 */
class Instrumento360
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity=TipoUnidad::class)
     */
    private $tipounidad;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaVigencia;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaPublicacion;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $publicar;

    /**
     * @ORM\Column(type="integer")
     */
    private $duracion;

    /**
     * @ORM\ManyToOne(targetEntity=TipoInstrumento360::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipoInstrumento;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    private $empresa;

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
     * @ORM\OneToMany(targetEntity=SeccionEvaluacion360::class, mappedBy="instrumento360")
     */
    private $seccions;

     /**
     * @ORM\OneToMany(targetEntity=Instrumento360UsuariosAsignados::class, mappedBy="instrumento360")
     */
    private $instrumento360UsuariosAsignados;

    /**
     * @ORM\OneToMany(targetEntity=PreguntaEvaluacion360::class, mappedBy="idInstrumento")
     */
    private $preguntas;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $questionsByCategory;

      /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $puntosGlobales;


    public function __construct()
    {
        $this->instrumento360UsuariosAsignados = new ArrayCollection();
        $this->seccions = new ArrayCollection();
        $this->preguntas = new ArrayCollection();


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

    public function getTipounidad(): ?TipoUnidad
    {
        return $this->tipounidad;
    }

    public function setTipounidad(?TipoUnidad $tipounidad): self
    {
        $this->tipounidad = $tipounidad;

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

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fechaPublicacion;
    }

    public function setFechaPublicacion(?\DateTimeInterface $fechaPublicacion): self
    {
        $this->fechaPublicacion = $fechaPublicacion;

        return $this;
    }

    public function getPublicar(): ?bool
    {
        return $this->publicar;
    }

    public function setPublicar(?bool $publicar): self
    {
        $this->publicar = $publicar;

        return $this;
    }

    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    public function setDuracion(int $duracion): self
    {
        $this->duracion = $duracion;

        return $this;
    }

    public function getTipoInstrumento(): ?TipoInstrumento360
    {
        return $this->tipoInstrumento;
    }

    public function setTipoInstrumento(?TipoInstrumento360 $tipoInstrumento): self
    {
        $this->tipoInstrumento = $tipoInstrumento;

        return $this;
    }

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): self
    {
        $this->empresa = $empresa;

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
 
    public function getInstrumento360UsuariosAsignados(): Collection
    {
        return $this->instrumento360UsuariosAsignados;
    }

        /**
     * @return Collection|SeccionEvaluacion360[]
     */
    public function getSeccions(): Collection
    {
        return $this->seccions;
    }

    public function addSeccion(SeccionEvaluacion360 $seccion): self
    {
        if (!$this->seccions->contains($seccion)) {
            $this->seccions[] = $seccion;
            $seccion->setInstrumento($this);
        }

        return $this;
    }

    public function removeSeccion(SeccionEvaluacion360 $seccion): self
    {
        if ($this->seccions->removeElement($seccion)) {
            // set the owning side to null (unless already changed)
            if ($seccion->getInstrumento() === $this) {
                $seccion->setInstrumento(null);
            }
        }

        return $this;
    }


        /**
     * @return Collection|PreguntaEvaluacion360[]
     */
    public function getPreguntas(): Collection
    {
        return $this->preguntas;
    }

    public function addPregunta(PreguntaEvaluacion360 $pregunta): self
    {
        if (!$this->preguntas->contains($pregunta)) {
            $this->preguntas[] = $pregunta;
            $pregunta->setIdInstrumento($this);
        }

        return $this;
    }

    public function removePregunta(PreguntaEvaluacion360 $pregunta): self
    {
        if ($this->preguntas->removeElement($pregunta)) {
            // set the owning side to null (unless already changed)
            if ($pregunta->getIdInstrumento() === $this) {
                $pregunta->setIdInstrumento(null);
            }
        }

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

    public function getPuntosGlobales(): ?int
    {
        return $this->puntosGlobales;
    }

    public function setPuntosGlobales(?int $puntosGlobales): self
    {
        $this->puntosGlobales = $puntosGlobales;

        return $this;
    }

    

}
