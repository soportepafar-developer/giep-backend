<?php

namespace App\Entity\Evaluacion;

use App\Entity\Proyecto\Empresa;
use App\Entity\Status;
use App\Repository\Evaluacion\TipoInputEvaluacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoInputEvaluacionRepository::class)
 */
class TipoInputEvaluacion
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
     * @ORM\Column(type="smallint")
     */
    private $seleccionMultiple;

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
     * @ORM\OneToMany(targetEntity=PreguntaEvaluacion::class, mappedBy="idInput")
     */
    private $preguntas;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class)
     */
    private $estatus;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    private $idempresa;

    public function __construct()
    {
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

    public function getSeleccionMultiple(): ?int
    {
        return $this->seleccionMultiple;
    }

    public function setSeleccionMultiple(int $seleccionMultiple): self
    {
        $this->seleccionMultiple = $seleccionMultiple;

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
     * @return Collection|PreguntaEvaluacion[]
     */
    public function getPreguntas(): Collection
    {
        return $this->preguntas;
    }

    public function addPregunta(PreguntaEvaluacion $pregunta): self
    {
        if (!$this->preguntas->contains($pregunta)) {
            $this->preguntas[] = $pregunta;
            $pregunta->setIdInput($this);
        }

        return $this;
    }

    public function removePregunta(PreguntaEvaluacion $pregunta): self
    {
        if ($this->preguntas->removeElement($pregunta)) {
            // set the owning side to null (unless already changed)
            if ($pregunta->getIdInput() === $this) {
                $pregunta->setIdInput(null);
            }
        }

        return $this;
    }

    public function getEstatus(): ?Status
    {
        return $this->estatus;
    }

    public function setEstatus(?Status $estatus): self
    {
        $this->estatus = $estatus;

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
