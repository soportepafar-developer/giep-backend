<?php

namespace App\Dto\DocumentoDigital;

use App\Repository\DocumentoDigital\ContenidoCajaRepository;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
/**
 * @ORM\Entity(repositoryClass=ContenidoCajaRepository::class)
 */
class ContenidoCajaOutPutDto
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
    public $nombreestuche;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreEstuche(): ?string
    {
        return $this->nombreestuche;
    }
}
