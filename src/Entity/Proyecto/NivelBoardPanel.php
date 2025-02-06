<?php

namespace App\Entity\Proyecto;

use App\Repository\Proyecto\NivelBoardPanelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NivelBoardPanelRepository::class)
 */
class NivelBoardPanel
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
    private $nivelpanel;

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
     * @ORM\OneToMany(targetEntity=Items::class, mappedBy="idnivelboardpanel")
     */
    private $idnivelboardpane;
    
      /**
     * @ORM\Column(type="string", length=255)
     */
    private $attr_key;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idnivelboardpanel")
     */
    private $idempresa;

    public function __construct()
    {
        $this->idnivelboardpane = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNivelpanel(): ?string
    {
        return $this->nivelpanel;
    }

    public function setNivelpanel(string $nivelpanel): self
    {
        $this->nivelpanel = $nivelpanel;

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
    public function getIdnivelboardpane(): Collection
    {
        return $this->idnivelboardpane;
    }

    public function addIdnivelboardpane(Items $idnivelboardpane): self
    {
        if (!$this->idnivelboardpane->contains($idnivelboardpane)) {
            $this->idnivelboardpane[] = $idnivelboardpane;
            $idnivelboardpane->setIdnivelboardpanel($this);
        }

        return $this;
    }

    public function removeIdnivelboardpane(Items $idnivelboardpane): self
    {
        if ($this->idnivelboardpane->removeElement($idnivelboardpane)) {
            // set the owning side to null (unless already changed)
            if ($idnivelboardpane->getIdnivelboardpanel() === $this) {
                $idnivelboardpane->setIdnivelboardpanel(null);
            }
        }

        return $this;
    }
    
     public function getAttrKey(): ?string
    {
        return $this->attr_key;
    }

    public function setAttrKey(string $attr_key): self
    {
        $this->attr_key = $attr_key;

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
