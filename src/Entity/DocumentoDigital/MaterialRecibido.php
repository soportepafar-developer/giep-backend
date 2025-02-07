<?php

namespace App\Entity\DocumentoDigital;

use App\Repository\DocumentoDigital\MaterialRecibidoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaterialRecibidoRepository::class)
 */
class MaterialRecibido
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
    private $nombrematerial;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombrematerial(): ?string
    {
        return $this->nombrematerial;
    }

    public function setNombrematerial(string $nombrematerial): self
    {
        $this->nombrematerial = $nombrematerial;

        return $this;
    }
}
