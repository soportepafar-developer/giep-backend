<?php

namespace App\Entity\Proyecto;

use App\Repository\Proyecto\TypeEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeEventRepository::class)
 */
class TypeEvent
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
    private $tipodescripcion;

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
     * @ORM\OneToMany(targetEntity=Items::class, mappedBy="idTypeevent")
     */
    private $idTypeeven;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $icon_class;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $color;

     /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idtypeevent")
     */
    private $idempresa;

    public function __construct()
    {
        $this->idTypeeven = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipodescripcion(): ?string
    {
        return $this->tipodescripcion;
    }

    public function setTipodescripcion(string $tipodescripcion): self
    {
        $this->tipodescripcion = $tipodescripcion;

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
    public function getIdTypeeven(): Collection
    {
        return $this->idTypeeven;
    }

    public function addIdTypeeven(Items $idTypeeven): self
    {
        if (!$this->idTypeeven->contains($idTypeeven)) {
            $this->idTypeeven[] = $idTypeeven;
            $idTypeeven->setIdTypeevent($this);
        }

        return $this;
    }

    public function removeIdTypeeven(Items $idTypeeven): self
    {
        if ($this->idTypeeven->removeElement($idTypeeven)) {
            // set the owning side to null (unless already changed)
            if ($idTypeeven->getIdTypeevent() === $this) {
                $idTypeeven->setIdTypeevent(null);
            }
        }

        return $this;
    }
    
    public function getIconClass(): ?string
    {
        return $this->icon_class;
    }

    public function setIconClass(string $icon_class): self
    {
        $this->icon_class = $icon_class;

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
