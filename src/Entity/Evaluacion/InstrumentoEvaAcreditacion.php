<?php

namespace App\Entity\Evaluacion;

use App\Entity\CalendarioPluggin\CalendarEvent;
use App\Entity\Proyecto\Empresa;
use App\Entity\User;
use App\Repository\Evaluacion\InstrumentoEvaAcreditacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InstrumentoEvaAcreditacionRepository::class)
 */
class InstrumentoEvaAcreditacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Evaluacion::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $instrumentEvaId;

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

    public function getInstrumentEvaId(): ?Evaluacion
    {
        return $this->instrumentEvaId;
    }

    public function setInstrumentEvaId(?Evaluacion $instrumentEvaId): self
    {
        $this->instrumentEvaId = $instrumentEvaId;

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
