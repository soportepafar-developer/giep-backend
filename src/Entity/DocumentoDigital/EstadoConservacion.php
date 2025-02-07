<?php

namespace App\Entity\DocumentoDigital;

use App\Repository\DocumentoDigital\EstadoConservacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EstadoConservacionRepository::class)
 */
class EstadoConservacion
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
    private $nombreconservacion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreconservacion(): ?string
    {
        return $this->nombreconservacion;
    }

    public function setNombreconservacion(string $nombreconservacion): self
    {
        $this->nombreconservacion = $nombreconservacion;

        return $this;
    }
}
