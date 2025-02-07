<?php

namespace App\Dto\DocumentoDigital;

use App\Repository\DocumentoDigital\EstructuraOrganizativaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EstructuraOrganizativaRepository::class)
 */
class EstructuraNivelUnidadOutPutDto
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
    public $nivelunidad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNivelUnidad(): ?string
    {
        return $this->nivelunidad;
    }
}
