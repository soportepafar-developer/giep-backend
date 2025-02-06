<?php

namespace App\Entity\Acreditacion;

use App\Entity\CalendarioPluggin\CalendarEvent;
use App\Entity\Proyecto\Empresa;
use App\Entity\User;
use App\Repository\Acreditacion\AcreditacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AcreditacionRepository::class)
 */
class Acreditacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CalendarEvent::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $idEvent;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdUser;

    /**
     * @ORM\OneToMany(targetEntity=ItemAcreditacion::class, mappedBy="IdAcreditacion", orphanRemoval=true)
     */
    private $itemAcreditacions;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idacreditacion")
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

    public function getIdEvent(): ?CalendarEvent
    {
        return $this->idEvent;
    }

    public function setIdEvent(?CalendarEvent $idEvent): self
    {
        $this->idEvent = $idEvent;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->IdUser;
    }

    public function setIdUser(?User $IdUser): self
    {
        $this->IdUser = $IdUser;

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
            $itemAcreditacion->setIdAcreditacion($this);
        }

        return $this;
    }

    public function removeItemAcreditacion(ItemAcreditacion $itemAcreditacion): self
    {
        if ($this->itemAcreditacions->removeElement($itemAcreditacion)) {
            // set the owning side to null (unless already changed)
            if ($itemAcreditacion->getIdAcreditacion() === $this) {
                $itemAcreditacion->setIdAcreditacion(null);
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
