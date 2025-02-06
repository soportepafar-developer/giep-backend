<?php

namespace App\Entity\CalendarioPluggin;

use App\Entity\Acreditacion\ItemAcreditacion;
use App\Entity\Proyecto\Empresa;
use App\Repository\CalendarioPluggin\CalendarUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalendarUserRepository::class)
 */
class CalendarUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CalendarEvent::class, inversedBy="calendarUsers")
     */
    private $idCalendar;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Email;

    /**
     * @ORM\OneToMany(targetEntity=ItemAcreditacion::class, mappedBy="idAcreditacion", orphanRemoval=true)
     */
    private $itemAcreditacions;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idcalendaruser")
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

    public function getIdCalendar(): ?CalendarEvent
    {
        return $this->idCalendar;
    }

    public function setIdCalendar(?CalendarEvent $idCalendar): self
    {
        $this->idCalendar = $idCalendar;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

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
