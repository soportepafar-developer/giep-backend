<?php

namespace App\Entity\Proyecto;

use App\Repository\Proyecto\StatusisuuesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatusisuuesRepository::class)
 */
class Statusisuues
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prioridad;

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
     * @ORM\OneToMany(targetEntity=Items::class, mappedBy="idstatusisuues")
     */
    private $idstatusisuue;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $imagenprioridad;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idstatusisuues")
     */
    private $idempresa;

    public function __construct()
    {
        $this->idstatusisuue = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrioridad(): ?string
    {
        return $this->prioridad;
    }

    public function setPrioridad(string $prioridad): self
    {
        $this->prioridad = $prioridad;

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
     * @return Collection|Items[]
     */
    public function getIdstatusisuue(): Collection
    {
        return $this->idstatusisuue;
    }

    public function addIdstatusisuue(Items $idstatusisuue): self
    {
        if (!$this->idstatusisuue->contains($idstatusisuue)) {
            $this->idstatusisuue[] = $idstatusisuue;
            $idstatusisuue->setIdstatusisuues($this);
        }

        return $this;
    }

    public function removeIdstatusisuue(Items $idstatusisuue): self
    {
        if ($this->idstatusisuue->removeElement($idstatusisuue)) {
            // set the owning side to null (unless already changed)
            if ($idstatusisuue->getIdstatusisuues() === $this) {
                $idstatusisuue->setIdstatusisuues(null);
            }
        }

        return $this;
    }

    public function getImagenprioridad(): ?string
    {
        return $this->imagenprioridad;
    }

    public function setImagenprioridad(?string $imagenprioridad): self
    {
        $this->imagenprioridad = $imagenprioridad;

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
