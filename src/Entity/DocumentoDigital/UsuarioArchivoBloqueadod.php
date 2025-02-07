<?php

namespace App\Entity\DocumentoDigital;



use App\Repository\DocumentoDigital\UsuarioArchivoBloqueadodRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsuarioArchivoBloqueadodRepository::class)
 */
class UsuarioArchivoBloqueadod
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=Archivosd::class, inversedBy="idarchivobloqueado")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idarchivo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_bloqueo;


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

    
}
