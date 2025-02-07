<?php

namespace App\Dto\DocumentoDigital;

use App\Repository\DocumentoDigital\DireccionAlmacenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DireccionAlmacenRepository::class)
 */
class DireccionAlmacenOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $direccionzona;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $nombre;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public $telefono;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDireccionzona(): ?string
    {
        return $this->direccionzona;
    }

    public function setDireccionzona(string $direccionzona): self
    {
        $this->direccionzona = $direccionzona;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }
}
