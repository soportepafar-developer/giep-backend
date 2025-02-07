<?php

namespace App\Dto\DocumentoDigital;

use App\Repository\DocumentoDigital\UbicacionRepository;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;


/**
 * @ORM\Entity(repositoryClass=UbicacionRepository::class)
 */
class UbicacionOutPutDto
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
    public $descripcion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }
}
