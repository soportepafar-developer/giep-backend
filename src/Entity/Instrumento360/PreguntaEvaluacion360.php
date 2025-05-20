<?php

namespace App\Entity\Instrumento360;
use App\Entity\Instrumento360\TipoInputEvaluacion360;
use App\Entity\Proyecto\Empresa;
use App\Repository\Instrumento360\PreguntaEvaluacion360Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PreguntaEvaluacion360Repository::class)
 * @UniqueEntity(
    *     fields={"pregunta"},
    *     message="La pregunta ya existe"
 * )
 * @ORM\HasLifecycleCallbacks() 
 */

class PreguntaEvaluacion360
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $pregunta;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $orden;

    /**
     * @ORM\ManyToOne(targetEntity=TipoInputEvaluacion360::class, inversedBy="preguntas")
     */
    private $idInput;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $class;

    /**
     * @ORM\Column(type="smallint")
     */
    private $obligatorio;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $puntos;

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
     * @ORM\OneToMany(targetEntity=OpcionesEvaluacion360::class, mappedBy="idPregunta", orphanRemoval=true)
     */
    private $opciones;

    /**
     * @ORM\OneToMany(targetEntity=RespuestaEvaluacion360::class, mappedBy="idPregunta", orphanRemoval=true)
     */
    private $respuestas;

    /**
     * @ORM\ManyToOne(targetEntity=SeccionEvaluacion360::class, inversedBy="preguntas")
     */
    private $seccion;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    private $idempresa;

    public function __construct()
    {
        $this->opciones = new ArrayCollection();
        $this->respuestas = new ArrayCollection();
    }

    /**
    * @ORM\PrePersist
    */
    public function setCreatedAtValue()
    {
        $this->createAt = new \DateTime();
        
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPregunta(): ?string
    {
        return $this->pregunta;
    }

    public function setPregunta(string $pregunta): self
    {
        $this->pregunta = $pregunta;

        return $this;
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

    public function getIdInput(): ?TipoInput
    {
        return $this->idInput;
    }

    public function setIdInput(?TipoInput $idInput): self
    {
        $this->idInput = $idInput;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getObligatorio(): ?int
    {
        return $this->obligatorio;
    }

    public function setObligatorio(int $obligatorio): self
    {
        $this->obligatorio = $obligatorio;

        return $this;
    }

    public function getPuntos(): ?string
    {
        return $this->puntos;
    }

    public function setPuntos(?string $puntos): self
    {
        $this->puntos = $puntos;

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
     * @return Collection|OpcionesEvaluacion360[]
     */
    public function getOpciones(): Collection
    {
        return $this->opciones;
    }

    public function addOpcione(OpcionesEvaluacion360 $opcione): self
    {
        if (!$this->opciones->contains($opcione)) {
            $this->opciones[] = $opcione;
            $opcione->setIdPregunta($this);
        }

        return $this;
    }

    public function removeOpcione(OpcionesEvaluacion360 $opcione): self
    {
        if ($this->opciones->removeElement($opcione)) {
            // set the owning side to null (unless already changed)
            if ($opcione->getIdPregunta() === $this) {
                $opcione->setIdPregunta(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Respuesta360[]
     */
    public function getRespuestas(): Collection
    {
        return $this->respuestas;
    }

    public function addRespuesta(RespuestaEvaluacion360 $respuesta): self
    {
        if (!$this->respuestas->contains($respuesta)) {
            $this->respuestas[] = $respuesta;
            $respuesta->setIdPregunta($this);
        }

        return $this;
    }

    public function removeRespuesta(RespuestaEvaluacion360 $respuesta): self
    {
        if ($this->respuestas->removeElement($respuesta)) {
            // set the owning side to null (unless already changed)
            if ($respuesta->getIdPregunta() === $this) {
                $respuesta->setIdPregunta(null);
            }
        }

        return $this;
    }

    public function getSeccion(): ?SeccionEvaluacion
    {
        return $this->seccion;
    }

    public function setSeccion(?SeccionEvaluacion $seccion): self
    {
        $this->seccion = $seccion;

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
