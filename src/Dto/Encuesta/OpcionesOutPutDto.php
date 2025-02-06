<?php

namespace App\Dto\Encuesta;

use App\Repository\Encuesta\OpcionesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
/**
 * @ORM\Entity(repositoryClass=OpcionesRepository::class)
 */
class OpcionesOutPutDto 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    public $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $valor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $puntos;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    public $correcta;

    /**
     * @ORM\ManyToOne(targetEntity=Pregunta::class, inversedBy="opciones")
     */
    public $idPregunta;

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
     * @ORM\Column(type="string", length=255)
     */
    public $updateBy;

    /**
     * @ORM\OneToMany(targetEntity=Respuesta::class, mappedBy="idOpcion")
     */
    public $respuestas;

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

    public function getIdPregunta(): ?Pregunta
    {
        return $this->idPregunta;
    }

    public function setIdPregunta(?Pregunta $idPregunta): self
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
     * @return Collection|Respuesta[]
     */
    public function getRespuestas(): Collection
    {
        return $this->respuestas;
    }

    public function addRespuesta(Respuesta $respuesta): self
    {
        if (!$this->respuestas->contains($respuesta)) {
            $this->respuestas[] = $respuesta;
            $respuesta->setIdOpcion($this);
        }

        return $this;
    }

    public function removeRespuesta(Respuesta $respuesta): self
    {
        if ($this->respuestas->removeElement($respuesta)) {
            // set the owning side to null (unless already changed)
            if ($respuesta->getIdOpcion() === $this) {
                $respuesta->setIdOpcion(null);
            }
        }

        return $this;
    }
}
