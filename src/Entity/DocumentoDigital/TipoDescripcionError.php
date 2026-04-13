<?php

namespace App\Entity\DocumentoDigital;

use App\Repository\DocumentoDigital\TipoDescripcionErrorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoDescripcionErrorRepository::class)
 */
class TipoDescripcionError
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $descripcion_error;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcionError(): ?string
    {
        return $this->descripcion_error;
    }

    public function setDescripcionError(string $descripcion_error): self
    {
        $this->descripcion_error = $descripcion_error;

        return $this;
    }
}
