<?php

namespace App\Entity\CalendarioPluggin;
use App\Entity\Proyecto\Empresa;
use App\Repository\CalendarioPluggin\CalendarEventCorreoNotifRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalendarEventCorreoNotifRepository::class)
 */
class CalendarEventCorreoNotif
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_calendar_event;

    /**
     * @ORM\Column(type="integer")
     */
    private $swenvio;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCalendarEvent(): ?int
    {
        return $this->id_calendar_event;
    }

    public function setIdCalendarEvent(int $id_calendar_event): self
    {
        $this->id_calendar_event = $id_calendar_event;

        return $this;
    }

    public function getSwenvio(): ?int
    {
        return $this->swenvio;
    }

    public function setSwenvio(int $swenvio): self
    {
        $this->swenvio = $swenvio;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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
