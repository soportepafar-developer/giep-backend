<?php

namespace App\Entity\Proyecto;

use App\Entity\User;
use App\Repository\Proyecto\ItemsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemsRepository::class)
 */
class Items
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=600)
     */
    private $titulo;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUser;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity=Items::class, inversedBy="itemsChild")
     */
    private $idBacklogPadre;

    /**
     * @ORM\OneToMany(targetEntity=Items::class, mappedBy="idBacklogPadre")
     */
    private $itemsChild;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Peso;

    /**
     * @ORM\OneToMany(targetEntity=SprintItem::class, mappedBy="IdItem")
     */
    private $sprintItems;

    /**
     * @ORM\OneToMany(targetEntity=ItemComentario::class, mappedBy="item", orphanRemoval=true)
     */
    private $itemComentario;

    /**
     * @ORM\OneToMany(targetEntity=ItemAdjunto::class, mappedBy="idItem")
     */
    private $itemAdjuntos;

    /**
     * @ORM\ManyToOne(targetEntity=NivelBoardPanel::class, inversedBy="idnivelboardpane")
     */
    private $idnivelboardpanel;

    /**
     * @ORM\ManyToOne(targetEntity=Statusisuues::class, inversedBy="idstatusisuue")
     */
    private $idstatusisuues;

    /**
     * @ORM\ManyToOne(targetEntity=TypeEvent::class, inversedBy="idTypeeven")
     */
    private $idTypeevent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $orden;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $swactivo;
    
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
     * @ORM\OneToMany(targetEntity=ItemsHorasTrabajadas::class, mappedBy="iditems")
     */
    private $iditemshorastrabajadas;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="iditems")
     */
    private $idempresa;

    public function __construct()
    {
        $this->itemsChild = new ArrayCollection();
        $this->sprintItems = new ArrayCollection();
        $this->itemComentario = new ArrayCollection();
        $this->itemAdjuntos = new ArrayCollection();
        $this->iditemshorastrabajadas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

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

    public function getIdBacklogPadre(): ?self
    {
        return $this->idBacklogPadre;
    }

    public function setIdBacklogPadre(?self $idBacklogPadre): self
    {
        $this->idBacklogPadre = $idBacklogPadre;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getItemsChild(): Collection
    {
        return $this->itemsChild;
    }

    public function addItemsChild(self $itemsChild): self
    {
        if (!$this->itemsChild->contains($itemsChild)) {
            $this->itemsChild[] = $itemsChild;
            $itemsChild->setIdBacklogPadre($this);
        }

        return $this;
    }

    public function removeItemsChild(self $itemsChild): self
    {
        if ($this->yes->removeElement($itemsChild)) {
            // set the owning side to null (unless already changed)
            if ($itemsChild->getIdBacklogPadre() === $this) {
                $itemsChild->setIdBacklogPadre(null);
            }
        }

        return $this;
    }

    public function getPeso(): ?int
    {
        return $this->Peso;
    }

    public function setPeso(?int $Peso): self
    {
        $this->Peso = $Peso;

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
            $sprintItem->setIdItem($this);
        }

        return $this;
    }

    public function removeSprintItem(SprintItem $sprintItem): self
    {
        if ($this->sprintItems->removeElement($sprintItem)) {
            // set the owning side to null (unless already changed)
            if ($sprintItem->getIdItem() === $this) {
                $sprintItem->setIdItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ItemComentario[]
     */
    public function getItemComentario(): Collection
    {
        return $this->itemComentario;
    }

    public function addItemComentario(ItemComentario $itemComentario): self
    {
        if (!$this->itemComentario->contains($itemComentario)) {
            $this->itemComentario[] = $itemComentario;
            $itemComentario->setItem($this);
        }

        return $this;
    }

    public function removeItemComentario(ItemComentario $ItemComentario): self
    {
        if ($this->yes->removeElement($ItemComentario)) {
            // set the owning side to null (unless already changed)
            if ($ItemComentario->getItem() === $this) {
                $ItemComentario->setItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ItemAdjunto[]
     */
    public function getItemAdjuntos(): Collection
    {
        return $this->itemAdjuntos;
    }

    public function addItemAdjunto(ItemAdjunto $itemAdjunto): self
    {
        if (!$this->itemAdjuntos->contains($itemAdjunto)) {
            $this->itemAdjuntos[] = $itemAdjunto;
            $itemAdjunto->setIdItem($this);
        }

        return $this;
    }

    public function removeItemAdjunto(ItemAdjunto $itemAdjunto): self
    {
        if ($this->itemAdjuntos->removeElement($itemAdjunto)) {
            // set the owning side to null (unless already changed)
            if ($itemAdjunto->getIdItem() === $this) {
                $itemAdjunto->setIdItem(null);
            }
        }

        return $this;
    }

    public function getIdnivelboardpanel(): ?NivelBoardPanel
    {
        return $this->idnivelboardpanel;
    }

    public function setIdnivelboardpanel(?NivelBoardPanel $idnivelboardpanel): self
    {
        $this->idnivelboardpanel = $idnivelboardpanel;

        return $this;
    }

    public function getIdstatusisuues(): ?Statusisuues
    {
        return $this->idstatusisuues;
    }

    public function setIdstatusisuues(?Statusisuues $idstatusisuues): self
    {
        $this->idstatusisuues = $idstatusisuues;

        return $this;
    }

    public function getIdTypeevent(): ?TypeEvent
    {
        return $this->idTypeevent;
    }

    public function setIdTypeevent(?TypeEvent $idTypeevent): self
    {
        $this->idTypeevent = $idTypeevent;

        return $this;
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(?int $orden): self
    {
        $this->orden = $orden;

        return $this;
    }
    
    public function getSwactivo(): ?int
    {
        return $this->swactivo;
    }

    public function setSwactivo(?int $swactivo): self
    {
        $this->swactivo = $swactivo;

        return $this;
    }
    
        /**
     * @return Collection|ItemsHorasTrabajadas[]
     */
    public function getIditemshorastrabajadas(): Collection
    {
        return $this->iditemshorastrabajadas;
    }

    public function addIditemshorastrabajada(ItemsHorasTrabajadas $iditemshorastrabajada): self
    {
        if (!$this->iditemshorastrabajadas->contains($iditemshorastrabajada)) {
            $this->iditemshorastrabajadas[] = $iditemshorastrabajada;
            $iditemshorastrabajada->setIditems($this);
        }

        return $this;
    }

    public function removeIditemshorastrabajada(ItemsHorasTrabajadas $iditemshorastrabajada): self
    {
        if ($this->iditemshorastrabajadas->removeElement($iditemshorastrabajada)) {
            // set the owning side to null (unless already changed)
            if ($iditemshorastrabajada->getIditems() === $this) {
                $iditemshorastrabajada->setIditems(null);
            }
        }

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
