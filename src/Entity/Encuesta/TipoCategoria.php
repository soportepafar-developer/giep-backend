<?php

namespace App\Entity\Encuesta;

use App\Entity\Proyecto\Empresa;
use App\Entity\Status;
use App\Repository\Encuesta\TipoCategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoCategoriaRepository::class)
 */
class TipoCategoria
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
     * @ORM\OneToMany(targetEntity=Pregunta::class, mappedBy="IdCategoria")
     */
    private $preguntas;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class)
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $escalaPonderacion;


    /**
     * @ORM\OneToMany(targetEntity=CategoriaCargoEscala::class, mappedBy="categoria")
     */
    private $categoriasCargoEscalas;

       /**
     * @ORM\OneToMany(targetEntity=CategoriaNivelPonderacion::class, mappedBy="categoria")
     */
    private $categoriasNivelPonderacion;

        /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idtipocategoria")
     */
    private $idempresa;


    public function __construct()
    {
        $this->preguntas = new ArrayCollection();
        $this->categoriasCargoEscalas =new ArrayCollection();
        $this->categoriasNivelPonderacion =new ArrayCollection();
        
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
            $pregunta->setIdCategoria($this);
        }

        return $this;
    }

    public function removePregunta(Pregunta $pregunta): self
    {
        if ($this->preguntas->removeElement($pregunta)) {
            // set the owning side to null (unless already changed)
            if ($pregunta->getIdCategoria() === $this) {
                $pregunta->setIdCategoria(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getEscalaPonderacion(): ?int
    {
        return $this->escalaPonderacion;
    }

    public function setEscalaPonderacion(int $escalaPonderacion): self
    {
        $this->escalaPonderacion = $escalaPonderacion;

        return $this;
    }
    
    /**
     * @return Collection|CategoriaCargoEscala[]
    */
    public function getCategoriaCargoEscala(): Collection
    {
        return $this->categoriasCargoEscalas;
    }

    /**
     * @return Collection|CategoriaNivelPonderacion[]
    */
    public function getCategpriaNivelPonderacion(): Collection
    {
       return  $this->categoriasNivelPonderacion;
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
