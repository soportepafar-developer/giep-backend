<?php

namespace App\Entity\Encuesta;

use App\Entity\CalendarioPluggin\CalendarEvent;
use App\Entity\Proyecto\Empresa;
use App\Entity\User;
use App\Repository\Encuesta\IntrumentoCapAcreditacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IntrumentoCapAcreditacionRepository::class)
 */
class IntrumentoCapAcreditacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
     * @ORM\ManyToOne(targetEntity=InstrumentoCaptura::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $instrumentCapId;

    /**
     * @ORM\ManyToOne(targetEntity=CalendarEvent::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCalendarEvent;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstrumentCapId(): ?InstrumentoCaptura
    {
        return $this->instrumentCapId;
    }

    public function setInstrumentCapId(?InstrumentoCaptura $instrumentCapId): self
    {
        $this->instrumentCapId = $instrumentCapId;

        return $this;
    }

    public function getIdCalendarEvent(): ?CalendarEvent
    {
        return $this->idCalendarEvent;
    }

    public function setIdCalendarEvent(?CalendarEvent $idCalendarEvent): self
    {
        $this->idCalendarEvent = $idCalendarEvent;

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
