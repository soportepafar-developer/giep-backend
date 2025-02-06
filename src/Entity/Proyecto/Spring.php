<?php

namespace App\Entity\Proyecto;

use App\Repository\Proyecto\SpringRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpringRepository::class)
 */
class Spring
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechainicio;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechafin;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity=Proyecto::class, inversedBy="springs")
     */
    private $idproyecto;

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
     * @ORM\OneToMany(targetEntity=SprintItem::class, mappedBy="IdSpring")
     */
    private $sprintItems;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idspring")
     */
    private $idempresa;

    public function __construct()
    {
        $this->sprintItems = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechainicio(): ?\DateTimeInterface
    {
        return $this->fechainicio;
    }

    public function setFechainicio(\DateTimeInterface $fechainicio): self
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    public function getFechafin(): ?\DateTimeInterface
    {
        return $this->fechafin;
    }

    public function setFechafin(?\DateTimeInterface $fechafin): self
    {
        $this->fechafin = $fechafin;

        return $this;
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

    public function getIdproyecto(): ?Proyecto
    {
        return $this->idproyecto;
    }

    public function setIdproyecto(?Proyecto $idproyecto): self
    {
        $this->idproyecto = $idproyecto;

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
     * @return Collection|SprintItem[]
     */
    public function getSprintItems(): Collection
    {
        return $this->sprintItems;
    }

    public function addSprintItem(SprintItem $sprintItem): self
    {
        if (!$this->sprintItems->contains($sprintItem)) {
            $this->sprintItems[] = $sprintItem;
            $sprintItem->setIdSpring($this);
        }

        return $this;
    }

    public function removeSprintItem(SprintItem $sprintItem): self
    {
        if ($this->sprintItems->removeElement($sprintItem)) {
            // set the owning side to null (unless already changed)
            if ($sprintItem->getIdSpring() === $this) {
                $sprintItem->setIdSpring(null);
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
