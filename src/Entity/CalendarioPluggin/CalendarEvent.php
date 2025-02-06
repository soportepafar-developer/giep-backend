<?php

namespace App\Entity\CalendarioPluggin;

use App\Entity\Proyecto\Empresa;
use App\Repository\CalendarioPluggin\CalendarEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalendarEventRepository::class)
 * @ORM\HasLifecycleCallbacks() 
 */
class CalendarEvent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end;

    /**
     * @ORM\Column(type="string", length=600)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $classNames;

    /**
     * @ORM\OneToMany(targetEntity=CalendarUser::class, mappedBy="idCalendar", orphanRemoval=true)
     */
    private $calendarUsers;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $updatedBy;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $acreditacion;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idcalendarevent")
     */
    private $idempresa;

    public function __construct()
    {
        $this->calendarUsers = new ArrayCollection();
    }

    /**
    * @ORM\PrePersist
    */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(?\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getClassNames(): ?string
    {
        return $this->classNames;
    }

    public function setClassNames(?string $classNames): self
    {
        $this->classNames = $classNames;

        return $this;
    }

    /**
     * @return Collection|CalendarUser[]
     */
    public function getCalendarUsers(): Collection
    {
        return $this->calendarUsers;
    }

    public function addCalendarUser(CalendarUser $calendarUser): self
    {
        if (!$this->calendarUsers->contains($calendarUser)) {
            $this->calendarUsers[] = $calendarUser;
            $calendarUser->setIdCalendar($this);
        }

        return $this;
    }

    public function removeCalendarUser(CalendarUser $calendarUser): self
    {
        if ($this->calendarUsers->removeElement($calendarUser)) {
            // set the owning side to null (unless already changed)
            if ($calendarUser->getIdCalendar() === $this) {
                $calendarUser->setIdCalendar(null);
            }
        }

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

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getAcreditacion(): ?int
    {
        return $this->acreditacion;
    }

    public function setAcreditacion(?int $acreditacion): self
    {
        $this->acreditacion = $acreditacion;

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
