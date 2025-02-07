<?php

namespace App\Dto\DocumentoDigital;

use App\Repository\DocumentoDigital\MaterialRecibidoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaterialRecibidoRepository::class)
 */
class MaterialRecibidoOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public $nombrematerial;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombrematerial(): ?string
    {
        return $this->nombrematerial;
    }

}
