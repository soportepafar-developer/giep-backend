<?php

namespace App\Entity\Instrumento360;

use App\Entity\Proyecto\Empresa;
use App\Entity\Status;
use App\Repository\Instrumento360\NivelDominioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NivelDominioRepository::class)
 */
class NivelDominio
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
     * @ORM\Column(type="integer")
     */
    private $valor;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

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
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    private $empresa;

    /**
     * @ORM\OneToMany(targetEntity=Instrumento360Evaluaciones::class, mappedBy="nivelDominio")
     */
    private $instrumento360Evaluaciones;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class)
     */
    private $status;

    public function __construct()
    {
        $this->instrumento360Evaluaciones = new ArrayCollection();
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

    public function getValor(): ?int
    {
        return $this->valor;
    }

    public function setValor(int $valor): self
    {
        $this->valor = $valor;

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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createdatat;
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
        $this->updateat = $updateAt;

        return $this;
    }

    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }

    public function setUpdateby(?string $updateBy): self
    {
        $this->updateBy = $updateBy;

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

    /**
     * @return Collection|Instrumento360Evaluaciones[]
     */
    public function getInstrumento360Evaluaciones(): Collection
    {
        return $this->instrumento360Evaluaciones;
    }

    public function addInstrumento360Evaluacione(Instrumento360Evaluaciones $instrumento360Evaluacione): self
    {
        if (!$this->instrumento360Evaluaciones->contains($instrumento360Evaluacione)) {
            $this->instrumento360Evaluaciones[] = $instrumento360Evaluacione;
            $instrumento360Evaluacione->setNivelDominio($this);
        }

        return $this;
    }

    public function removeInstrumento360Evaluacione(Instrumento360Evaluaciones $instrumento360Evaluacione): self
    {
        if ($this->instrumento360Evaluaciones->removeElement($instrumento360Evaluacione)) {
            // set the owning side to null (unless already changed)
            if ($instrumento360Evaluacione->getNivelDominio() === $this) {
                $instrumento360Evaluacione->setNivelDominio(null);
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
}
