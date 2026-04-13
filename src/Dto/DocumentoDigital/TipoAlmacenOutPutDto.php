<?php

namespace App\Dto\DocumentoDigital;

use App\Entity\DocumentoDigital\DireccionAlmacen;
use App\Repository\DocumentoDigital\TipoAlmacenRepository;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;


/**
 * @ORM\Entity(repositoryClass=TipoAlmacenRepository::class)
 */
class TipoAlmacenOutPutDto
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
    public $nombrealmacen;

    /**
     * @ORM\ManyToOne(targetEntity=DireccionAlmacen::class)
     * @ORM\JoinColumn(nullable=false)
     */
    public $iddireccionalmacen;

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
