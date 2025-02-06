<?php

namespace App\Entity\Evaluacion;

use App\Entity\Proyecto\Empresa;
use App\Repository\Evaluacion\OpcionesEvaluacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OpcionesEvaluacionRepository::class)
 */
class OpcionesEvaluacion
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
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $valor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $puntos;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $correcta;

    /**
     * @ORM\ManyToOne(targetEntity=PreguntaEvaluacion::class, inversedBy="opciones")
     */
    private $idPregunta;

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
     * @ORM\Column(type="string", length=255)
     */
    private $updateBy;

    /**
     * @ORM\OneToMany(targetEntity=RespuestaEvaluacion::class, mappedBy="idOpcion")
     */
    private $respuestas;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    private $idempresa;

    public function __construct()
    {
        $this->respuestas = new ArrayCollection();
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

    public function getValor(): ?string
    {
        return $this->valor;
    }

    public function setValor(?string $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getPuntos(): ?int
    {
        return $this->puntos;
    }

    public function setPuntos(?int $puntos): self
    {
        $this->puntos = $puntos;

        return $this;
    }

    public function getCorrecta(): ?int
    {
        return $this->correcta;
    }

    public function setCorrecta(?int $correcta): self
    {
        $this->correcta = $correcta;

        return $this;
    }

    public function getIdPregunta(): ?PreguntaEvaluacion
    {
        return $this->idPregunta;
    }

    public function setIdPregunta(?PreguntaEvaluacion $idPregunta): self
    {
        $this->idPregunta = $idPregunta;

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

    public function setUpdateBy(string $updateBy): self
    {
        $this->updateBy = $updateBy;

        return $this;
    }

    /**
     * @return Collection|RespuestaEvaluacion[]
     */
    public function getRespuestas(): Collection
    {
        return $this->respuestas;
    }

    public function addRespuesta(RespuestaEvaluacion $respuesta): self
    {
        if (!$this->respuestas->contains($respuesta)) {
            $this->respuestas[] = $respuesta;
            $respuesta->setIdOpcion($this);
        }

        return $this;
    }

    public function removeRespuesta(RespuestaEvaluacion $respuesta): self
    {
        if ($this->respuestas->removeElement($respuesta)) {
            // set the owning side to null (unless already changed)
            if ($respuesta->getIdOpcion() === $this) {
                $respuesta->setIdOpcion(null);
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