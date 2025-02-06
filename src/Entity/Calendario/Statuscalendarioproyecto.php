<?php

namespace App\Entity\Calendario;

use App\Entity\Proyecto\Empresa;
use App\Entity\Proyecto\Proyecto;
use App\Repository\Calendario\StatuscalendarioproyectoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatuscalendarioproyectoRepository::class)
 */
class Statuscalendarioproyecto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $estado;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $color;

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
     * @ORM\OneToMany(targetEntity=Proyecto::class, mappedBy="idstatuscalendarioproyecto")
     */
    private $idstatuscalendario;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idstatuscalendarioproyecto")
     */
    private $idempresa;

    public function __construct()
    {
        $this->idstatuscalendario = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

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
     * @return Collection|Proyecto[]
     */
    public function getIdstatuscalendario(): Collection
    {
        return $this->idstatuscalendario;
    }

    public function addIdstatuscalendario(Proyecto $idstatuscalendario): self
    {
        if (!$this->idstatuscalendario->contains($idstatuscalendario)) {
            $this->idstatuscalendario[] = $idstatuscalendario;
            $idstatuscalendario->setIdstatuscalendarioproyecto($this);
        }

        return $this;
    }

    public function removeIdstatuscalendario(Proyecto $idstatuscalendario): self
    {
        if ($this->idstatuscalendario->removeElement($idstatuscalendario)) {
            // set the owning side to null (unless already changed)
            if ($idstatuscalendario->getIdstatuscalendarioproyecto() === $this) {
                $idstatuscalendario->setIdstatuscalendarioproyecto(null);
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