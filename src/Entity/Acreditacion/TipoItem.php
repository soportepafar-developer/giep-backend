<?php

namespace App\Entity\Acreditacion;

use App\Entity\Proyecto\Empresa;
use App\Repository\Acreditacion\TipoItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoItemRepository::class)
 */
class TipoItem
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
    private $descripcion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $updateBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=ItemAcreditacion::class, mappedBy="IdTipoITem")
     */
    private $itemAcreditacions;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idtipoitem")
     */
    private $idempresa;

    public function __construct()
    {
        $this->itemAcreditacions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|ItemAcreditacion[]
     */
    public function getItemAcreditacions(): Collection
    {
        return $this->itemAcreditacions;
    }

    public function addItemAcreditacion(ItemAcreditacion $itemAcreditacion): self
    {
        if (!$this->itemAcreditacions->contains($itemAcreditacion)) {
            $this->itemAcreditacions[] = $itemAcreditacion;
            $itemAcreditacion->setIdTipoITem($this);
        }

        return $this;
    }

    public function removeItemAcreditacion(ItemAcreditacion $itemAcreditacion): self
    {
        if ($this->itemAcreditacions->removeElement($itemAcreditacion)) {
            // set the owning side to null (unless already changed)
            if ($itemAcreditacion->getIdTipoITem() === $this) {
                $itemAcreditacion->setIdTipoITem(null);
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
