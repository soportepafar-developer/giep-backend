<?php

namespace App\Dto\DocumentoDigital;

use App\Repository\DocumentoDigital\EstadoConservacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EstadoConservacionRepository::class)
 */
class EstadoConservacionOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    public $nombreconservacion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreconservacion(): ?string
    {
        return $this->nombreconservacion;
    }
}
