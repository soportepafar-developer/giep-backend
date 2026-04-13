<?php

namespace App\Entity\DocumentoDigital;

use App\Repository\DocumentoDigital\ContenidoCajaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContenidoCajaRepository::class)
 */
class ContenidoCaja
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
    private $nombre_estuche;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreEstuche(): ?string
    {
        return $this->nombre_estuche;
    }

    public function setNombreEstuche(string $nombre_estuche): self
    {
        $this->nombre_estuche = $nombre_estuche;

        return $this;
    }
}
