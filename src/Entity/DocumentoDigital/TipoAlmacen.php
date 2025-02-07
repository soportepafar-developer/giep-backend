<?php

namespace App\Entity\DocumentoDigital;

use App\Repository\DocumentoDigital\TipoAlmacenRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Proyecto\Empresa;

/**
 * @ORM\Entity(repositoryClass=TipoAlmacenRepository::class)
 */
class TipoAlmacen
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
    private $nombrealmacen;

    /**
     * @ORM\ManyToOne(targetEntity=DireccionAlmacen::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $iddireccionalmacen;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombrealmacen(): ?string
    {
        return $this->nombrealmacen;
    }

    public function setNombrealmacen(string $nombrealmacen): self
    {
        $this->nombrealmacen = $nombrealmacen;

        return $this;
    }

    public function getIddireccionalmacen(): ?DireccionAlmacen
    {
        return $this->iddireccionalmacen;
    }

    public function setIddireccionalmacen(?DireccionAlmacen $iddireccionalmacen): self
    {
        $this->iddireccionalmacen = $iddireccionalmacen;

        return $this;
    }

    

}
