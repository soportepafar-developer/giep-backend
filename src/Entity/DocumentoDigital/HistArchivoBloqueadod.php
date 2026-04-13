<?php

namespace App\Entity\DocumentoDigital;


use App\Entity\DocumentoDigital\Archivosd;
use App\Repository\DocumentoDigital\HistArchivoBloqueadodRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistArchivoBloqueadodRepository::class)
 */
class HistArchivoBloqueadod
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Archivosd::class, inversedBy="idarchivohistbloqueo")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idarchivo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_bloqueo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_desbloqueo;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getIdarchivo(): ?Archivosd
    {
        return $this->idarchivo;
    }

    public function setIdarchivo(?Archivosd $idarchivo): self
    {
        $this->idarchivo = $idarchivo;

        return $this;
    }

    public function getFechaBloqueo(): ?\DateTimeInterface
    {
        return $this->fecha_bloqueo;
    }

    /**
    * @ORM\PrePersist
    */
    public function setFechaBloqueo()
    {
        $this->fecha_bloqueo = new \DateTime();
        
    }

    public function getFechaDesbloqueo(): ?\DateTimeInterface
    {
        return $this->fecha_desbloqueo;
    }

    public function setFechaDesbloqueo(\DateTimeInterface $fecha_desbloqueo): self
    {
        $this->fecha_desbloqueo = $fecha_desbloqueo;

        return $this;
    }


}
